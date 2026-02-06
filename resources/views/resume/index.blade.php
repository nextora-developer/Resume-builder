<x-app-layout>
    <div class="max-w-7xl mx-auto py-12 px-6">

        {{-- Header Section --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 mb-12">
            <div>
                <nav class="flex items-center gap-2 text-xs font-bold tracking-widest text-blue-600 uppercase mb-3">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span>Workspace</span>
                </nav>
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900">My Resumes</h1>
                <p class="mt-2 text-gray-500 font-medium">Manage, edit, and track your professional documents.</p>
            </div>

            <div class="flex items-center gap-4">
                {{-- Quick Stats Desktop --}}
                <div
                    class="hidden md:flex items-center divide-x divide-gray-200 rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                    <div class="px-5 py-3 text-center">
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-tighter">Total</span>
                        <span class="text-lg font-bold text-gray-900">{{ $resumes->count() }}</span>
                    </div>
                    <div class="px-5 py-3 text-center bg-gray-50/50">
                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-tighter">Updated</span>
                        <span
                            class="text-sm font-bold text-gray-900">{{ $resumes->first()?->updated_at->format('M d') ?? 'N/A' }}</span>
                    </div>
                </div>

                <a href="{{ route('resume.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gray-900 px-6 py-4 text-sm font-bold text-white hover:bg-blue-600 transition-all duration-300 shadow-xl shadow-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create New Resume
                </a>
            </div>
        </div>

        @if ($resumes->isEmpty())
            {{-- Enhanced Empty State --}}
            <div
                class="relative group rounded-[3rem] border-2 border-dashed border-gray-200 bg-white p-20 text-center transition-colors hover:border-blue-300">
                <div
                    class="mx-auto h-20 w-20 rounded-3xl bg-blue-50 flex items-center justify-center mb-6 transition-transform group-hover:scale-110 duration-300">
                    <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Start your journey</h2>
                <p class="mt-3 text-gray-500 max-w-sm mx-auto text-lg leading-relaxed">
                    You haven't created any resumes yet. Pick a template and land that dream job.
                </p>
                <div class="mt-10">
                    <a href="{{ route('resume.create') }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-gray-900 px-8 py-4 text-base font-bold text-white hover:bg-black transition-all shadow-lg">
                        Create Your First Resume
                    </a>
                </div>
            </div>
        @else
            {{-- Professional Grid --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($resumes as $resume)
                    @php
                        $title = $resume->title ?: 'Untitled Resume';
                        $template = strtolower($resume->template ?? 'classic');
                        $badge = match ($template) {
                            'modern' => 'text-blue-600 bg-blue-50',
                            'tech' => 'text-emerald-600 bg-emerald-50',
                            default => 'text-gray-600 bg-gray-100',
                        };
                        $desc = $resume->data['summary'] ?? 'No summary provided for this draft.';
                    @endphp

                    <div
                        class="group relative flex flex-col rounded-[2rem] border border-gray-200 bg-white shadow-sm hover:shadow-2xl hover:shadow-gray-200/50 transition-all duration-300 overflow-hidden">

                        {{-- Top Action Bar (Context Menu Style) --}}
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                            <div
                                class="flex gap-1 p-1 bg-white/80 backdrop-blur rounded-xl border border-gray-100 shadow-sm">
                                <form method="POST" action="{{ route('resume.destroy', $resume) }}"
                                    onsubmit="return confirm('Delete this resume?');">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-gray-400 hover:text-red-500 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="p-8 flex-1">
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="h-12 w-12 rounded-2xl bg-gray-900 flex items-center justify-center text-white shadow-lg shadow-gray-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <span
                                        class="inline-block px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-widest {{ $badge }} mb-1">
                                        {{ $template }}
                                    </span>
                                    <h3 class="font-bold text-gray-900 leading-tight truncate max-w-[180px]">
                                        {{ $title }}
                                    </h3>
                                </div>
                            </div>

                            <p class="text-sm text-gray-500 leading-relaxed line-clamp-3">
                                {{ \Illuminate\Support\Str::limit($desc, 100) }}
                            </p>
                        </div>

                        {{-- Footer Info --}}
                        <div
                            class="px-8 py-4 bg-gray-50/50 border-t border-gray-100 flex items-center justify-between text-xs font-medium text-gray-400">
                            <span>Edited {{ $resume->updated_at->diffForHumans() }}</span>
                            <span
                                class="font-mono bg-white px-2 py-1 rounded border border-gray-100">#{{ substr($resume->uuid, 0, 8) }}</span>
                        </div>

                        {{-- Hover Actions (Slides up) --}}
                        <div class="p-6 bg-white border-t border-gray-100 grid grid-cols-3 gap-2">
                            <a href="{{ route('resume.edit', $resume) }}"
                                class="flex flex-col items-center gap-1 p-2 rounded-xl hover:bg-gray-50 transition group/btn">
                                <div
                                    class="p-2 rounded-lg bg-gray-100 text-gray-600 group-hover/btn:bg-blue-600 group-hover/btn:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </div>
                                <span class="text-[10px] font-bold uppercase text-gray-400">Edit</span>
                            </a>
                            <a href="{{ route('resume.preview', $resume) }}"
                                class="flex flex-col items-center gap-1 p-2 rounded-xl hover:bg-gray-50 transition group/btn">
                                <div
                                    class="p-2 rounded-lg bg-gray-100 text-gray-600 group-hover/btn:bg-gray-900 group-hover/btn:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </div>
                                <span class="text-[10px] font-bold uppercase text-gray-400">View</span>
                            </a>
                            <a href="{{ route('resume.download', $resume) }}" target="_blank"
                                class="flex flex-col items-center gap-1 p-2 rounded-xl hover:bg-gray-50 transition group/btn">
                                <div
                                    class="p-2 rounded-lg bg-gray-100 text-gray-600 group-hover/btn:bg-emerald-600 group-hover/btn:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </div>
                                <span class="text-[10px] font-bold uppercase text-gray-400">PDF</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
