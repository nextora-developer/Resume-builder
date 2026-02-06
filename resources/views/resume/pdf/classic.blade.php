<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Resume - {{ $data['name'] ?? 'Professional' }}</title>

    <style>
        @page {
            margin: 28px 36px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #0f172a;
            line-height: 1.45;
        }

        .header {
            border-bottom: 2px solid #0f172a;
            padding-bottom: 18px;
            margin-bottom: 22px;
        }

        .row {
            width: 100%;
        }

        .row:after {
            content: "";
            display: block;
            clear: both;
        }

        .avatar {
            float: left;
            width: 86px;
            margin-top: 15px;
        }

        .avatar img {
            width: 95px;
            height: 120px;
            object-fit: cover;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
        }

        .head-main {
            margin-left: 0;
        }

        .has-avatar .head-main {
            margin-left: 35px;
        }

        /* 86 + 18 gap */

        h1 {
            margin: 0;
            font-size: 28px;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        h1,
        .section-title {
            font-family: "DejaVu Serif", serif;
        }

        .position {
            margin-bottom: 6px;
            font-size: 15px;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: #64748b;
        }

        .meta {
            /* margin-top: 10px; */
            font-size: 11px;
            color: #475569;
        }

        .meta span {
            margin-right: 14px;
        }

        a {
            color: #0f172a;
            text-decoration: underline;
        }

        .section {
            margin-top: 30px;
        }

        .section-title {
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 6px;
            margin-bottom: 10px;
            color: #0f172a;
        }

        .item {
            margin-bottom: 14px;
        }

        .item-head {
            width: 100%;
            margin-bottom: 2px;
        }

        .item-title {
            float: left;
            font-weight: 700;
            font-size: 13px;
            width: 70%;
        }

        .item-period {
            float: right;
            text-align: right;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #64748b;
            width: 28%;
        }

        .item-head:after {
            content: "";
            display: block;
            clear: both;
        }

        .item-sub {
            font-size: 11px;
            color: #475569;
            margin-bottom: 6px;
        }

        .dot {
            color: #cbd5e1;
            margin: 0 8px;
        }

        ul.bullets {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        ul.bullets li {
            margin: 0 0 5px 0;
            padding-left: 14px;
            position: relative;
            color: #334155;
        }

        ul.bullets li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #94a3b8;
            /* font-size: 13px; */
        }

        .chips span {
            display: inline-block;
            margin-right: 16px;
            margin-bottom: 6px;
            color: #334155;
        }

        .chips span i {
            font-style: normal;
            font-size: 8px;
            line-height: 1;
            opacity: 0.6;
        }

        .chips i {
            color: #cbd5e1;
            margin-right: 6px;
            font-style: normal;
        }
    </style>
</head>

@php
    use Illuminate\Support\Facades\Storage;

    $links = $data['links'] ?? [];

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

    $avatarPath = $data['avatar_path'] ?? null;
    $avatarAbs = $avatarPath ? Storage::disk('public')->path($avatarPath) : null;
    $hasAvatar = $avatarAbs && is_file($avatarAbs);
@endphp

<body>

    <div class="header">
        <div class="row {{ $hasAvatar ? 'has-avatar' : '' }}">
            @if ($hasAvatar)
                <div class="avatar">
                    <img src="{{ $avatarAbs }}" alt="Profile photo">
                </div>
            @endif

            <div class="head-main">
                <h1>{{ $data['name'] ?? 'Your Name' }}</h1>

                @if (!empty($data['position']))
                    <div class="position">{{ $data['position'] }}</div>
                @endif

                <div class="meta">
                    @if (!empty($data['email']))
                        <span>{{ $data['email'] }}</span>
                    @endif
                    @if (!empty($data['phone']))
                        <span>{{ $data['phone'] }}</span>
                    @endif
                    @if (!empty($data['location']))
                        <span>{{ $data['location'] }}</span>
                    @endif
                </div>

                <div class="meta">
                    @if (!empty($links['website']))
                        <span><a style="margin-right: 20px;" href="{{ $links['website'] }}">Website</a></span>
                    @endif
                    @if (!empty($links['linkedin']))
                        <span><a style="margin-right: 20px;" href="{{ $links['linkedin'] }}">LinkedIn</a></span>
                    @endif
                    @if (!empty($links['github']))
                        <span><a href="{{ $links['github'] }}">GitHub</a></span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if (!empty($data['summary']))
        <div class="section">
            <div class="section-title">Professional Profile</div>
            <div style="color:#334155;">{{ $data['summary'] }}</div>
        </div>
    @endif

    @if (!empty($data['experience']) && is_array($data['experience']))
        <div class="section">
            <div class="section-title">Professional Experience</div>

            @foreach ($data['experience'] as $exp)
                @php
                    $role = $exp['role'] ?? '';
                    $company = $exp['company'] ?? '';
                    $loc = $exp['location'] ?? '';
                    $period =
                        $exp['period'] ??
                        $periodFrom($exp['start'] ?? null, $exp['end'] ?? null, !empty($exp['current']));
                    $highlights = $exp['highlights'] ?? null;
                    $desc = $exp['description'] ?? null;

                    if (is_string($highlights)) {
                        $highlights = array_values(array_filter(array_map('trim', explode("\n", $highlights))));
                    }
                    if (!is_array($highlights)) {
                        $highlights = [];
                    }
                    if (empty($highlights) && is_string($desc)) {
                        $highlights = array_values(array_filter(array_map('trim', explode("\n", $desc))));
                    }
                @endphp

                @if (!empty($role) || !empty($company) || !empty($highlights))
                    <div class="item">
                        <div class="item-head">
                            <div class="item-title">{{ $role ?: 'Role' }}</div>
                            @if (!empty($period))
                                <div class="item-period">{{ $period }}</div>
                            @endif
                        </div>

                        <div class="item-sub">
                            {{ $company }}
                            @if (!empty($loc))
                                <span class="dot">•</span>{{ $loc }}
                            @endif
                        </div>

                        @if (!empty($highlights))
                            <ul class="bullets">
                                @foreach ($highlights as $line)
                                    @php $line = trim((string) $line); @endphp
                                    @if ($line !== '')
                                        <li>{{ trim($line, "• \t") }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    @if (!empty($data['education']) && is_array($data['education']))
        <div class="section">
            <div class="section-title">Education</div>

            @foreach ($data['education'] as $edu)
                @php
                    $school = $edu['school'] ?? '';
                    $degree = $edu['degree'] ?? '';
                    $field = $edu['field'] ?? '';
                    $notes = $edu['notes'] ?? '';
                    $period = $edu['period'] ?? $periodFrom($edu['start'] ?? null, $edu['end'] ?? null, false);
                @endphp

                @if (!empty($school) || !empty($degree) || !empty($field) || !empty($notes))
                    <div class="item">
                        <div class="item-head">
                            <div class="item-title">
                                {{ $school }}
                                @if (!empty($degree) || !empty($field))
                                    <span style="color:#cbd5e1;"> | </span>
                                    <span style="font-weight:500;color:#334155;">
                                        {{ $degree }}
                                        @if (!empty($field))
                                            <span style="color:#94a3b8;"> — </span>{{ $field }}
                                        @endif
                                    </span>
                                @endif
                            </div>
                            @if (!empty($period))
                                <div class="item-period">{{ $period }}</div>
                            @endif
                        </div>

                        @if (!empty($notes))
                            <div style="color:#334155;margin-top:4px;">{{ $notes }}</div>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    @if (!empty($data['projects']) && is_array($data['projects']))
        <div class="section">
            <div class="section-title">Projects</div>

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

                    $pdfPath = $prj['pdf_path'] ?? ($prj['pdf'] ?? ($prj['pdf_url'] ?? null));
                @endphp

                @if (!empty($name) || !empty($role) || !empty($link) || !empty($highlights))
                    <div class="item">
                        <div class="item-head">
                            <div class="item-title">
                                {{ $name ?: 'Project' }}
                                @if (!empty($role))
                                    <span style="font-weight:500;color:#475569;"> ({{ $role }})</span>
                                @endif

                                <div style="margin-top:3px;font-size:11px;color:#475569;">
                                    @if (!empty($link))
                                        <span><a href="{{ $link }}">{{ $link }}</a></span>
                                    @endif
                                    @if (!empty($pdfPath))
                                        <span>
                                            <a href="{{ url('storage/' . $pdfPath) }}">View Project</a>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if (!empty($period))
                                <div class="item-period">{{ $period }}</div>
                            @endif
                        </div>

                        @if (!empty($highlights))
                            <ul class="bullets">
                                @foreach ($highlights as $line)
                                    @php $line = trim((string) $line); @endphp
                                    @if ($line !== '')
                                        <li>{{ trim($line, "• \t") }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    @endif

    @php $skillsArr = $toArray($data['skills'] ?? []); @endphp
    @if (!empty($skillsArr))
        <div class="section">
            <div class="section-title">Skills</div>
            <div class="chips">
                @foreach ($skillsArr as $skill)
                    @php $skill = trim((string) $skill); @endphp
                    @if ($skill !== '')
                        <span><i>■</i>{{ $skill }}</span>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    @php $languagesArr = $toArray($data['languages'] ?? []); @endphp
    @if (!empty($languagesArr))
        <div class="section">
            <div class="section-title">Languages</div>
            <div class="chips">
                @foreach ($languagesArr as $lang)
                    @php $lang = trim((string) $lang); @endphp
                    @if ($lang !== '')
                        <span><i>■</i>{{ $lang }}</span>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

</body>

</html>
