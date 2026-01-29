<x-app-layout>
    <div class="relative overflow-hidden">
        {{-- Background --}}
        <div class="absolute inset-0 -z-10">
            <div class="absolute inset-0 bg-gradient-to-b from-white via-gray-50 to-white"></div>
            <div
                class="absolute -top-24 left-1/2 -translate-x-1/2 h-[480px] w-[900px] rounded-full bg-gray-900/5 blur-3xl">
            </div>
            <div
                class="absolute inset-0 opacity-[0.07] [background-image:linear-gradient(to_right,#000_1px,transparent_1px),linear-gradient(to_bottom,#000_1px,transparent_1px)] [background-size:48px_48px]">
            </div>
        </div>

        {{-- Hero --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-16">
            <div class="grid lg:grid-cols-2 gap-10 lg:gap-14 items-center">

                {{-- Left --}}
                <div>
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white/70 px-3 py-1 text-xs font-semibold text-gray-600">
                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                        Fast • Clean • PDF Export
                    </div>

                    <h1 class="mt-5 text-4xl sm:text-5xl font-bold tracking-tight leading-[1.05] text-gray-900">
                        Build a professional resume
                        <span class="block text-gray-500">in minutes.</span>
                    </h1>

                    <p class="mt-6 text-lg text-gray-600 max-w-xl">
                        Pick a template, fill your details, and export a polished PDF resume — simple, modern, and easy
                        to maintain.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('resume.create') }}"
                            class="inline-flex items-center justify-center rounded-2xl bg-gray-900 px-6 py-3
                                  text-sm sm:text-base font-semibold text-white hover:bg-black transition shadow-sm">
                            Start building
                        </a>

                        <a href="{{ route('templates.index') }}"
                            class="inline-flex items-center justify-center rounded-2xl border border-gray-300 bg-white/70 px-6 py-3
                                  text-sm sm:text-base font-semibold text-gray-900 hover:bg-white transition">
                            View templates
                        </a>

                        @guest
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center justify-center rounded-2xl border border-gray-200 px-6 py-3
                                      text-sm sm:text-base font-semibold text-gray-700 hover:bg-gray-100 transition">
                                Create account
                            </a>
                        @endguest
                    </div>

                    {{-- Mini trust row --}}
                    <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-gray-500">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-gray-900/20"></span>
                            Autosave drafts
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-gray-900/20"></span>
                            Modern templates
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-gray-900/20"></span>
                            PDF ready
                        </div>
                    </div>
                </div>

                {{-- Right: Preview card --}}
                <div class="relative">
                    <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-gray-900/5 blur-2xl"></div>

                    <div
                        class="rounded-3xl border border-gray-200 bg-white/80 backdrop-blur shadow-[0_30px_80px_-30px_rgba(0,0,0,0.25)] overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                            <div class="text-sm font-semibold text-gray-900">Resume Preview</div>
                            <div class="flex items-center gap-1.5">
                                <span class="h-2 w-2 rounded-full bg-red-400/80"></span>
                                <span class="h-2 w-2 rounded-full bg-yellow-400/80"></span>
                                <span class="h-2 w-2 rounded-full bg-green-400/80"></span>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="h-5 w-40 bg-gray-200 rounded"></div>
                            <div class="mt-2 h-4 w-56 bg-gray-100 rounded"></div>

                            <div class="mt-6 space-y-3">
                                <div class="h-3 w-full bg-gray-100 rounded"></div>
                                <div class="h-3 w-[92%] bg-gray-100 rounded"></div>
                                <div class="h-3 w-[80%] bg-gray-100 rounded"></div>
                            </div>

                            <div class="mt-8 grid grid-cols-2 gap-4">
                                <div class="rounded-2xl border border-gray-200 p-4">
                                    <div class="h-3 w-20 bg-gray-200 rounded"></div>
                                    <div class="mt-3 space-y-2">
                                        <div class="h-2.5 w-full bg-gray-100 rounded"></div>
                                        <div class="h-2.5 w-[85%] bg-gray-100 rounded"></div>
                                    </div>
                                </div>

                                <div class="rounded-2xl border border-gray-200 p-4">
                                    <div class="h-3 w-24 bg-gray-200 rounded"></div>
                                    <div class="mt-3 space-y-2">
                                        <div class="h-2.5 w-full bg-gray-100 rounded"></div>
                                        <div class="h-2.5 w-[78%] bg-gray-100 rounded"></div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="mt-6 rounded-2xl bg-gray-900/5 border border-gray-200 px-4 py-3 text-xs text-gray-600">
                                Tip: Start as guest, save & download when you’re ready.
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- Steps / Features --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
            <div class="grid md:grid-cols-3 gap-6">
                <div class="rounded-3xl border border-gray-200 bg-white/70 p-6 shadow-sm">
                    <div class="text-xs font-bold tracking-widest text-gray-400">STEP 01</div>
                    <div class="mt-2 text-lg font-semibold text-gray-900">Choose a template</div>
                    <p class="mt-2 text-sm text-gray-600">Pick from clean layouts designed for ATS-friendly resumes.</p>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white/70 p-6 shadow-sm">
                    <div class="text-xs font-bold tracking-widest text-gray-400">STEP 02</div>
                    <div class="mt-2 text-lg font-semibold text-gray-900">Fill your details</div>
                    <p class="mt-2 text-sm text-gray-600">Add experience, education, skills, projects, and links.</p>
                </div>

                <div class="rounded-3xl border border-gray-200 bg-white/70 p-6 shadow-sm">
                    <div class="text-xs font-bold tracking-widest text-gray-400">STEP 03</div>
                    <div class="mt-2 text-lg font-semibold text-gray-900">Export as PDF</div>
                    <p class="mt-2 text-sm text-gray-600">Download a polished PDF ready to email or upload.</p>
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <div
                class="rounded-[32px] border border-gray-200 bg-gray-900 text-white p-8 sm:p-10 overflow-hidden relative">
                <div class="absolute -top-20 -right-24 h-64 w-64 rounded-full bg-white/10 blur-2xl"></div>

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-bold tracking-tight">Ready to build yours?</h2>
                        <p class="mt-2 text-white/70 text-sm sm:text-base max-w-2xl">
                            Start now. You can edit as guest — login only when you want to save & download.
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('resume.create') }}"
                            class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3
                                  text-sm font-semibold text-gray-900 hover:bg-white/90 transition">
                            Start building
                        </a>

                        <a href="{{ route('templates.index') }}"
                            class="inline-flex items-center justify-center rounded-2xl border border-white/20 px-6 py-3
                                  text-sm font-semibold text-white hover:bg-white/10 transition">
                            Browse templates
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
