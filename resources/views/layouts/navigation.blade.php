@php
    // 🖤 Refined Minimalist UI Variables
    $linkBase = 'relative px-4 py-2 text-[13px] font-medium tracking-tight rounded-full transition-all duration-300 ease-out';
    $linkIdle = 'text-gray-500 hover:text-black hover:bg-gray-100/50';
    $linkActive = 'text-black bg-white shadow-[0_2px_10px_-3px_rgba(0,0,0,0.08)] ring-1 ring-black/[0.03]';
@endphp

<nav x-data="{ open: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     class="sticky top-0 z-50 transition-all duration-300 px-4 pt-4"
     :class="{ 'pt-2': scrolled }">
     
    <div class="max-w-7xl mx-auto">
        {{-- Main Navigation Container --}}
        <div class="relative group">
            {{-- Subtle outer glow on hover --}}
            <div class="absolute -inset-[1px] bg-gradient-to-r from-black/5 via-black/10 to-black/5 rounded-[32px] blur-sm opacity-0 group-hover:opacity-100 transition duration-1000"></div>
            
            <div class="relative h-14 md:h-16 px-4 flex items-center justify-between bg-white/70 backdrop-blur-2xl border border-white/40 shadow-[0_8px_32px_-4px_rgba(0,0,0,0.05)] rounded-[32px]">
                
                <div class="flex items-center shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group/logo">
                        <div class="relative overflow-hidden rounded-xl shadow-sm transition-transform duration-300 group-hover/logo:scale-105">
                            <img src="{{ asset('images/ex-logo.png') }}" alt="{{ config('app.name') }}" class="h-9 w-9 object-cover" />
                        </div>
                        <span class="hidden sm:inline text-[15px] font-bold tracking-tighter text-gray-900">
                            {{ config('app.name', 'Resume Builder') }}
                        </span>
                    </a>
                </div>

                <div class="hidden md:flex items-center p-1 bg-black/[0.03] rounded-full border border-black/[0.01]">
                    <a href="{{ route('home') }}"
                        class="{{ $linkBase }} {{ request()->routeIs('home') ? $linkActive : $linkIdle }}">
                        Home
                    </a>

                    <a href="{{ route('templates.index') }}"
                        class="{{ $linkBase }} {{ request()->routeIs('templates.*') ? $linkActive : $linkIdle }}">
                        Templates
                    </a>

                    @auth
                        <a href="{{ route('resumes.index') }}"
                            class="{{ $linkBase }} {{ request()->routeIs('resumes.*') || request()->routeIs('resume.*') ? $linkActive : $linkIdle }}">
                            My Resumes
                        </a>
                    @endauth
                </div>

                <div class="hidden md:flex items-center gap-2">
                    @auth
                        {{-- Dropdown is better for a "clean" feel than two separate buttons --}}
                        <x-dropdown align="right" width="56">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-2 pl-1 pr-3 py-1 rounded-full border border-black/5 bg-white/50 hover:bg-white hover:shadow-sm transition-all duration-200">
                                    <div class="h-8 w-8 rounded-full bg-black flex items-center justify-center text-[10px] text-white font-bold">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <span class="text-xs font-semibold text-gray-700">{{ explode(' ', Auth::user()->name)[0] }}</span>
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-3 bg-gray-50/50">
                                    <p class="text-[11px] uppercase tracking-widest text-gray-400 font-bold">Account</p>
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="p-1">
                                    <x-dropdown-link :href="route('resume.create')" class="rounded-lg font-medium">Create New Resume</x-dropdown-link>
                                    <div class="h-px bg-gray-100 my-1"></div>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')" class="rounded-lg text-red-500 font-medium"
                                                onclick="event.preventDefault(); this.closest('form').submit();">
                                            Log Out
                                        </x-dropdown-link>
                                    </form>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-semibold text-gray-600 hover:text-black transition">
                            Login
                        </a>

                        <a href="{{ route('register') }}"
                            class="inline-flex items-center px-5 py-2.5 rounded-full text-[13px] font-bold bg-black text-white hover:bg-gray-800 transition shadow-lg shadow-black/10 active:scale-95">
                            Try for free
                        </a>
                    @endauth
                </div>

                <div class="md:hidden flex items-center">
                    <button @click="open = !open" class="p-2 text-gray-600 hover:bg-black/5 rounded-full transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div x-cloak x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="absolute top-full left-0 right-0 mt-2 p-2 bg-white/90 backdrop-blur-xl border border-black/5 rounded-[24px] shadow-2xl md:hidden">
                <div class="flex flex-col gap-1">
                    <a href="{{ route('home') }}" class="px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('home') ? 'bg-black text-white' : 'text-gray-600' }}">Home</a>
                    <a href="{{ route('templates.index') }}" class="px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('templates.*') ? 'bg-black text-white' : 'text-gray-600' }}">Templates</a>
                    @auth
                        <a href="{{ route('resumes.index') }}" class="px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('resumes.*') ? 'bg-black text-white' : 'text-gray-600' }}">My Resumes</a>
                        <div class="h-px bg-black/5 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 rounded-xl text-sm font-semibold text-red-500">Log Out</button>
                        </form>
                    @else
                        <div class="grid grid-cols-2 gap-2 mt-2">
                            <a href="{{ route('login') }}" class="flex items-center justify-center h-11 rounded-xl border border-black/5 font-bold text-sm">Login</a>
                            <a href="{{ route('register') }}" class="flex items-center justify-center h-11 rounded-xl bg-black text-white font-bold text-sm">Sign Up</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>