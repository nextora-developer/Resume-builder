<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Resume</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css'])

    <style>
        /* PDF / print friendly tweaks */
        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
</head>

<body class="bg-white text-gray-900">
    <div class="max-w-[820px] mx-auto px-10 py-12">

        {{-- ================= HEADER ================= --}}
        <header class="border-b border-gray-300 pb-6 mb-8">
            <h1 class="text-3xl font-bold tracking-tight">
                {{ $data['name'] ?? 'Your Name' }}
            </h1>

            @if (!empty($data['title']))
                <p class="mt-1 text-sm font-medium text-gray-600">
                    {{ $data['title'] }}
                </p>
            @endif

            <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-600">
                @if (!empty($data['email']))
                    <span>{{ $data['email'] }}</span>
                @endif

                @if (!empty($data['phone']))
                    <span>{{ $data['phone'] }}</span>
                @endif

                @if (!empty($data['location']))
                    <span>{{ $data['location'] }}</span>
                @endif

                @if (!empty($data['website']))
                    <span>{{ $data['website'] }}</span>
                @endif
            </div>
        </header>

        {{-- ================= SUMMARY ================= --}}
        @if (!empty($data['summary']))
            <section class="mb-8">
                <h2 class="text-sm font-bold uppercase tracking-widest text-gray-700 mb-2">
                    Professional Summary
                </h2>
                <p class="text-sm leading-relaxed text-gray-800 whitespace-pre-line">
                    {{ $data['summary'] }}
                </p>
            </section>
        @endif

        {{-- ================= EXPERIENCE ================= --}}
        @if (!empty($data['experience']) && is_array($data['experience']))
            <section class="mb-8">
                <h2 class="text-sm font-bold uppercase tracking-widest text-gray-700 mb-4">
                    Experience
                </h2>

                <div class="space-y-6">
                    @foreach ($data['experience'] as $exp)
                        <div>
                            <div class="flex justify-between items-start gap-4">
                                <div>
                                    <div class="font-semibold text-sm">
                                        {{ $exp['role'] ?? '' }}
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{ $exp['company'] ?? '' }}
                                    </div>
                                </div>

                                @if (!empty($exp['period']))
                                    <div class="text-sm text-gray-500 whitespace-nowrap">
                                        {{ $exp['period'] }}
                                    </div>
                                @endif
                            </div>

                            @if (!empty($exp['description']))
                                <ul class="mt-2 list-disc list-inside text-sm text-gray-700 space-y-1">
                                    @foreach (explode("\n", $exp['description']) as $line)
                                        @if (trim($line) !== '')
                                            <li>{{ $line }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ================= EDUCATION ================= --}}
        @if (!empty($data['education']) && is_array($data['education']))
            <section class="mb-8">
                <h2 class="text-sm font-bold uppercase tracking-widest text-gray-700 mb-4">
                    Education
                </h2>

                <div class="space-y-4">
                    @foreach ($data['education'] as $edu)
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <div class="font-semibold text-sm">
                                    {{ $edu['school'] ?? '' }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $edu['degree'] ?? '' }}
                                </div>
                            </div>

                            @if (!empty($edu['period']))
                                <div class="text-sm text-gray-500 whitespace-nowrap">
                                    {{ $edu['period'] }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ================= SKILLS ================= --}}
        @if (!empty($data['skills']))
            <section class="mb-2">
                <h2 class="text-sm font-bold uppercase tracking-widest text-gray-700 mb-3">
                    Skills
                </h2>

                <div class="flex flex-wrap gap-2 text-sm">
                    @foreach (explode(',', $data['skills']) as $skill)
                        @if (trim($skill) !== '')
                            <span class="border border-gray-300 rounded px-2 py-1">
                                {{ trim($skill) }}
                            </span>
                        @endif
                    @endforeach
                </div>
            </section>
        @endif

    </div>
</body>

</html>
