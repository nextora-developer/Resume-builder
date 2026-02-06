<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $data['name'] ?? 'Dev_Resume' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Tech Typography: Inter for body, JetBrains Mono for technical data --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=JetBrains+Mono:wght@400;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .mono {
            font-family: 'JetBrains Mono', monospace;
        }

        @media print {
            body {
                background: white;
            }

            .no-print {
                display: none;
            }

            a {
                text-decoration: none !important;
                color: inherit !important;
            }
        }
    </style>
</head>

@php
    // ===== align with editor data structure =====
    $links = $data['links'] ?? [];
    $avatarPath = $data['avatar_path'] ?? null;

    $fmtMonth = function ($ym) {
        if (empty($ym) || !is_string($ym) || !str_contains($ym, '-')) {
            return null;
        }
        try {
            return \Carbon\Carbon::createFromFormat('Y-m', $ym)->format('M Y');
        } catch (\Throwable $e) {
            return $ym;
        }
    };

    $periodFrom = function ($start, $end, $current = false) use ($fmtMonth) {
        $s = $fmtMonth($start);
        $e = $current ? 'Present' : $fmtMonth($end);
        if ($s && $e) {
            return $s . ' – ' . $e;
        }
        if ($s) {
            return $s . ' – ' . ($current ? 'Present' : '');
        }
        if ($e) {
            return $e;
        }
        return null;
    };

    $toArray = function ($v) {
        if (is_array($v)) {
            return $v;
        }
        if (is_string($v)) {
            return array_values(array_filter(array_map('trim', explode(',', $v))));
        }
        return [];
    };

    $skillsArr = $toArray($data['skills'] ?? []);
@endphp

<body class="bg-[#f8fafc] text-slate-900 antialiased py-10">
    <div
        class="max-w-[920px] mx-auto bg-white border border-slate-200 shadow-xl print:shadow-none print:border-none overflow-hidden">

        {{-- ================= TECH HEADER ================= --}}
        <header class="bg-[#0f172a] p-10 text-white relative overflow-hidden">
            {{-- Subtle Grid Background Pattern --}}
            <div class="absolute inset-0 opacity-10 pointer-events-none"
                style="background-image: radial-gradient(#334155 1px, transparent 1px); background-size: 20px 20px;">
            </div>

            <div class="relative z-10">
                <div class="flex items-start gap-5 min-w-0">
                    {{-- Avatar --}}
                    @if (!empty($avatarPath))
                        <img src="{{ asset('storage/' . $avatarPath) }}" alt="Profile photo"
                            class="h-28 w-20 sm:h-32 sm:w-24 rounded-2xl object-cover
                           border border-white/10 bg-white/5 shrink-0" />
                    @endif

                    <div class="min-w-0 flex-1">
                        {{-- Name --}}
                        <h1
                            class="text-3xl sm:text-4xl font-extrabold tracking-tighter mono leading-tight whitespace-nowrap">
                            <span class="text-emerald-400">&gt;</span>
                            {{ $data['name'] ?? 'Developer Name' }}
                        </h1>

                        {{-- Position --}}
                        @if (!empty($data['position']))
                            <p class="mt-2 text-slate-300 uppercase mono text-sm font-medium tracking-tight">
                                {{ $data['position'] }}
                            </p>
                        @endif


                        {{-- Contacts UNDER NAME --}}
                        <div class="mt-4 flex flex-wrap gap-2 text-xs mono">
                            @if (!empty($data['email']))
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl
                                   bg-white/5 border border-white/10 text-slate-200">
                                    <span class="text-emerald-400">@</span>
                                    <span class="truncate max-w-[260px]">{{ $data['email'] }}</span>
                                </span>
                            @endif

                            @if (!empty($data['phone']))
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl
                                   bg-white/5 border border-white/10 text-slate-200">
                                    <span class="text-emerald-400">#</span>
                                    {{ $data['phone'] }}
                                </span>
                            @endif

                            @if (!empty($data['location']))
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl
                                   bg-white/5 border border-white/10 text-emerald-300">
                                    <span class="text-emerald-400">📍</span>
                                    {{ $data['location'] }}
                                </span>
                            @endif

                            @if (!empty($links['website']))
                                <a href="{{ $links['website'] }}" target="_blank"
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl
                                   bg-white/5 border border-white/10 text-sky-300 hover:text-white transition">
                                    <span class="text-emerald-400">↗</span> Website
                                </a>
                            @endif

                            @if (!empty($links['linkedin']))
                                <a href="{{ $links['linkedin'] }}" target="_blank"
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl
                                   bg-white/5 border border-white/10 text-sky-300 hover:text-white transition">
                                    <span class="text-emerald-400">in</span> LinkedIn
                                </a>
                            @endif

                            @if (!empty($links['github']))
                                <a href="{{ $links['github'] }}" target="_blank"
                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl
                                   bg-white/5 border border-white/10 text-sky-300 hover:text-white transition">
                                    <span class="text-emerald-400">{ }</span> GitHub
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Summary --}}
                @if (!empty($data['summary']))
                    <div class="mt-8 pt-6 border-t border-slate-700/50 max-w-3xl">
                        <p class="text-sm leading-relaxed text-slate-300">
                            <span class="text-emerald-400 font-bold italic">// Summary:</span>
                            {{ $data['summary'] }}
                        </p>
                    </div>
                @endif
            </div>
        </header>



        <div class="grid grid-cols-12">

            {{-- ================= SIDEBAR: STACK & EDU & PROJECTS ================= --}}
            <aside class="col-span-12 md:col-span-4 bg-slate-50 p-10 border-r border-slate-100">

                {{-- Skills / Tech Stack (supports array or comma string) --}}
                @if (!empty($skillsArr))
                    <section class="mb-10">
                        <h2
                            class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-6 flex items-center gap-2">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Tech_Stack
                        </h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($skillsArr as $skill)
                                @php $skill = trim((string) $skill); @endphp
                                @if ($skill !== '')
                                    <span class="bg-slate-900 text-slate-100 mono text-[11px] px-2.5 py-1 rounded">
                                        {{ $skill }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif

                @php
                    $languagesArr = $toArray($data['languages'] ?? []);
                @endphp


                {{-- Languages --}}
                @if (!empty($languagesArr))
                    <section class="mb-10">
                        <h2
                            class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-6 flex items-center gap-2">
                            <span class="w-2 h-2 bg-indigo-500 rounded-full"></span> Languages
                        </h2>

                        <div class="flex flex-wrap gap-2">
                            @foreach ($languagesArr as $lang)
                                @php $lang = trim((string) $lang); @endphp
                                @if ($lang !== '')
                                    <span
                                        class="bg-white border border-slate-200 text-slate-700 mono text-[11px]
                               px-2.5 py-1 rounded">
                                        {{ $lang }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif



                {{-- Projects (missing in original) --}}
                @if (!empty($data['projects']) && is_array($data['projects']))
                    <section>
                        <h2
                            class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-6 flex items-center gap-2">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Projects
                        </h2>

                        <div class="space-y-7">
                            @foreach ($data['projects'] as $prj)
                                @php
                                    $name = $prj['name'] ?? '';
                                    $role = $prj['role'] ?? '';
                                    $link = $prj['link'] ?? '';
                                    $period = $periodFrom($prj['start'] ?? null, $prj['end'] ?? null, false);

                                    $highlights = $prj['highlights'] ?? [];
                                    if (is_string($highlights)) {
                                        $highlights = array_values(
                                            array_filter(array_map('trim', explode("\n", $highlights))),
                                        );
                                    }
                                    if (!is_array($highlights)) {
                                        $highlights = [];
                                    }

                                    $pdfPath = $prj['pdf_path'] ?? null; // optional if you store it later
                                @endphp

                                @if (!empty($name) || !empty($role) || !empty($link) || !empty($highlights))
                                    <div
                                        class="border border-slate-200 bg-white rounded-xl p-4 shadow-sm shadow-slate-100">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="text-sm font-extrabold text-slate-900 tracking-tight">
                                                    {{ $name ?: 'Project' }}
                                                </p>

                                                @if (!empty($role))
                                                    <p class="text-xs text-slate-500 mt-1 mono">
                                                        {{ $role }}
                                                    </p>
                                                @endif

                                                <div
                                                    class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs mono">
                                                    @if (!empty($link))
                                                        <a href="{{ $link }}" target="_blank"
                                                            class="text-sky-600 hover:underline break-all">
                                                            {{ str_replace(['https://', 'http://'], '', $link) }}
                                                        </a>
                                                    @endif
                                                    @if (!empty($pdfPath))
                                                        <a href="{{ asset('storage/' . $pdfPath) }}" target="_blank"
                                                            class="text-sky-600 hover:underline">
                                                            PDF
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>

                                            @if (!empty($period))
                                                <span
                                                    class="text-[10px] mono font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded">
                                                    {{ $period }}
                                                </span>
                                            @endif
                                        </div>

                                        @if (!empty($highlights))
                                            <ul class="mt-3 space-y-2">
                                                @foreach ($highlights as $line)
                                                    @php $line = trim((string) $line); @endphp
                                                    @if ($line !== '')
                                                        <li
                                                            class="flex gap-3 text-[12px] text-slate-600 leading-relaxed">
                                                            <span class="text-slate-300 mt-1.5 mono font-bold">#</span>
                                                            <span>{{ trim($line, '• ') }}</span>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif
            </aside>


            <main class="col-span-12 md:col-span-8 p-10">
                {{-- ================= EDUCATION (NOW ABOVE WORK) ================= --}}
                @if (!empty($data['education']) && is_array($data['education']))
                    <section class="mb-12">
                        <h2
                            class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-8 flex items-center gap-2">
                            <span class="w-2 h-2 bg-sky-500 rounded-full"></span> Education.log()
                        </h2>

                        <div class="space-y-6">
                            @foreach ($data['education'] as $edu)
                                @php
                                    $school = $edu['school'] ?? '';
                                    $degree = $edu['degree'] ?? '';
                                    $field = $edu['field'] ?? '';
                                    $notes = $edu['notes'] ?? '';
                                    $period =
                                        $edu['period'] ??
                                        $periodFrom($edu['start'] ?? null, $edu['end'] ?? null, false);
                                @endphp

                                @if (!empty($school) || !empty($degree) || !empty($field) || !empty($notes))
                                    <div class="relative border-l-2 border-slate-100 pl-8 ml-1">
                                        {{-- Bullet (match experience style) --}}
                                        <div
                                            class="absolute -left-[9px] top-0 w-4 h-4 bg-white border-2 border-slate-900 rounded-sm rotate-45">
                                        </div>

                                        <div
                                            class="flex flex-col sm:flex-row sm:items-baseline justify-between mb-2 gap-2">
                                            <p
                                                class="text-base font-extrabold text-slate-900 tracking-tight leading-snug">
                                                {{ $school }}
                                            </p>

                                            @if (!empty($period))
                                                <span
                                                    class="text-[11px] mono font-bold text-sky-600 bg-sky-50 px-2 py-0.5 rounded">
                                                    {{ $period }}
                                                </span>
                                            @endif
                                        </div>

                                        <p class="text-sm text-slate-600 mono">
                                            {{ $degree }}
                                            @if (!empty($field))
                                                <span class="text-slate-300 mx-1">•</span>{{ $field }}
                                            @endif
                                        </p>

                                        @if (!empty($notes))
                                            <p class="text-[13px] text-slate-600 mt-3 leading-relaxed">
                                                {{ $notes }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif


                {{-- ================= EXPERIENCE ================= --}}
                @if (!empty($data['experience']) && is_array($data['experience']))
                    <section>
                        <h2
                            class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-8 flex items-center gap-2">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Experience.log()
                        </h2>

                        {{-- ✅ 下面这里保持你原本 Experience 的代码不动 --}}
                        <div class="space-y-6">
                            @foreach ($data['experience'] as $exp)
                                @php
                                    $role = $exp['role'] ?? '';
                                    $company = $exp['company'] ?? '';
                                    $loc = $exp['location'] ?? '';
                                    $period =
                                        $exp['period'] ??
                                        $periodFrom(
                                            $exp['start'] ?? null,
                                            $exp['end'] ?? null,
                                            !empty($exp['current']),
                                        );

                                    // editor uses highlights[]; old template used description newline
                                    $highlights = $exp['highlights'] ?? null;
                                    $desc = $exp['description'] ?? null;

                                    if (is_string($highlights)) {
                                        $highlights = array_values(
                                            array_filter(array_map('trim', explode("\n", $highlights))),
                                        );
                                    }
                                    if (!is_array($highlights)) {
                                        $highlights = [];
                                    }

                                    if (empty($highlights) && is_string($desc)) {
                                        $highlights = array_values(
                                            array_filter(array_map('trim', explode("\n", $desc))),
                                        );
                                    }
                                @endphp

                                @if (!empty($role) || !empty($company) || !empty($highlights))
                                    <div class="relative border-l-2 border-slate-100 pl-8 ml-1">
                                        {{-- Custom Bullet --}}
                                        <div
                                            class="absolute -left-[9px] top-0 w-4 h-4 bg-white border-2 border-slate-900 rounded-sm rotate-45">
                                        </div>

                                        <div
                                            class="flex flex-col sm:flex-row sm:items-baseline justify-between mb-2 gap-2">
                                            <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">
                                                {{ $role ?: 'Role' }}
                                            </h3>

                                            @if (!empty($period))
                                                <span
                                                    class="text-[11px] mono font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">
                                                    {{ $period }}
                                                </span>
                                            @endif
                                        </div>

                                        <div
                                            class="text-sm font-bold text-slate-500 mb-4 mono uppercase tracking-wider">
                                            @ {{ $company }}
                                            @if (!empty($loc))
                                                <span class="text-slate-300 mx-2">•</span>
                                                <span class="text-slate-500">{{ $loc }}</span>
                                            @endif
                                        </div>

                                        @if (!empty($highlights))
                                            <ul class="space-y-2.5">
                                                @foreach ($highlights as $line)
                                                    @php $line = trim((string) $line); @endphp
                                                    @if ($line !== '')
                                                        <li
                                                            class="flex gap-3 text-[13px] text-slate-600 leading-relaxed">
                                                            <span class="text-slate-300 mt-1.5 mono font-bold">#</span>
                                                            <span>{{ trim($line, '• ') }}</span>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif
            </main>

        </div>

        {{-- Footer-like detail --}}
        <footer class="bg-slate-50 border-t border-slate-100 px-10 py-4 flex justify-between items-center no-print">
            <span class="text-[10px] mono text-slate-400 uppercase tracking-widest">Build Version: 2026.1.0</span>
            <span class="text-[10px] mono text-slate-400 italic">// End of Document</span>
        </footer>
    </div>
</body>

</html>
