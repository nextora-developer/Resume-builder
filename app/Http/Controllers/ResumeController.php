<?php

namespace App\Http\Controllers;

use App\Models\Resume;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    public function index()
    {
        $resumes = Resume::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('resume.index', compact('resumes'));
    }

    public function create(Request $request)
    {
        $resume = Resume::create([
            'template' => $request->query('template', 'classic'),
            'data' => [],
        ]);

        $this->rememberDraft($request, $resume);

        return redirect()->route('resume.edit', $resume);
    }


    public function storeDraft(Request $request)
    {
        $resume = Resume::create([
            'template' => $request->input('template', 'classic'),
            'data' => $request->input('data', []),
        ]);

        $this->rememberDraft($request, $resume);

        return redirect()->route('resume.edit', $resume);
    }

    public function edit(Request $request, Resume $resume)
    {
        // 非 owner（匿名草稿也算 owner）不给编辑
        $this->authorizeDraftAccess($request, $resume);

        return view('resume.form', compact('resume'));
    }

    public function update(Request $request, Resume $resume)
    {
        $this->authorizeDraftAccess($request, $resume);

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:120'],
            'template' => ['nullable', 'in:classic,modern,tech'],
            'data' => ['nullable', 'array'],
        ]);

        $resume->update([
            'title' => $data['title'] ?? $resume->title,
            'template' => $data['template'] ?? $resume->template,
            'data' => $data['data'] ?? $resume->data,
        ]);

        return back()->with('status', 'Saved!');
    }

    public function preview(Request $request, Resume $resume)
    {
        // 预览你可以选择公开或限制，我先也做“需要是 owner 才能看”，更安全
        $this->authorizeDraftAccess($request, $resume);

        return view("resume.templates.{$resume->template}", [
            'data' => $resume->data ?? [],
        ]);
    }

    public function claim(Request $request, Resume $resume)
    {
        $this->authorizeDraftAccess($request, $resume);

        $resume->update(['user_id' => $request->user()->id]);

        return redirect()
            ->route('resumes.index')
            ->with('status', 'Resume saved to your account');
    }

    public function download(Request $request, Resume $resume)
    {
        // 先不做 PDF 生成，先把 gate 做好：必须登录 + 必须是 owner
        abort_unless($resume->user_id === $request->user()->id, 403);

        // 下一步我们再接 PDF（Browsershot / Dompdf）
        return response("TODO: PDF generation", 200);
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
        // 1) 如果这份 resume 已绑定账号：只有该 user 可访问
        if (!is_null($resume->user_id)) {
            abort_unless(auth()->check() && auth()->id() === $resume->user_id, 403);
            return;
        }

        // 2) 没绑定账号：检查 session 是否拥有这份草稿
        $drafts = $request->session()->get('draft_resumes', []);
        abort_unless(isset($drafts[$resume->uuid]), 403);
    }

    public function destroy(Resume $resume)
    {
        abort_unless(auth()->check(), 403);

        // 只能删除自己的
        abort_unless((int) $resume->user_id === (int) auth()->id(), 403);

        $resume->delete();

        return redirect()
            ->route('resumes.index')
            ->with('status', 'Resume deleted.');
    }
}
