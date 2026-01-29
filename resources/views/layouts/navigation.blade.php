<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Left -->
            <div class="flex items-center gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-900" />
                        <span class="hidden sm:inline text-sm font-semibold tracking-tight text-gray-900">
                            {{ config('app.name', 'Resume Builder') }}
                        </span>
                    </a>
                </div>

                <!-- Desktop Links -->
                <div class="hidden sm:flex items-center gap-1">
                    @php
                        $tabBase = 'inline-flex items-center h-10 px-4 rounded-xl text-sm font-semibold transition';
                        $tabActive = 'bg-gray-900 text-white';
                        $tabIdle = 'text-gray-700 hover:bg-gray-100';
                    @endphp

                    <a href="{{ route('home') }}"
                        class="{{ $tabBase }} {{ request()->routeIs('home') ? $tabActive : $tabIdle }}">
                        Home
                    </a>

                    <a href="{{ route('templates.index') }}"
                        class="{{ $tabBase }} {{ request()->routeIs('templates.*') ? $tabActive : $tabIdle }}">
                        Templates
                    </a>


                    @auth
                        <a href="{{ route('resumes.index') }}"
                            class="{{ $tabBase }}
                            {{ request()->routeIs('resumes.*') ||
                            request()->routeIs('resume.create') ||
                            request()->routeIs('resume.edit') ||
                            request()->routeIs('resume.preview')
                                ? $tabActive
                                : $tabIdle }}">
                            My Resumes
                        </a>

                    @endauth

                </div>
            </div>

            <!-- Right -->
            <div class="hidden sm:flex items-center gap-3">
                @auth
                    {{-- User Dropdown --}}
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center gap-2 h-10 px-3 rounded-xl
                                       border border-gray-200 bg-white
                                       text-sm font-semibold text-gray-800
                                       hover:bg-gray-50 transition">
                                <span class="max-w-[160px] truncate">{{ Auth::user()->name }}</span>
                                <svg class="h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3">
                                <div class="text-xs text-gray-500">Signed in as</div>
                                <div class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->email }}</div>
                            </div>
                            <div class="h-px bg-gray-100"></div>

                            {{-- <x-dropdown-link :href="route('profile.edit')">
                                    Profile
                                </x-dropdown-link> --}}

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    {{-- Guest --}}
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center h-10 px-4 rounded-xl
                              text-sm font-semibold text-gray-700 hover:bg-gray-100 transition">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                        class="inline-flex items-center h-10 px-4 rounded-xl
                              border border-gray-200 bg-white
                              text-sm font-semibold text-gray-900 hover:bg-gray-50 transition">
                        Sign up
                    </a>
                @endauth

            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-xl
                           text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Responsive Menu -->
    <div x-cloak x-show="open" class="sm:hidden border-t border-gray-200 bg-white">
        <div class="px-4 py-4 space-y-2">

            {{-- Create (mobile) --}}
            <a href="{{ route('resume.create') }}"
                class="block w-full text-center px-4 py-2.5 rounded-xl
                      text-sm font-semibold bg-gray-900 text-white hover:bg-black transition">
                Create
            </a>

            <a href="{{ route('home') }}"
                class="block px-4 py-2.5 rounded-xl text-sm font-semibold
                      {{ request()->routeIs('home') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100' }}">
                Home
            </a>

            <a href="{{ route('templates.index') }}"
                class="block px-4 py-2.5 rounded-xl text-sm font-semibold
                      {{ request()->routeIs('templates.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100' }}">
                Templates
            </a>

            @auth

                <a href="{{ route('resumes.index') }}"
                    class="block px-4 py-2.5 rounded-xl text-sm font-semibold
              {{ request()->routeIs('resumes.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    My Resumes
                </a>
                {{-- <a href="{{ route('dashboard') }}"
                    class="block px-4 py-2.5 rounded-xl text-sm font-semibold
                          {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    Dashboard
                </a> --}}

                <div class="mt-3 rounded-2xl border border-gray-200 p-4">
                    <div class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</div>

                    <div class="mt-3 space-y-1">
                        {{-- <a href="{{ route('profile.edit') }}"
                            class="block px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-100 transition">
                            Profile
                        </a> --}}

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2.5 rounded-xl text-sm font-semibold
                                       text-red-600 hover:bg-red-50 transition">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-2 gap-2 pt-2">
                    <a href="{{ route('login') }}"
                        class="block w-full text-center px-4 py-2.5 rounded-xl
                              text-sm font-semibold border border-gray-200 text-gray-900 hover:bg-gray-50 transition">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                        class="block w-full text-center px-4 py-2.5 rounded-xl
                              text-sm font-semibold bg-white border border-gray-200 text-gray-900 hover:bg-gray-50 transition">
                        Sign up
                    </a>
                </div>
            @endauth

        </div>
    </div>
</nav>
