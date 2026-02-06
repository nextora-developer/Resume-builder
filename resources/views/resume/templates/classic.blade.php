<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Resume - {{ $data['name'] ?? 'Professional' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        h1,
        h2,
        .serif {
            font-family: 'Libre+Baskerville', serif;
        }

        @media print {
            body {
                background: white;
            }

            .max-w-screen-md {
                width: 100%;
                max-width: 100%;
                margin: 0;
                padding: 0;
            }

            a {
                text-decoration: none !important;
                color: inherit !important;
            }
        }
    </style>
</head>

@php
    // ===== helpers =====
    $links = $data['links'] ?? [];

    $fmtMonth = function ($ym) {
        // expects YYYY-MM
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

            // if contains newline -> treat as bullets
            if (str_contains($v, "\n")) {
                return array_values(
                    array_filter(
                        array_map(fn($x) => trim($x, "• \t"), preg_split("/\r\n|\n|\r/", $v)),
                        fn($x) => $x !== '',
                    ),
                );
            }

            // default: comma separated
            return array_values(array_filter(array_map('trim', explode(',', $v)), fn($x) => $x !== ''));
        }

        return [];
    };

    $avatarPath = $data['avatar_path'] ?? null;
@endphp

<body class="bg-gray-50 text-slate-900 antialiased">
    <div class="max-w-[820px] mx-auto bg-white my-8 shadow-lg print:shadow-none px-12 py-14">

        {{-- ================= HEADER ================= --}}
        <header class="border-b-2 border-slate-900 pb-8 mb-10">

            <div class="flex items-center gap-6">
                {{-- Avatar --}}
                @if (!empty($avatarPath))
                    <div class="shrink-0">
                        <img src="{{ asset('storage/' . $avatarPath) }}" alt="Profile photo"
                            class="h-32 w-24 rounded-2xl object-cover border border-slate-200" />
                    </div>
                @endif

                <div class="flex-1">
                    <div class="text-center {{ !empty($avatarPath) ? 'md:text-left' : '' }}">
                        <h1 class="text-4xl font-bold tracking-tight text-slate-900 uppercase">
                            {{ $data['name'] ?? 'Your Name' }}
                        </h1>

                        {{-- Position (TOP LEVEL) --}}
                        @if (!empty($data['position']))
                            <p class="mt-2 text-md tracking-[0.2em] font-medium text-slate-500 uppercase">
                                {{ $data['position'] }}
                            </p>
                        @endif


                        {{-- Contacts --}}
                        <div
                            class="mt-4 flex flex-wrap justify-center {{ !empty($avatarPath) ? 'md:justify-start' : '' }} items-center gap-x-6 gap-y-1 text-xs font-medium text-slate-600">
                            @if (!empty($data['email']))
                                <span>{{ $data['email'] }}</span>
                            @endif
                            @if (!empty($data['phone']))
                                <span>{{ $data['phone'] }}</span>
                            @endif
                            @if (!empty($data['location']))
                                <span>{{ $data['location'] }}</span>
                            @endif

                            <br>

                            {{-- Links (data[links]) --}}
                            @if (!empty($links['website']))
                                <a class="underline underline-offset-2" href="{{ $links['website'] }}" target="_blank">
                                    Website
                                </a>
                            @endif

                            @if (!empty($links['linkedin']))
                                <a class="underline underline-offset-2" href="{{ $links['linkedin'] }}"
                                    target="_blank">
                                    LinkedIn
                                </a>
                            @endif
                            @if (!empty($links['github']))
                                <a class="underline underline-offset-2" href="{{ $links['github'] }}" target="_blank">
                                    GitHub
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </header>

        {{-- ================= SUMMARY ================= --}}
        @if (!empty($data['summary']))
            <section class="mb-10">
                <h2
                    class="text-xs font-bold uppercase tracking-[0.25em] text-slate-900 border-b border-slate-200 mb-3 pb-1">
                    Professional Profile
                </h2>
                <p class="text-[13px] leading-relaxed text-slate-700">
                    {{ $data['summary'] }}
                </p>
            </section>
        @endif

        {{-- ================= EXPERIENCE ================= --}}
        @if (!empty($data['experience']) && is_array($data['experience']))
            <section class="mb-10">
                <h2
                    class="text-xs font-bold uppercase tracking-[0.25em] text-slate-900 border-b border-slate-200 mb-5 pb-1">
                    Professional Experience
                </h2>

                <div class="space-y-8">
                    @foreach ($data['experience'] as $exp)
                        @php
                            $role = $exp['role'] ?? '';
                            $company = $exp['company'] ?? '';
                            $loc = $exp['location'] ?? '';
                            $period =
                                $exp['period'] ??
                                $periodFrom($exp['start'] ?? null, $exp['end'] ?? null, !empty($exp['current']));
                            $highlights = $exp['highlights'] ?? null;

                            // Backward compatibility: if you still have description as newline string
                            $desc = $exp['description'] ?? null;

                            if (is_string($highlights)) {
                                $highlights = array_values(array_filter(array_map('trim', explode("\n", $highlights))));
                            }
                            if (!is_array($highlights)) {
                                $highlights = [];
                            }

                            // If highlights empty but description exists, convert description to bullets
                            if (empty($highlights) && is_string($desc)) {
                                $highlights = array_values(array_filter(array_map('trim', explode("\n", $desc))));
                            }
                        @endphp

                        @if (!empty($role) || !empty($company) || !empty($highlights))
                            <div>
                                <div class="flex justify-between items-baseline mb-1 gap-4">
                                    <h3 class="font-bold text-[15px] text-slate-900">
                                        {{ $role ?: 'Role' }}
                                    </h3>

                                    @if (!empty($period))
                                        <span class="text-[12px] font-semibold text-slate-500 uppercase tracking-wider">
                                            {{ $period }}
                                        </span>
                                    @endif
                                </div>

                                <div class="text-[13px] font-medium text-slate-600 mb-3">
                                    {{ $company }}
                                    @if (!empty($loc))
                                        <span class="text-slate-400 mx-2">•</span>
                                        <span class="text-slate-600">{{ $loc }}</span>
                                    @endif
                                </div>

                                @if (!empty($highlights))
                                    <ul class="list-none text-[13px] text-slate-700 space-y-2 ml-1">
                                        @foreach ($highlights as $line)
                                            @php $line = trim((string) $line); @endphp
                                            @if ($line !== '')
                                                <li
                                                    class="relative pl-5 before:content-['•'] before:absolute before:left-0 before:text-slate-400">
                                                    {{ trim($line, "• \t") }}
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

        {{-- ================= EDUCATION ================= --}}
        @if (!empty($data['education']) && is_array($data['education']))
            <section class="mb-10">
                <h2
                    class="text-xs font-bold uppercase tracking-[0.25em] text-slate-900 border-b border-slate-200 mb-5 pb-1">
                    Education
                </h2>

                <div class="space-y-4">
                    @foreach ($data['education'] as $edu)
                        @php
                            $school = $edu['school'] ?? '';
                            $degree = $edu['degree'] ?? '';
                            $field = $edu['field'] ?? '';
                            $notes = $edu['notes'] ?? '';
                            $period = $edu['period'] ?? $periodFrom($edu['start'] ?? null, $edu['end'] ?? null, false);
                        @endphp

                        @if (!empty($school) || !empty($degree) || !empty($field) || !empty($notes))
                            <div>
                                <div class="flex justify-between items-baseline gap-4">
                                    <div class="min-w-0">
                                        <span class="font-bold text-[14px] text-slate-900">{{ $school }}</span>

                                        @if (!empty($degree) || !empty($field))
                                            <span class="text-slate-300 mx-2">|</span>
                                            <span class="text-[13px] text-slate-700">
                                                {{ $degree }}
                                                @if (!empty($field))
                                                    <span class="text-slate-400 mx-1">—</span>
                                                    {{ $field }}
                                                @endif
                                            </span>
                                        @endif
                                    </div>

                                    @if (!empty($period))
                                        <div class="shrink-0 text-[12px] font-semibold text-slate-500 uppercase">
                                            {{ $period }}
                                        </div>
                                    @endif
                                </div>

                                @if (!empty($notes))
                                    <div class="mt-2 text-[13px] text-slate-700">
                                        {{ $notes }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ================= PROJECTS ================= --}}
        @if (!empty($data['projects']) && is_array($data['projects']))
            <section class="mb-10">
                <h2
                    class="text-xs font-bold uppercase tracking-[0.25em] text-slate-900 border-b border-slate-200 mb-5 pb-1">
                    Projects
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
                                $highlights = array_values(array_filter(array_map('trim', explode("\n", $highlights))));
                            }
                            if (!is_array($highlights)) {
                                $highlights = [];
                            }

                            // optional if you store it later
                            $pdfPath = $prj['pdf_path'] ?? ($prj['pdf'] ?? ($prj['pdf_url'] ?? null));
                        @endphp

                        @if (!empty($name) || !empty($role) || !empty($link) || !empty($highlights))
                            <div>
                                <div class="flex justify-between items-baseline gap-4">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                                            <h3 class="font-bold text-[15px] text-slate-900">
                                                {{ $name ?: 'Project' }}
                                            </h3>

                                            @if (!empty($role))
                                                <span class="text-[13px] text-slate-600">
                                                    ({{ $role }})
                                                </span>
                                            @endif
                                        </div>

                                        <div
                                            class="mt-1 flex flex-wrap items-center gap-x-4 gap-y-1 text-[12px] text-slate-600">
                                            @if (!empty($link))
                                                <a href="{{ $link }}" target="_blank"
                                                    class="underline underline-offset-2">
                                                    {{ $link }}
                                                </a>
                                            @endif

                                            @if (!empty($pdfPath))
                                                <a href="{{ asset('storage/' . $pdfPath) }}" target="_blank"
                                                    class="underline underline-offset-2">
                                                    View Project
                                                </a>
                                            @endif
                                        </div>
                                    </div>

                                    @if (!empty($period))
                                        <div
                                            class="shrink-0 text-[12px] font-semibold text-slate-500 uppercase tracking-wider">
                                            {{ $period }}
                                        </div>
                                    @endif
                                </div>

                                @if (!empty($highlights))
                                    <ul class="mt-3 list-none text-[13px] text-slate-700 space-y-2 ml-1">
                                        @foreach ($highlights as $line)
                                            @php $line = trim((string) $line); @endphp
                                            @if ($line !== '')
                                                <li
                                                    class="relative pl-5 before:content-['•'] before:absolute before:left-0 before:text-slate-400">
                                                    {{ trim($line, "• \t") }}
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

        {{-- ================= SKILLS ================= --}}
        @php
            $skillsArr = $toArray($data['skills'] ?? []);
        @endphp

        @if (!empty($skillsArr))
            <section>
                <h2
                    class="text-xs font-bold uppercase tracking-[0.25em] text-slate-900 border-b border-slate-200 mb-4 pb-1">
                    Skills
                </h2>

                <div class="flex flex-wrap gap-x-6 gap-y-2">
                    @foreach ($skillsArr as $skill)
                        @php $skill = trim((string) $skill); @endphp
                        @if ($skill !== '')
                            <span class="text-[13px] text-slate-700 flex items-center">
                                <span class="text-slate-300 mr-2 text-[10px]">■</span> {{ $skill }}
                            </span>
                        @endif
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ================= LANGUAGES ================= --}}
        @php
            $languagesArr = $toArray($data['languages'] ?? []);
        @endphp

        @if (!empty($languagesArr))
            <section class="mt-10">
                <h2
                    class="text-xs font-bold uppercase tracking-[0.25em] text-slate-900 border-b border-slate-200 mb-4 pb-1">
                    Languages
                </h2>

                <div class="flex flex-wrap gap-x-6 gap-y-2">
                    @foreach ($languagesArr as $lang)
                        @php $lang = trim((string) $lang); @endphp
                        @if ($lang !== '')
                            <span class="text-[13px] text-slate-700 flex items-center">
                                <span class="text-slate-300 mr-2 text-[10px]">■</span> {{ $lang }}
                            </span>
                        @endif
                    @endforeach
                </div>
            </section>
        @endif

    </div>
</body>

</html>
