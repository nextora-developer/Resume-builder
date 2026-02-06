<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ResumeController extends Controller
{
    public function index()
    {
        $resumes = Resume::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('resume.index', compact('resumes'));
    }

    /**
     * Show create form (方案B：不写DB)
     */
    public function create(Request $request)
    {
        $resume = new Resume([
            'uuid' => (string) Str::uuid(),
            'template' => $request->query('template', 'classic'),
            'data' => [],
        ]);

        return view('resume.form', compact('resume'));
    }

    /**
     * Store new resume (按 Save 才写入DB)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'uuid' => ['required', 'uuid'],

            'title' => ['nullable', 'string', 'max:120'],
            'template' => ['nullable', 'in:classic,modern,tech'],

            'data' => ['nullable', 'array'],
            'data.name' => ['nullable', 'string', 'max:120'],
            'data.position' => ['nullable', 'string', 'max:120'],
            'data.summary' => ['nullable', 'string', 'max:2000'],

            // optional fields you are using
            'data.email' => ['nullable', 'string', 'max:120'],
            'data.phone' => ['nullable', 'string', 'max:50'],
            'data.location' => ['nullable', 'string', 'max:120'],

            'data.links' => ['nullable', 'array'],
            'data.links.linkedin' => ['nullable', 'string', 'max:255'],
            'data.links.github' => ['nullable', 'string', 'max:255'],
            'data.links.website' => ['nullable', 'string', 'max:255'],

            'data.skills' => ['nullable', 'array'],
            'data.skills.*' => ['nullable', 'string', 'max:50'],

            'data.languages' => ['nullable', 'array'],
            'data.languages.*' => ['nullable', 'string', 'max:50'],

            'data.experience' => ['nullable', 'array'],
            'data.education' => ['nullable', 'array'],
            'data.projects' => ['nullable', 'array'],

            // uploads
            'avatar' => ['nullable', 'file', 'image', 'max:2048'], // 2MB
            'data.avatar_path' => ['nullable', 'string', 'max:255'],

            'project_pdfs' => ['nullable', 'array'],
            'project_pdfs.*' => ['nullable', 'file', 'mimes:pdf', 'max:5120'], // 5MB
        ]);

        $data = $validated['data'] ?? [];

        // normalize skills: allow comma string -> array
        $data['skills'] = $this->normalizeSkills($data['skills'] ?? null);

        // avatar upload
        if ($request->hasFile('avatar')) {
            $data['avatar_path'] = $request->file('avatar')->store('resume_avatars', 'public');
        }

        // projects pdf upload (index matched)
        $data = $this->handleProjectPdfs($request, $data);

        $resume = Resume::create([
            'uuid' => $validated['uuid'],
            'title' => $validated['title'] ?? null,
            'template' => $validated['template'] ?? 'classic',
            'data' => $data,

            // ✅ 登录就绑定；guest 就 null
            'user_id' => auth()->check() ? auth()->id() : null,
        ]);

        // guest 也能继续编辑（session owner）
        $this->rememberDraft($request, $resume);

        return redirect()
            ->route('resume.edit', $resume)
            ->with('status', 'Created!');
    }

    public function edit(Request $request, Resume $resume)
    {
        $this->authorizeDraftAccess($request, $resume);
        return view('resume.form', compact('resume'));
    }

    public function update(Request $request, Resume $resume)
    {
        $this->authorizeDraftAccess($request, $resume);

        $validated = $request->validate([
            'uuid' => ['required', 'uuid'], // ✅ keep uuid stable

            'title' => ['nullable', 'string', 'max:120'],
            'template' => ['nullable', 'in:classic,modern,tech'],

            'data' => ['nullable', 'array'],
            'data.name' => ['nullable', 'string', 'max:120'],
            'data.position' => ['nullable', 'string', 'max:120'],
            'data.summary' => ['nullable', 'string', 'max:2000'],

            'data.email' => ['nullable', 'string', 'max:120'],
            'data.phone' => ['nullable', 'string', 'max:50'],
            'data.location' => ['nullable', 'string', 'max:120'],

            'data.links' => ['nullable', 'array'],
            'data.links.linkedin' => ['nullable', 'string', 'max:255'],
            'data.links.github' => ['nullable', 'string', 'max:255'],
            'data.links.website' => ['nullable', 'string', 'max:255'],

            'data.skills' => ['nullable', 'array'],
            'data.skills.*' => ['nullable', 'string', 'max:50'],

            'data.languages' => ['nullable', 'array'],
            'data.languages.*' => ['nullable', 'string', 'max:50'],

            'data.experience' => ['nullable', 'array'],
            'data.education' => ['nullable', 'array'],
            'data.projects' => ['nullable', 'array'],

            // uploads
            'avatar' => ['nullable', 'file', 'image', 'max:2048'],
            'data.avatar_path' => ['nullable', 'string', 'max:255'],

            'project_pdfs' => ['nullable', 'array'],
            'project_pdfs.*' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $data = $validated['data'] ?? $resume->data ?? [];

        // normalize skills
        $data['skills'] = $this->normalizeSkills($data['skills'] ?? null);

        // avatar upload (replace path)
        if ($request->hasFile('avatar')) {
            $data['avatar_path'] = $request->file('avatar')->store('resume_avatars', 'public');
        }

        // projects pdf upload
        $data = $this->handleProjectPdfs($request, $data);

        $resume->update([
            // keep uuid consistent (optional)
            'uuid' => $validated['uuid'] ?? $resume->uuid,
            'title' => $validated['title'] ?? $resume->title,
            'template' => $validated['template'] ?? $resume->template,
            'data' => $data,
        ]);

        return back()->with('status', 'Saved!');
    }

    public function preview(Request $request, Resume $resume)
    {
        $this->authorizeDraftAccess($request, $resume);

        $tpl = in_array($resume->template, ['classic', 'modern', 'tech'], true)
            ? $resume->template
            : 'classic';

        return view("resume.templates.$tpl", [
            'data' => $resume->data ?? [],
        ]);
    }

    public function claim(Request $request, Resume $resume)
    {
        abort_unless(auth()->check(), 403);
        $this->authorizeDraftAccess($request, $resume);

        if (!is_null($resume->user_id)) {
            return redirect()->route('resumes.index')->with('status', 'Already saved to your account.');
        }

        $resume->update(['user_id' => $request->user()->id]);

        return redirect()->route('resumes.index')->with('status', 'Resume saved to your account');
    }

    public function claimAfterLogin(Request $request, string $uuid)
    {
        abort_unless(auth()->check(), 403);

        $resume = Resume::where('uuid', $uuid)->first();

        if (!$resume) {
            // 登录后创建一份，并绑定到当前用户
            $resume = Resume::create([
                'uuid' => $uuid,
                'user_id' => $request->user()->id,
                'template' => 'classic',
                'data' => [],
                'title' => null,
            ]);
        } else {
            // 已存在就绑定（如果还没绑定）
            if ($resume->user_id === null) {
                $resume->update(['user_id' => $request->user()->id]);
            }
        }

        $this->rememberDraft($request, $resume);

        return redirect()
            ->route('resume.edit', $resume)
            ->with('status', 'Saved to your account. You can continue editing.');
    }

    public function download(Request $request, Resume $resume)
    {
        abort_unless(auth()->check(), 403);
        abort_unless((int) $resume->user_id === (int) $request->user()->id, 403);

        $tpl = in_array($resume->template, ['classic', 'modern', 'tech'], true)
            ? $resume->template
            : 'classic';

        $data = $resume->data ?? [];

        $title = $resume->title ?: ($data['name'] ?? 'resume');
        $filename = Str::slug($title) . '.pdf';

        // ✅ 一定要用 PDF 专用 view（不是 resume.templates.*）
        $pdf = Pdf::loadView("resume.pdf.$tpl", [
            'data' => $data,
            'resume' => $resume,
        ])
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true);

        return $pdf->stream($filename);
    }



    public function destroy(Request $request, Resume $resume)
    {
        abort_unless(auth()->check(), 403);
        abort_unless((int) $resume->user_id === (int) auth()->id(), 403);

        $resume->delete();

        return redirect()
            ->route('resumes.index')
            ->with('status', 'Resume deleted.');
    }

    // ===== helpers =====

    private function rememberDraft(Request $request, Resume $resume): void
    {
        $drafts = $request->session()->get('draft_resumes', []);
        $drafts[$resume->uuid] = true;
        $request->session()->put('draft_resumes', $drafts);
    }

    private function authorizeDraftAccess(Request $request, Resume $resume): void
    {
        // 1) 已绑定账号：只有 owner 可访问
        if (!is_null($resume->user_id)) {
            abort_unless(auth()->check() && (int) auth()->id() === (int) $resume->user_id, 403);
            return;
        }

        // 2) 未绑定账号：session 必须拥有这份草稿
        $drafts = $request->session()->get('draft_resumes', []);
        abort_unless(isset($drafts[$resume->uuid]), 403);
    }

    private function normalizeSkills($skills): array
    {
        if (is_array($skills)) {
            return array_values(array_filter(array_map('trim', $skills)));
        }

        $skills = (string) $skills;
        if (trim($skills) === '') return [];

        return array_values(array_filter(array_map('trim', explode(',', $skills))));
    }

    private function handleProjectPdfs(Request $request, array $data, array $oldData = []): array
    {
        // 确保 projects 是 array
        $data['projects'] = $data['projects'] ?? [];
        $oldProjects = $oldData['projects'] ?? [];

        // 取得上传的 pdf（key = project index）
        $files = $request->file('project_pdfs', []);

        foreach ($data['projects'] as $idx => &$project) {

            // 1️⃣ 如果这次有新上传 PDF
            if (isset($files[$idx]) && $files[$idx]) {

                // 有旧 pdf → 删除
                if (!empty($oldProjects[$idx]['pdf_path'] ?? null)) {
                    \Illuminate\Support\Facades\Storage::disk('public')
                        ->delete($oldProjects[$idx]['pdf_path']);
                }

                // 存新 pdf
                $project['pdf_path'] = $files[$idx]->store('resume_projects', 'public');
            }

            // 2️⃣ 没新上传，但旧的存在 → 保留旧 pdf_path
            elseif (empty($project['pdf_path']) && !empty($oldProjects[$idx]['pdf_path'] ?? null)) {
                $project['pdf_path'] = $oldProjects[$idx]['pdf_path'];
            }
        }

        return $data;
    }
}
