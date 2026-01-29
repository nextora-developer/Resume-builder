<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-6">

        {{-- Top Header --}}
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            <div>
                <div class="text-xl font-bold tracking-widest text-gray-400 uppercase">
                    Resume Editor
                </div>

                <div class="mt-2 flex flex-wrap items-center gap-2 text-sm text-gray-500">
                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset ring-gray-200 bg-white">
                        Draft ID: <span class="ml-1 font-mono text-gray-700">{{ $resume->uuid }}</span>
                    </span>

                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset ring-gray-200 bg-white">
                        Template: <span class="ml-1 text-gray-700">{{ ucfirst($resume->template) }}</span>
                    </span>

                    <span class="text-xs text-gray-400">
                        Updated {{ $resume->updated_at?->diffForHumans() }}
                    </span>
                </div>
            </div>

            {{-- Right actions --}}
            <div class="flex flex-wrap items-center gap-2">
                @auth
                    @if (is_null($resume->user_id))
                        <form method="POST" action="{{ route('resume.claim', $resume) }}">
                            @csrf
                            <button
                                class="inline-flex items-center justify-center h-10 px-4 rounded-xl
                                       bg-gray-900 text-white text-sm font-semibold
                                       hover:bg-black transition shadow-sm">
                                Save to my account
                            </button>
                        </form>
                    @else
                        <a href="{{ route('resumes.index') }}"
                            class="inline-flex items-center justify-center h-10 px-4 rounded-xl
                                  border border-gray-200 bg-white text-sm font-semibold text-gray-900
                                  hover:bg-gray-50 transition">
                            Back to My Resumes
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center justify-center h-10 px-4 rounded-xl
                              bg-gray-900 text-white text-sm font-semibold
                              hover:bg-black transition shadow-sm">
                        Login to save & download
                    </a>
                @endauth
            </div>
        </div>

        {{-- Status Banner --}}
        @if (session('status'))
            <div class="mt-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif

        {{-- Main Card --}}
        <div class="mt-8 grid lg:grid-cols-5 gap-6">

            {{-- Left: Form --}}
            <div class="lg:col-span-3">
                <form method="POST" action="{{ route('resume.update', $resume) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Basics</h2>
                                <p class="text-sm text-gray-500">Core details used in all templates.</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            {{-- Title --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1">Resume title</label>
                                <input name="title" value="{{ old('title', $resume->title) }}"
                                    placeholder="e.g. Product Manager Resume"
                                    class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10" />
                                <p class="mt-1 text-xs text-gray-500">This title is only for you (shown in My Resumes).
                                </p>
                            </div>

                            {{-- Template --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1">Template</label>
                                <select name="template"
                                    class="w-full rounded-2xl border-gray-200 bg-white focus:border-gray-900 focus:ring-gray-900/10">
                                    @foreach (['classic', 'modern', 'tech'] as $t)
                                        <option value="{{ $t }}" @selected($resume->template === $t)>
                                            {{ ucfirst($t) }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">You can switch anytime — your content stays.</p>
                            </div>

                            {{-- Name --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1">Full name</label>
                                <input name="data[name]" value="{{ old('data.name', $resume->data['name'] ?? '') }}"
                                    placeholder="e.g. John Tan"
                                    class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10" />
                            </div>

                            {{-- Summary --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1">Summary</label>
                                <textarea name="data[summary]" rows="6" placeholder="Write 3–5 lines about your strengths, results, and focus..."
                                    class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10">{{ old('data.summary', $resume->data['summary'] ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-wrap items-center gap-3">
                        <button
                            class="inline-flex items-center justify-center h-11 px-6 rounded-2xl
                                   bg-gray-900 text-white text-sm font-semibold
                                   hover:bg-black transition shadow-sm">
                            Save
                        </button>

                        <a href="{{ route('resume.preview', $resume) }}"
                            class="inline-flex items-center justify-center h-11 px-6 rounded-2xl
                                  border border-gray-200 bg-white text-sm font-semibold text-gray-900
                                  hover:bg-gray-50 transition">
                            Preview
                        </a>

                        @auth
                            @if (!is_null($resume->user_id) && $resume->user_id === auth()->id())
                                <a href="{{ route('resume.download', $resume) }}"
                                    class="inline-flex items-center justify-center h-11 px-6 rounded-2xl
                                          border border-gray-200 bg-white text-sm font-semibold text-gray-900
                                          hover:bg-gray-50 transition">
                                    Download PDF
                                </a>
                            @endif
                        @endauth
                    </div>
                </form>
            </div>

            {{-- Right: Tips / Next steps --}}
            <aside class="lg:col-span-2">
                <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm sticky top-24">
                    <h3 class="text-sm font-bold tracking-widest text-gray-400 uppercase">Next</h3>
                    <div class="mt-3 space-y-3 text-sm text-gray-700">
                        <div class="rounded-2xl border border-gray-200 p-4">
                            <div class="font-semibold text-gray-900">Add more sections</div>
                            <p class="mt-1 text-gray-600">
                                Next we’ll add Experience, Education and Skills with repeatable blocks.
                            </p>
                        </div>

                        <div class="rounded-2xl border border-gray-200 p-4">
                            <div class="font-semibold text-gray-900">Switch templates</div>
                            <p class="mt-1 text-gray-600">
                                Try Classic for ATS, Modern for general, Tech for developer.
                            </p>
                        </div>

                        <div class="rounded-2xl border border-gray-200 p-4">
                            <div class="font-semibold text-gray-900">Save to account</div>
                            <p class="mt-1 text-gray-600">
                                Claim your draft to enable PDF download.
                            </p>
                        </div>
                    </div>
                </div>
            </aside>

        </div>
    </div>
</x-app-layout>
