<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-6">

        {{-- Top Header --}}
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

            {{-- Left: Title --}}
            <div class="text-xl font-bold tracking-widest text-gray-400 uppercase">
                Resume Editor
            </div>

            @php
                $draftUuid = $resume->uuid ?? (string) \Illuminate\Support\Str::uuid();
            @endphp

            {{-- Right: Badges --}}
            <div class="flex flex-wrap items-center justify-start md:justify-end gap-2 text-sm text-gray-500">


                <span
                    class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold
                   ring-1 ring-inset ring-gray-200 bg-white">
                    Template:
                    <span class="ml-1 text-gray-700">
                        {{ ucfirst($resume->template ?? 'classic') }}
                    </span>
                </span>

                @if ($resume->exists)
                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold
                       ring-1 ring-inset ring-gray-200 bg-white">
                        Draft ID:
                        <span class="ml-1 font-mono text-gray-700">{{ $resume->uuid }}</span>
                    </span>

                    <span class="text-xs text-gray-400">
                        Updated {{ $resume->updated_at->diffForHumans() }}
                    </span>
                @else
                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold
                       ring-1 ring-inset ring-amber-300 bg-amber-50 text-amber-700">
                        Not saved yet
                    </span>
                @endif
            </div>
        </div>


        {{-- Status Banner --}}
        @if (session('status'))
            <div class="mt-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('status') }}
            </div>
        @endif

        {{-- Main Layout --}}
        <div class="mt-8 grid lg:grid-cols-5 gap-6">

            {{-- Left: Form --}}
            <div class="lg:col-span-4">
                <form id="resume-form" method="POST" enctype="multipart/form-data"
                    action="{{ $resume->exists ? route('resume.update', $resume) : route('resume.store') }}"
                    class="space-y-6">
                    @csrf

                    <input type="hidden" name="uuid" value="{{ old('uuid', $draftUuid) }}">

                    @if ($resume->exists)
                        @method('PUT')
                    @endif

                    <div class="first:mt-0 rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900 mb-1">Basics</h2>
                        <p class="text-sm text-gray-500 mb-5">
                            Core details used in all templates.
                        </p>

                        <div class="space-y-4">
                            {{-- Title --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1">Resume title</label>
                                <input name="title" value="{{ old('title', $resume->title) }}"
                                    placeholder="e.g. Product Manager Resume"
                                    class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10" />
                            </div>

                            {{-- Template --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1">Template</label>
                                <select name="template"
                                    class="w-full rounded-2xl border-gray-200 bg-white focus:border-gray-900 focus:ring-gray-900/10">
                                    @foreach (['classic', 'modern', 'tech'] as $t)
                                        <option value="{{ $t }}" @selected(($resume->template ?? 'classic') === $t)>
                                            {{ ucfirst($t) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @php
                                $avatarPath = old('data.avatar_path', $resume->data['avatar_path'] ?? null);
                            @endphp


                            {{-- Avatar Upload --}}
                            <div x-data="{
                                preview: '{{ $avatarPath ? asset('storage/' . $avatarPath) : '' }}'
                            }">
                                <label class="block text-sm font-semibold text-gray-800 mb-1">
                                    Profile photo (optional)
                                </label>

                                <div class="flex items-center gap-4">
                                    <div
                                        class="h-20 w-16 rounded-xl overflow-hidden border border-gray-200 bg-gray-50 shrink-0 flex items-center justify-center">

                                        <template x-if="preview">
                                            <img :src="preview" alt="Avatar"
                                                class="h-full w-full object-cover">
                                        </template>

                                        <template x-if="!preview">
                                            <div class="text-xs text-gray-400 text-center">
                                                No<br>photo
                                            </div>
                                        </template>
                                    </div>

                                    <div class="flex-1">
                                        <input type="file" name="avatar" accept="image/*"
                                            @change="
                    const file = $event.target.files[0];
                    if (file) preview = URL.createObjectURL(file);
                "
                                            class="block w-full text-sm text-gray-700
                       file:mr-3 file:py-2 file:px-4 file:rounded-xl
                       file:border-0 file:bg-gray-900 file:text-white file:text-sm file:font-semibold
                       hover:file:bg-black
                       border border-gray-200 rounded-2xl p-2" />

                                        <p class="mt-1 text-xs text-gray-500">
                                            JPG / PNG / WebP. Recommended: portrait (4:5 or 3:4).
                                        </p>

                                        {{-- keep existing path for backend --}}
                                        @if ($avatarPath)
                                            <input type="hidden" name="data[avatar_path]" value="{{ $avatarPath }}">
                                        @endif
                                    </div>
                                </div>
                            </div>


                            {{-- Name --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1">Full name</label>
                                <input name="data[name]" value="{{ old('data.name', $resume->data['name'] ?? '') }}"
                                    placeholder="e.g. John Tan"
                                    class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10" />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1">Position</label>
                                <input name="data[position]"
                                    value="{{ old('data.position', $resume->data['position'] ?? '') }}"
                                    placeholder="e.g. Frontend Developer"
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


                    <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900 mb-1">Contact</h2>
                        <p class="text-sm text-gray-500 mb-5">How recruiters can reach you.</p>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1">Email</label>
                                <input name="data[email]" value="{{ old('data.email', $resume->data['email'] ?? '') }}"
                                    placeholder="e.g. john@email.com"
                                    class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10" />
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1">Phone</label>
                                <input name="data[phone]" value="{{ old('data.phone', $resume->data['phone'] ?? '') }}"
                                    placeholder="e.g. +60 12-345 6789"
                                    class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10" />
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-800 mb-1">Location</label>
                                <input name="data[location]"
                                    value="{{ old('data.location', $resume->data['location'] ?? '') }}"
                                    placeholder="e.g. Kuala Lumpur, Malaysia"
                                    class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10" />
                            </div>
                        </div>
                    </div>


                    <div class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900 mb-1">Links</h2>
                        <p class="text-sm text-gray-500 mb-5">Portfolio, LinkedIn, GitHub, etc.</p>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1">LinkedIn</label>
                                <input name="data[links][linkedin]"
                                    value="{{ old('data.links.linkedin', $resume->data['links']['linkedin'] ?? '') }}"
                                    placeholder="https://linkedin.com/in/..."
                                    class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10" />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-800 mb-1">GitHub</label>
                                <input name="data[links][github]"
                                    value="{{ old('data.links.github', $resume->data['links']['github'] ?? '') }}"
                                    placeholder="https://github.com/..."
                                    class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10" />
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-semibold text-gray-800 mb-1">Portfolio /
                                    Website</label>
                                <input name="data[links][website]"
                                    value="{{ old('data.links.website', $resume->data['links']['website'] ?? '') }}"
                                    placeholder="https://..."
                                    class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10" />
                            </div>
                        </div>
                    </div>


                    <div x-data="{
                        items: {{ \Illuminate\Support\Js::from(old('data.experience', $resume->data['experience'] ?? [])) }},
                    
                        init() {
                            // ✅ normalize current to REAL boolean (avoid '0' being truthy)
                            this.items = (this.items || []).map(it => ({
                                ...it,
                                current: it.current === true || it.current === 1 || it.current === '1',
                                highlights: Array.isArray(it.highlights) ? it.highlights : [''],
                            }))
                        },
                    
                        add() {
                            this.items.push({
                                company: '',
                                role: '',
                                location: '',
                                start: '',
                                end: '',
                                current: false,
                                highlights: ['']
                            })
                        },
                        remove(i) { this.items.splice(i, 1) },
                        addBullet(i) {
                            if (!Array.isArray(this.items[i].highlights)) this.items[i].highlights = ['']
                            this.items[i].highlights.push('')
                        },
                        removeBullet(i, j) { this.items[i].highlights.splice(j, 1) }
                    }" class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">

                        <div class="flex items-center justify-between gap-3 mb-5">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Experience</h2>
                                <p class="text-sm text-gray-500">Your work history and impact.</p>
                            </div>

                            <button type="button" @click="add()"
                                class="h-10 px-4 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-black transition">
                                + Add
                            </button>
                        </div>

                        <template x-if="items.length === 0">
                            <div class="rounded-2xl border border-dashed border-gray-200 p-6 text-sm text-gray-500">
                                No experience added yet.
                            </div>
                        </template>

                        <div class="space-y-6" x-show="items.length > 0">
                            <template x-for="(it, i) in items" :key="i">
                                <div class="rounded-2xl border border-gray-200 p-5">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="font-semibold text-gray-900" x-text="it.role || 'New role'"></div>
                                        <button type="button" @click="remove(i)"
                                            class="text-sm text-red-600 hover:underline">
                                            Remove
                                        </button>
                                    </div>

                                    <div class="mt-4 grid sm:grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-gray-800 mb-1">Company</label>
                                            <input
                                                class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10"
                                                :name="`data[experience][${i}][company]`" x-model="it.company"
                                                placeholder="Company" />
                                        </div>

                                        <div>
                                            <label class="block text-sm font-semibold text-gray-800 mb-1">Role /
                                                Title</label>
                                            <input
                                                class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10"
                                                :name="`data[experience][${i}][role]`" x-model="it.role"
                                                placeholder="Job title" />
                                        </div>

                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-gray-800 mb-1">Location</label>
                                            <input
                                                class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10"
                                                :name="`data[experience][${i}][location]`" x-model="it.location"
                                                placeholder="City / Remote" />
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:items-end">
                                            <div class="sm:col-span-1">
                                                <label
                                                    class="block text-sm font-semibold text-gray-800 mb-1">Start</label>
                                                <input type="month"
                                                    class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10"
                                                    :name="`data[experience][${i}][start]`" x-model="it.start" />
                                            </div>

                                            <div class="sm:col-span-1">
                                                <label
                                                    class="block text-sm font-semibold text-gray-800 mb-1">End</label>

                                                {{-- ✅ use readonly (disabled won't submit) --}}
                                                <input type="month" :readonly="it.current"
                                                    class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10
                                       read-only:bg-gray-100 read-only:text-gray-500"
                                                    :name="`data[experience][${i}][end]`" x-model="it.end" />
                                            </div>

                                            <label
                                                class="sm:col-span-1 inline-flex items-center gap-2 text-sm text-gray-700 sm:justify-end sm:pb-2">
                                                <input type="checkbox" class="rounded border-gray-300"
                                                    :name="`data[experience][${i}][current]`"
                                                    x-model.boolean="it.current" value="1" />
                                                Current
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <div class="flex items-center justify-between">
                                            <label class="block text-sm font-semibold text-gray-800">Highlights</label>

                                            <button type="button" @click="addBullet(i)"
                                                class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl
                                   border border-gray-200 bg-white text-sm font-semibold text-gray-700
                                   hover:bg-gray-50 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 4v16m8-8H4" />
                                                </svg>
                                                Add bullet
                                            </button>
                                        </div>

                                        <div class="mt-3 space-y-2">
                                            <template x-for="(b, j) in it.highlights" :key="j">
                                                <div class="flex items-center gap-2">
                                                    <input
                                                        class="flex-1 rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10"
                                                        :name="`data[experience][${i}][highlights][${j}]`"
                                                        x-model="it.highlights[j]"
                                                        placeholder="e.g. Increased conversion by 18% by optimizing checkout flow." />

                                                    <button type="button" @click="removeBullet(i,j)"
                                                        class="h-9 px-3 rounded-xl
                                           border border-red-200 bg-red-50
                                           text-sm font-semibold text-red-700
                                           hover:bg-red-100 hover:border-red-300 transition">
                                                        Remove
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>


                    <div x-data="{
                        items: {{ \Illuminate\Support\Js::from(old('data.education', $resume->data['education'] ?? [])) }},
                        add() { this.items.push({ school: '', degree: '', field: '', start: '', end: '', notes: '' }) },
                        remove(i) { this.items.splice(i, 1) }
                    }" class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">

                        <div class="flex items-center justify-between gap-3 mb-5">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Education</h2>
                                <p class="text-sm text-gray-500">Degrees, diplomas, courses.</p>
                            </div>

                            <button type="button" @click="add()"
                                class="h-10 px-4 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-black transition">
                                + Add
                            </button>
                        </div>

                        <template x-if="items.length === 0">
                            <div class="rounded-2xl border border-dashed border-gray-200 p-6 text-sm text-gray-500">
                                No education added yet.
                            </div>
                        </template>

                        <div class="space-y-6" x-show="items.length > 0">
                            <template x-for="(it, i) in items" :key="i">
                                <div class="rounded-2xl border border-gray-200 p-5">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="font-semibold text-gray-900"
                                            x-text="it.school || 'New education'"></div>
                                        <button type="button" @click="remove(i)"
                                            class="text-sm text-red-600 hover:underline">
                                            Remove
                                        </button>
                                    </div>

                                    <div class="mt-4 grid sm:grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-gray-800 mb-1">School</label>
                                            <input
                                                class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10"
                                                :name="`data[education][${i}][school]`" x-model="it.school"
                                                placeholder="University / College" />
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-gray-800 mb-1">Degree</label>
                                            <input
                                                class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10"
                                                :name="`data[education][${i}][degree]`" x-model="it.degree"
                                                placeholder="e.g. Bachelor's" />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-800 mb-1">Field</label>
                                            <input
                                                class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10"
                                                :name="`data[education][${i}][field]`" x-model="it.field"
                                                placeholder="e.g. Computer Science" />
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label
                                                    class="block text-sm font-semibold text-gray-800 mb-1">Start</label>
                                                <input type="month"
                                                    class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10"
                                                    :name="`data[education][${i}][start]`" x-model="it.start" />
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-sm font-semibold text-gray-800 mb-1">End</label>
                                                <input type="month"
                                                    class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10"
                                                    :name="`data[education][${i}][end]`" x-model="it.end" />
                                            </div>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label class="block text-sm font-semibold text-gray-800 mb-1">Notes</label>
                                            <textarea rows="3" class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10"
                                                :name="`data[education][${i}][notes]`" x-model="it.notes"
                                                placeholder="Awards, GPA, activities, relevant courses..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div x-data="{
                        items: {{ \Illuminate\Support\Js::from(old('data.projects', $resume->data['projects'] ?? [])) }},
                        add() {
                            this.items.push({
                                name: '',
                                role: '',
                                link: '',
                                start: '',
                                end: '',
                                highlights: ['']
                            })
                        },
                        remove(i) { this.items.splice(i, 1) },
                        addBullet(i) { this.items[i].highlights.push('') },
                        removeBullet(i, j) { this.items[i].highlights.splice(j, 1) }
                    }" class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">

                        <div class="flex items-center justify-between gap-3 mb-5">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Projects</h2>
                                <p class="text-sm text-gray-500">
                                    Personal, academic, or professional projects.
                                </p>
                            </div>

                            <button type="button" @click="add()"
                                class="h-10 px-4 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-black transition">
                                + Add
                            </button>
                        </div>

                        <template x-if="items.length === 0">
                            <div class="rounded-2xl border border-dashed border-gray-200 p-6 text-sm text-gray-500">
                                No projects added yet.
                            </div>
                        </template>

                        <div class="space-y-6" x-show="items.length > 0">
                            <template x-for="(it, i) in items" :key="i">
                                <div class="rounded-2xl border border-gray-200 p-5">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="font-semibold text-gray-900" x-text="it.name || 'New project'">
                                        </div>
                                        <button type="button" @click="remove(i)"
                                            class="text-sm text-red-600 hover:underline">
                                            Remove
                                        </button>
                                    </div>

                                    <div class="mt-4 grid sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-800 mb-1">Project
                                                name</label>
                                            <input
                                                class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10"
                                                :name="`data[projects][${i}][name]`" x-model="it.name"
                                                placeholder="e.g. Resume Builder SaaS" />
                                        </div>

                                        <div>
                                            <label class="block text-sm font-semibold text-gray-800 mb-1">Role</label>
                                            <input
                                                class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10"
                                                :name="`data[projects][${i}][role]`" x-model="it.role"
                                                placeholder="e.g. Full-stack Developer" />
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label class="block text-sm font-semibold text-gray-800 mb-1">Project
                                                link</label>
                                            <input
                                                class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10"
                                                :name="`data[projects][${i}][link]`" x-model="it.link"
                                                placeholder="https://github.com/... or https://live-demo.com" />
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:items-end sm:col-span-2">
                                            <div>
                                                <label
                                                    class="block text-sm font-semibold text-gray-800 mb-1">Start</label>
                                                <input type="month"
                                                    class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10"
                                                    :name="`data[projects][${i}][start]`" x-model="it.start" />
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-semibold text-gray-800 mb-1">End</label>
                                                <input type="month"
                                                    class="w-full rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10"
                                                    :name="`data[projects][${i}][end]`" x-model="it.end" />
                                            </div>
                                        </div>

                                        {{-- PDF Upload --}}
                                        <div class="sm:col-span-2">
                                            <label class="block text-sm font-semibold text-gray-800 mb-1">
                                                Project PDF (optional)
                                            </label>

                                            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                                <input type="file" accept="application/pdf"
                                                    :name="`project_pdfs[${i}]`"
                                                    class="block w-full text-sm text-gray-700
                file:mr-3 file:py-2 file:px-4 file:rounded-xl
                file:border-0 file:bg-gray-900 file:text-white file:text-sm file:font-semibold
                hover:file:bg-black
                border border-gray-200 rounded-2xl p-2" />

                                                {{-- ✅ IMPORTANT: keep existing pdf_path when editing --}}
                                                <input type="hidden" :name="`data[projects][${i}][pdf_path]`"
                                                    x-model="it.pdf_path">

                                                {{-- show existing uploaded pdf --}}
                                                <template x-if="it.pdf_path">
                                                    <a :href="`/storage/${it.pdf_path}`" target="_blank"
                                                        class="inline-flex items-center justify-center gap-2
                    h-10 px-4 rounded-xl
                    bg-gray-900 text-white text-sm font-semibold
                    hover:bg-black transition shadow-sm">
                                                        View
                                                    </a>
                                                </template>
                                            </div>

                                            <p class="mt-1 text-xs text-gray-500">
                                                PDF only. Example: project report / case study.
                                            </p>
                                        </div>


                                    </div>

                                    {{-- Highlights --}}
                                    <div class="mt-4">
                                        <div class="flex items-center justify-between">
                                            <label class="block text-sm font-semibold text-gray-800">Highlights</label>

                                            <button type="button" @click="addBullet(i)"
                                                class="inline-flex items-center gap-1.5 h-9 px-3 rounded-xl
                                   border border-gray-200 bg-white text-sm font-semibold text-gray-700
                                   hover:bg-gray-50 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 4v16m8-8H4" />
                                                </svg>
                                                Add bullet
                                            </button>
                                        </div>

                                        <div class="mt-3 space-y-2">
                                            <template x-for="(b, j) in it.highlights" :key="j">
                                                <div class="flex items-center gap-2">
                                                    <input
                                                        class="flex-1 rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10"
                                                        :name="`data[projects][${i}][highlights][${j}]`"
                                                        x-model="it.highlights[j]"
                                                        placeholder="e.g. Built full CRUD flow with Laravel + Alpine.js" />

                                                    <button type="button" @click="removeBullet(i,j)"
                                                        class="h-9 px-3 rounded-xl
                                           border border-red-200 bg-red-50
                                           text-sm font-semibold text-red-700
                                           hover:bg-red-100 hover:border-red-300
                                           transition">
                                                        Remove
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>


                    <div x-data="{
                        skills: {{ \Illuminate\Support\Js::from(old('data.skills', $resume->data['skills'] ?? [])) }},
                        init() {
                            if (!Array.isArray(this.skills) || this.skills.length === 0) {
                                this.skills = ['']
                            }
                        },
                        add() { this.skills.push('') },
                        remove(i) { this.skills.splice(i, 1) },
                    }" class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">

                        <h2 class="text-lg font-semibold text-gray-900 mb-1">Skills</h2>
                        <p class="text-sm text-gray-500 mb-5">Technical skills & tools.</p>

                        <div class="space-y-2">
                            <template x-for="(s, i) in skills" :key="i">
                                <div class="flex items-center gap-2">
                                    <input
                                        class="flex-1 rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10"
                                        :l-placeholder="Laravel / React / Docker" :name="`data[skills][${i}]`"
                                        x-model="skills[i]" />

                                    <button type="button" @click="remove(i)"
                                        class="h-9 px-3 rounded-xl
                           border border-red-200 bg-red-50
                           text-sm font-semibold text-red-700
                           hover:bg-red-100 transition">
                                        Remove
                                    </button>
                                </div>
                            </template>
                        </div>

                        <button type="button" @click="add()"
                            class="mt-4 inline-flex items-center gap-1.5 h-9 px-3 rounded-xl
               border border-gray-200 bg-white text-sm font-semibold text-gray-700
               hover:bg-gray-50 transition">
                            + Add skill
                        </button>
                    </div>

                    <div x-data="{
                        languages: {{ \Illuminate\Support\Js::from(old('data.languages', $resume->data['languages'] ?? [])) }},
                        init() {
                            if (!Array.isArray(this.languages) || this.languages.length === 0) {
                                this.languages = ['']
                            }
                        },
                        add() { this.languages.push('') },
                        remove(i) { this.languages.splice(i, 1) },
                    }" class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">

                        <h2 class="text-lg font-semibold text-gray-900 mb-1">Languages</h2>
                        <p class="text-sm text-gray-500 mb-5">Languages you can communicate in.</p>

                        <div class="space-y-2">
                            <template x-for="(l, i) in languages" :key="i">
                                <div class="flex items-center gap-2">
                                    <input
                                        class="flex-1 rounded-2xl border-gray-200 focus:border-gray-900 focus:ring-gray-900/10"
                                        placeholder="English / 中文 / Bahasa Melayu" :name="`data[languages][${i}]`"
                                        x-model="languages[i]" />

                                    <button type="button" @click="remove(i)"
                                        class="h-9 px-3 rounded-xl
                           border border-red-200 bg-red-50
                           text-sm font-semibold text-red-700
                           hover:bg-red-100 transition">
                                        Remove
                                    </button>
                                </div>
                            </template>
                        </div>

                        <button type="button" @click="add()"
                            class="mt-4 inline-flex items-center gap-1.5 h-9 px-3 rounded-xl
               border border-gray-200 bg-white text-sm font-semibold text-gray-700
               hover:bg-gray-50 transition">
                            + Add language
                        </button>
                    </div>

                </form>
            </div>

            {{-- Right: Actions Panel --}}
            <aside class="lg:col-span-1">
                <div class="rounded-3xl mt-5 border border-gray-200 bg-white p-5 shadow-sm sticky top-24">
                    <div class="text-sm font-bold tracking-widest text-gray-400 uppercase">Actions</div>

                    <div class="mt-4 space-y-3">
                        {{-- SAVE (submit main form) --}}
                        <button type="submit" form="resume-form"
                            class="w-full inline-flex items-center justify-center h-11 px-4 rounded-2xl
                       bg-gray-900 text-white text-sm font-semibold
                       hover:bg-black transition shadow-sm">
                            Save
                        </button>

                        {{-- PREVIEW --}}
                        @if ($resume->exists)
                            <a href="{{ route('resume.preview', $resume) }}"
                                class="w-full inline-flex items-center justify-center h-11 px-4 rounded-2xl
                           border border-gray-200 bg-white text-sm font-semibold text-gray-900
                           hover:bg-gray-50 transition">
                                Preview
                            </a>
                        @else
                            <button type="button" disabled
                                class="w-full inline-flex items-center justify-center h-11 px-4 rounded-2xl
                           border border-gray-200 bg-gray-100 text-sm font-semibold text-gray-400 cursor-not-allowed">
                                Preview (save first)
                            </button>
                        @endif

                        {{-- LOGIN / CLAIM / BACK --}}
                        @auth
                            @if ($resume->exists && is_null($resume->user_id))
                                <form method="POST" action="{{ route('resume.claim', $resume) }}">
                                    @csrf
                                    <button
                                        class="w-full inline-flex items-center justify-center h-11 px-4 rounded-2xl
                                   bg-white border border-gray-200 text-sm font-semibold text-gray-900
                                   hover:bg-gray-50 transition">
                                        Save to my account
                                    </button>
                                </form>
                            @elseif ($resume->exists && !is_null($resume->user_id))
                                <a href="{{ route('resumes.index') }}"
                                    class="w-full inline-flex items-center justify-center h-11 px-4 rounded-2xl
                               bg-white border border-gray-200 text-sm font-semibold text-gray-900
                               hover:bg-gray-50 transition">
                                    Back to My Resumes
                                </a>
                            @endif
                        @else
                            @php
                                // login success -> redirect()->intended(...) -> claimAfterLogin
                                session(['url.intended' => route('resume.claim.after.login', ['uuid' => $draftUuid])]);
                            @endphp

                            <a href="{{ route('login') }}"
                                class="w-full inline-flex items-center justify-center h-11 px-4 rounded-2xl
                           bg-white border border-gray-200 text-sm font-semibold text-gray-900
                           hover:bg-gray-50 transition">
                                Login to save
                            </a>
                        @endauth

                        {{-- DOWNLOAD --}}
                        @auth
                            @if ($resume->exists && $resume->user_id === auth()->id())
                                <a href="{{ route('resume.download', $resume) }}"
                                    class="w-full inline-flex items-center justify-center h-11 px-4 rounded-2xl
                               bg-white border border-gray-200 text-sm font-semibold text-gray-900
                               hover:bg-gray-50 transition">
                                    Download PDF
                                </a>
                            @endif
                        @endauth
                    </div>

                    {{-- small helper text --}}
                    <div class="mt-4 text-xs text-gray-500 leading-relaxed">
                        Tip: Save first to enable Preview. Login to link this draft to your account.
                    </div>
                </div>
            </aside>


        </div>
    </div>
</x-app-layout>
