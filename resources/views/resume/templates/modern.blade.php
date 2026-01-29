<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Resume</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css'])

    <style>
        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    </style>
</head>

<body class="bg-white text-gray-900">
    <div class="max-w-[980px] mx-auto px-10 py-12">

        {{-- Top Header --}}
        <header class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-bold tracking-tight">
                        {{ $data['name'] ?? 'Your Name' }}
                    </h1>

                    @if (!empty($data['title']))
                        <p class="mt-1 text-sm font-semibold text-gray-600">
                            {{ $data['title'] }}
                        </p>
                    @endif
                </div>

                <div class="text-sm text-gray-600 flex flex-wrap gap-x-4 gap-y-1">
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
            </div>

            <div class="mt-5 h-px bg-gradient-to-r from-gray-900/30 via-gray-900/10 to-transparent"></div>
        </header>

        <div class="grid grid-cols-12 gap-8">
            {{-- Left Column --}}
            <aside class="col-span-12 md:col-span-4">
                <div class="rounded-3xl border border-gray-200 bg-gray-50 p-6">
                    {{-- Summary --}}
                    @if (!empty($data['summary']))
                        <div class="mb-6">
                            <h2 class="text-xs font-bold uppercase tracking-widest text-gray-600">
                                Summary
                            </h2>
                            <p class="mt-3 text-sm leading-relaxed text-gray-800 whitespace-pre-line">
                                {{ $data['summary'] }}
                            </p>
                        </div>
                    @endif

                    {{-- Skills --}}
                    @if (!empty($data['skills']))
                        <div>
                            <h2 class="text-xs font-bold uppercase tracking-widest text-gray-600">
                                Skills
                            </h2>
                            <div class="mt-3 flex flex-wrap gap-2 text-sm">
                                @foreach (explode(',', $data['skills']) as $skill)
                                    @if (trim($skill) !== '')
                                        <span class="rounded-full border border-gray-300 bg-white px-3 py-1">
                                            {{ trim($skill) }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Education --}}
                @if (!empty($data['education']) && is_array($data['education']))
                    <div class="mt-6">
                        <h2 class="text-xs font-bold uppercase tracking-widest text-gray-600 mb-3">
                            Education
                        </h2>
                        <div class="space-y-4">
                            @foreach ($data['education'] as $edu)
                                <div class="rounded-2xl border border-gray-200 p-4">
                                    <div class="text-sm font-semibold text-gray-900">
                                        {{ $edu['school'] ?? '' }}
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{ $edu['degree'] ?? '' }}
                                    </div>
                                    @if (!empty($edu['period']))
                                        <div class="mt-1 text-xs text-gray-500">
                                            {{ $edu['period'] }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>

            {{-- Right Column --}}
            <main class="col-span-12 md:col-span-8">
                {{-- Experience --}}
                @if (!empty($data['experience']) && is_array($data['experience']))
                    <section>
                        <h2 class="text-xs font-bold uppercase tracking-widest text-gray-600 mb-4">
                            Experience
                        </h2>

                        <div class="space-y-6">
                            @foreach ($data['experience'] as $exp)
                                <div class="rounded-3xl border border-gray-200 p-6">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">
                                                {{ $exp['role'] ?? '' }}
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                {{ $exp['company'] ?? '' }}
                                            </div>
                                        </div>

                                        @if (!empty($exp['period']))
                                            <div class="text-xs text-gray-500 whitespace-nowrap mt-0.5">
                                                {{ $exp['period'] }}
                                            </div>
                                        @endif
                                    </div>

                                    @if (!empty($exp['description']))
                                        <ul class="mt-3 list-disc list-inside text-sm text-gray-700 space-y-1">
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
            </main>
        </div>

    </div>
</body>

</html>
