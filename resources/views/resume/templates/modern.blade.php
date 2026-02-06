<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $data['name'] ?? 'Resume' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Modern Geometric Typeface --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
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
    // ===== helpers / alignment with editor data structure =====
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
            return array_values(array_filter(array_map('trim', $v), fn($x) => $x !== ''));
        }

        if (is_string($v)) {
            $v = trim($v);
            if ($v === '') {
                return [];
            }

            if (str_contains($v, "\n")) {
                return array_values(
                    array_filter(
                        array_map(fn($x) => trim($x, "• \t"), preg_split("/\r\n|\n|\r/", $v)),
                        fn($x) => $x !== '',
                    ),
                );
            }

            return array_values(array_filter(array_map('trim', explode(',', $v)), fn($x) => $x !== ''));
        }

        return [];
    };

    $skillsArr = $toArray($data['skills'] ?? []);
    $languagesArr = $toArray($data['languages'] ?? []);
@endphp

<body class="bg-slate-50 text-slate-900 antialiased py-12 px-4">
    <div
        class="max-w-[920px] mx-auto bg-white shadow-2xl shadow-slate-200/50 rounded-[2rem] overflow-hidden print:shadow-none print:rounded-none">

        {{-- ================= HEADER ================= --}}
        <header class="bg-slate-900 px-8 sm:px-12 py-10 sm:py-12 text-white">
            <div class="flex items-start gap-6">
                @if (!empty($avatarPath))
                    <img src="{{ asset('storage/' . $avatarPath) }}" alt="Profile photo"
                        class="h-28 w-22 sm:h-32 sm:w-24 rounded-xl object-cover border border-white/10 bg-white/5 shrink-0" />
                @endif

                <div class="min-w-0 flex-1">
                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight leading-tight">
                        {{ $data['name'] ?? 'Your Name' }}
                    </h1>

                    @if (!empty($data['position']))
                        <p class="mt-2 text-indigo-300 font-semibold tracking-wide uppercase text-xs sm:text-sm">
                            {{ $data['position'] }}
                        </p>
                    @endif


                    <div class="mt-5 flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-200">
                        @if (!empty($data['email']))
                            <span class="inline-flex items-center gap-2 min-w-0">
                                <span class="text-indigo-300">@</span>
                                <span class="truncate">{{ $data['email'] }}</span>
                            </span>
                        @endif

                        @if (!empty($data['phone']))
                            <span class="inline-flex items-center gap-2 min-w-0">
                                <span class="text-indigo-300">#</span>
                                <span class="truncate">{{ $data['phone'] }}</span>
                            </span>
                        @endif

                        @if (!empty($data['location']))
                            <span class="inline-flex items-center gap-2 text-slate-300 min-w-0">
                                <span>📍</span>
                                <span class="truncate">{{ $data['location'] }}</span>
                            </span>
                        @endif

                        @if (!empty($links['website']))
                            <a href="{{ $links['website'] }}" target="_blank"
                                class="inline-flex items-center gap-2 text-slate-200 hover:text-white transition">
                                <span class="text-indigo-300">↗</span>
                                <span class="underline underline-offset-4 decoration-white/30 hover:decoration-white">
                                    Website
                                </span>
                            </a>
                        @endif

                        @if (!empty($links['linkedin']))
                            <a href="{{ $links['linkedin'] }}" target="_blank"
                                class="inline-flex items-center gap-2 text-slate-200 hover:text-white transition">
                                <span class="text-indigo-300">in</span>
                                <span class="underline underline-offset-4 decoration-white/30 hover:decoration-white">
                                    Linkedin
                                </span>
                            </a>
                        @endif

                        @if (!empty($links['github']))
                            <a href="{{ $links['github'] }}" target="_blank"
                                class="inline-flex items-center gap-2 text-slate-200 hover:text-white transition">
                                <span class="text-indigo-300">{ }</span>
                                <span class="underline underline-offset-4 decoration-white/30 hover:decoration-white">
                                    Github
                                </span>
                            </a>
                        @endif

                    </div>
                </div>
            </div>
        </header>




        {{-- ================= ABOUT ME (FULL WIDTH ROW) ================= --}}
        @if (!empty($data['summary']))
            <section class="p-12 pb-0">
                <h2 class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-slate-400 mb-4">
                    About Me
                </h2>
                <p class="text-sm leading-relaxed text-slate-600 font-medium max-w-4xl">
                    {{ $data['summary'] }}
                </p>
            </section>
        @endif

        {{-- ================= EXPERTISE | WORK (TWO COLUMNS) ================= --}}
        <div class="grid grid-cols-12">

            {{-- ================= LEFT: Expertise / Education / Projects ================= --}}
            <aside class="col-span-12 lg:col-span-4 bg-slate-50/50 p-12 border-r border-slate-100">

                {{-- Skills (supports array or comma string) --}}
                @if (!empty($skillsArr))
                    <section class="mb-10">
                        <h2 class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-slate-400 mb-5">
                            Skills
                        </h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($skillsArr as $skill)
                                @php $skill = trim((string) $skill); @endphp
                                @if ($skill !== '')
                                    <span
                                        class="bg-white border border-slate-200 text-slate-700 px-3 py-1.5 rounded-xl text-xs font-semibold shadow-sm shadow-slate-100">
                                        {{ $skill }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Projects (date style like Education) --}}
                @if (!empty($data['projects']) && is_array($data['projects']))
                    <section class="mb-10">
                        <h2 class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-slate-400 mb-5">
                            Projects
                        </h2>

                        <div class="space-y-6">
                            @foreach ($data['projects'] as $prj)
                                @php
                                    $name = $prj['name'] ?? '';
                                    $role = $prj['role'] ?? '';
                                    $link = $prj['link'] ?? '';
                                    $pdfPath = $prj['pdf_path'] ?? ($prj['pdf'] ?? ($prj['pdf_url'] ?? ''));

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
                                @endphp

                                @if (!empty($name) || !empty($role) || !empty($link) || !empty($pdfPath) || !empty($highlights))
                                    <div>
                                        {{-- Name --}}
                                        <p class="text-sm font-bold text-slate-800 leading-tight">
                                            {{ $name ?: 'Project' }}
                                        </p>

                                        {{-- Date --}}
                                        @if (!empty($period))
                                            <p
                                                class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider mt-1">
                                                {{ $period }}
                                            </p>
                                        @endif

                                        {{-- Role --}}
                                        @if (!empty($role))
                                            <p class="text-xs text-slate-500 mt-1 font-medium">
                                                {{ $role }}
                                            </p>
                                        @endif

                                        {{-- Links row --}}
                                        @if (!empty($link) || !empty($pdfPath))
                                            <div class="mt-2 flex flex-wrap items-center gap-2 text-[11px] font-bold">
                                                @if (!empty($link))
                                                    <a href="{{ $link }}" target="_blank"
                                                        class="inline-flex items-center h-7 px-2.5 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition">
                                                        Live Link
                                                    </a>
                                                @endif

                                                @if (!empty($pdfPath))
                                                    <a href="{{ asset('storage/' . $pdfPath) }}" target="_blank"
                                                        class="inline-flex items-center h-7 px-2.5 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                                                        View PDF
                                                    </a>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- Highlights --}}
                                        @if (!empty($highlights))
                                            <ul class="mt-3 space-y-2">
                                                @foreach ($highlights as $line)
                                                    @php $line = trim((string) $line); @endphp
                                                    @if ($line !== '')
                                                        <li class="flex gap-3 text-xs text-slate-600 leading-relaxed">
                                                            <span class="text-indigo-300 mt-1.5 text-[8px]">◆</span>
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

                {{-- Languages --}}
                @if (!empty($languagesArr))
                    <section>
                        <h2 class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-slate-400 mb-5">
                            Languages
                        </h2>

                        <div class="flex flex-wrap gap-2.5">
                            @foreach ($languagesArr as $lang)
                                @php $lang = trim((string) $lang); @endphp
                                @if ($lang !== '')
                                    <span
                                        class="bg-white border border-slate-200 text-slate-700 px-3 py-1.5 rounded-xl text-xs font-semibold shadow-sm shadow-slate-100">
                                        {{ $lang }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif


            </aside>

            {{-- ================= RIGHT: Work Experience ================= --}}
            <main class="col-span-12 lg:col-span-8 p-12">

                {{-- ================= EDUCATION ================= --}}
                @if (!empty($data['education']) && is_array($data['education']))
                    <section class="mb-12">
                        <h2 class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-slate-400 mb-4">
                            Education
                        </h2>

                        <div class="space-y-8">
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

                                @if ($school || $degree || $field || $notes)
                                    <div>
                                        <div class="flex items-baseline justify-between gap-3">
                                            <p class="text-base font-bold text-slate-900 leading-tight">
                                                {{ $school }}
                                            </p>

                                            @if (!empty($period))
                                                <span
                                                    class="shrink-0 text-[10px] font-bold text-indigo-500 uppercase tracking-wider">
                                                    {{ $period }}
                                                </span>
                                            @endif
                                        </div>

                                        <p class="text-sm text-slate-600 mt-1 font-medium">
                                            {{ $degree }}
                                            @if (!empty($field))
                                                <span class="text-slate-300 mx-1">•</span>{{ $field }}
                                            @endif
                                        </p>

                                        @if (!empty($notes))
                                            <p class="text-sm text-slate-600 mt-3 leading-relaxed">
                                                {{ $notes }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- ================= WORK EXPERIENCE ================= --}}
                @if (!empty($data['experience']) && is_array($data['experience']))
                    <section>
                        <h2 class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-slate-400 mb-4">
                            Work Experience
                        </h2>

                        <div class="space-y-10">
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

                                @if ($role || $company || $highlights)
                                    <div class="relative group">
                                        <div
                                            class="absolute -left-[53px] top-1.5 w-2.5 h-2.5 rounded-full border-2 border-indigo-500 bg-white hidden lg:block">
                                        </div>

                                        <div
                                            class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                                            <h3 class="text-lg font-bold text-slate-900 tracking-tight">
                                                {{ $role }}
                                            </h3>

                                            @if (!empty($period))
                                                <span
                                                    class="text-xs font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-md uppercase tracking-wide">
                                                    {{ $period }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="text-sm font-semibold text-indigo-600 mb-4 flex items-center gap-2">
                                            {{ $company }}
                                            @if (!empty($loc))
                                                <span class="text-indigo-200">•</span>
                                                <span class="text-slate-500 font-semibold">{{ $loc }}</span>
                                            @endif
                                        </div>

                                        @if (!empty($highlights))
                                            <ul class="space-y-3">
                                                @foreach ($highlights as $line)
                                                    <li class="flex gap-3 text-sm text-slate-600 leading-relaxed">
                                                        <span class="text-indigo-300 mt-1.5 text-[8px]">◆</span>
                                                        <span>{{ trim($line, '• ') }}</span>
                                                    </li>
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

    </div>

    {{-- Tip for the user --}}
    <p class="text-center text-slate-400 text-xs mt-8 no-print">
        Modern Layout optimized for Screen Reading & PDF Export
    </p>
</body>

</html>
