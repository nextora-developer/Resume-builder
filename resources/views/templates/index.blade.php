<x-app-layout>
    <div class="max-w-7xl mx-auto py-20 px-6">

        {{-- Section Header --}}
        <header class="mb-20 text-center">
            <span
                class="text-blue-600 font-bold tracking-widest text-xs uppercase bg-blue-50 px-3 py-1 rounded-full">Templates</span>
            <h1 class="mt-4 text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900">
                Designed for <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-500">Success.</span>
            </h1>
            <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                Each layout is meticulously crafted for readability and ATS optimization. Choose your foundation and
                build your future.
            </p>
        </header>

        <div class="space-y-32">
            @foreach ($templates as $index => $t)
                <div
                    class="flex flex-col {{ $index % 2 != 0 ? 'md:flex-row-reverse' : 'md:flex-row' }} gap-12 lg:gap-20 items-center">

                    {{-- Mockup Display --}}
                    <div class="w-full md:w-1/2 group relative">
                        <div
                            class="absolute -inset-4 bg-gradient-to-tr from-gray-200 to-blue-50 rounded-[2rem] blur-2xl opacity-50 group-hover:opacity-100 transition duration-500">
                        </div>

                        <div
                            class="relative rounded-2xl border border-gray-200 bg-white overflow-hidden shadow-2xl transition-transform duration-500 group-hover:-translate-y-2">
                            <div class="flex items-center gap-1.5 px-4 py-3 border-b border-gray-100 bg-gray-50/50">
                                <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-400"></div>
                                <div class="ml-4 h-4 w-32 bg-gray-200 rounded-full"></div>
                            </div>

                            <div class="relative bg-gray-50 aspect-[3/4] overflow-hidden">
                                <iframe src="{{ route('templates.preview', $t['slug']) }}"
                                    class="w-full h-full scale-[0.75] origin-top-left pointer-events-none"
                                    style="width: 133.33%; height: 133.33%;"></iframe>

                                <div
                                    class="absolute inset-0 bg-gray-900/0 group-hover:bg-gray-900/5 transition-colors duration-300">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Text Content --}}
                    <div class="w-full md:w-1/2">
                        <div
                            class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-bold uppercase mb-4">
                            Template 0{{ $index + 1 }}
                        </div>

                        <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                            {{ $t['name'] }}
                        </h2>

                        <div class="mt-4 space-y-4">
                            <p class="text-lg text-gray-600 leading-relaxed">
                                @if ($t['slug'] === 'classic')
                                    The gold standard for professional applications. Clean lines and a traditional
                                    structure ensure your experience remains the focal point for recruiters.
                                @elseif($t['slug'] === 'modern')
                                    A contemporary approach for the modern professional. Utilizes whitespace and a
                                    subtle sidebar to highlight key technical competencies.
                                @elseif($t['slug'] === 'tech')
                                    Optimized for developers and designers. Features a high-density layout that
                                    emphasizes GitHub projects, tech stacks, and career growth.
                                @endif
                            </p>

                            <ul class="space-y-2 py-4">
                                <li class="flex items-center gap-3 text-sm text-gray-600">
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    100% ATS-Friendly Structure
                                </li>
                                <li class="flex items-center gap-3 text-sm text-gray-600">
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Export to high-quality PDF
                                </li>
                            </ul>
                        </div>

                        <div class="mt-8 flex flex-wrap gap-4">
                            <a href="{{ route('resume.create', ['template' => $t['slug']]) }}"
                                class="inline-flex items-center rounded-xl bg-gray-900 px-8 py-4 text-sm font-bold text-white hover:bg-blue-600 transition-all duration-300 shadow-xl shadow-gray-200">
                                Start with {{ $t['name'] }}
                            </a>

                            <a href="{{ route('templates.preview', $t['slug']) }}" target="_blank"
                                class="inline-flex items-center rounded-xl border border-gray-200 bg-white px-8 py-4 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-all duration-200">
                                Live Preview
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        {{-- Bottom CTA --}}
        <div class="mt-32 p-12 rounded-[2.5rem] bg-gray-900 text-center overflow-hidden relative">
            <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-white">Not sure which one to choose?</h2>
            <p class="text-gray-400 mt-2">You can switch templates at any time without losing your data.</p>
            <div class="mt-8">
                <a href="{{ route('register') }}"
                    class="bg-blue-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-blue-500 transition">Get
                    Started for Free</a>
            </div>
        </div>

    </div>
</x-app-layout>
