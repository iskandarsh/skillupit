<nav x-data="{ open: false }"
    class="sticky top-0 z-50 backdrop-blur-md bg-white/80 border-b border-gray-200 shadow-sm">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- LEFT -->
            <div class="flex items-center gap-6">

                <!-- Logo -->
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-2 font-bold text-gray-800 hover:opacity-80 transition">

                    <div class="w-9 h-9 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-500 
                                flex items-center justify-center text-white font-bold shadow-md">
                        S
                    </div>

                    <span class="hidden sm:block text-lg tracking-tight">
                        SkillUp<span class="text-indigo-600">IT</span>
                    </span>
                </a>

                <!-- Menu -->
                <div class="hidden sm:flex items-center gap-2">

                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 rounded-xl text-sm font-medium transition
                        {{ request()->routeIs('dashboard') 
                            ? 'bg-indigo-100 text-indigo-600 shadow-sm' 
                            : 'text-gray-600 hover:bg-gray-100' }}">
                        Dashboard
                    </a>

                    @auth
                    @if(auth()->user()->level == 1)
                    <div x-data="{ open: false }" class="relative">

                        <!-- BUTTON -->
                        <button @click="open = !open"
                            class="px-4 py-2 rounded-xl text-sm font-medium transition flex items-center gap-2
{{ request()->routeIs('kelas.*') || request()->routeIs('referral.*') || request()->routeIs('mentor.*') || request()->routeIs('schedule.*')
    ? 'bg-indigo-100 text-indigo-600 shadow-sm'
    : 'text-gray-600 hover:bg-gray-100' }}">

                            Master

                            <svg class="w-4 h-4 transition-transform"
                                :class="{ 'rotate-180': open }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- DROPDOWN -->
                        <div x-show="open"
                            @click.outside="open = false"
                            x-transition
                            class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg border z-50">

                            <!-- KELAS -->
                            <a href="{{ route('kelas.index') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100 
{{ request()->routeIs('kelas.*') ? 'text-indigo-600 font-semibold' : 'text-gray-700' }}">
                                📚 Kelas
                            </a>

                            <!-- MENTOR -->
                            <a href="{{ route('mentor.index') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100 
{{ request()->routeIs('mentor.*') ? 'text-indigo-600 font-semibold' : 'text-gray-700' }}">
                                🧑‍🏫 Mentor
                            </a>

                            <!-- REFERRAL -->
                            <a href="{{ route('referral.index') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100 
{{ request()->routeIs('referral.*') ? 'text-indigo-600 font-semibold' : 'text-gray-700' }}">
                                🎯 Referal
                            </a>

                            <!-- SCHEDULE (NEW) -->
                            <a href="{{ route('schedule.index') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100 
{{ request()->routeIs('schedule.*') ? 'text-indigo-600 font-semibold' : 'text-gray-700' }}">
                                📅 Schedule
                            </a>

                        </div>
                    </div>
                    @endif
                    @endauth

                </div>
            </div>

            <!-- RIGHT -->
            <div class="hidden sm:flex items-center gap-3">

                <!-- CTA -->
                <a href="{{ url('/') }}"
                    class="px-4 py-2 rounded-xl text-sm font-semibold 
   bg-gradient-to-r from-indigo-500 to-purple-500 text-white shadow hover:opacity-90 transition">
                    🚀 Explore
                </a>

                <!-- User Dropdown -->
                <div class="relative" x-data="{ dropdown: false }">
                    <button @click="dropdown = !dropdown"
                        class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-gray-100 transition">

                        <div
                            class="w-9 h-9 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 text-white flex items-center justify-center text-sm font-semibold shadow">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>

                        <span class="text-sm text-gray-700 font-medium">
                            {{ Auth::user()->name }}
                        </span>

                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="dropdown" @click.outside="dropdown = false"
                        x-transition
                        class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border p-2">

                        <a href="{{ route('profile.edit') }}"
                            class="block px-4 py-2 rounded-lg text-sm text-gray-700 hover:bg-gray-100">
                            👤 Profile
                        </a>

                        <div class="border-t my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                class="w-full text-left px-4 py-2 rounded-lg text-sm text-red-500 hover:bg-red-50">
                                🚪 Logout
                            </button>
                        </form>

                    </div>
                </div>

            </div>

            <!-- MOBILE BUTTON -->
            <button @click="open = !open"
                class="sm:hidden p-2 rounded-lg hover:bg-gray-100 transition">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path :class="{'hidden': open}" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': !open}" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

        </div>
    </div>

    <!-- MOBILE MENU -->
    <div x-show="open" x-transition
        class="sm:hidden px-4 pb-4 space-y-2 bg-white border-t">

        <a href="{{ route('dashboard') }}"
            class="block px-4 py-2 rounded-xl text-sm font-medium
            {{ request()->routeIs('dashboard') 
                ? 'bg-indigo-100 text-indigo-600' 
                : 'text-gray-600 hover:bg-gray-100' }}">
            Dashboard
        </a>

        @auth
        @if(auth()->user()->level == 1)

        <a href="{{ route('kelas.index') }}"
            class="block px-4 py-2 rounded-xl text-sm font-medium
            {{ request()->routeIs('kelas.*') 
                ? 'bg-indigo-100 text-indigo-600' 
                : 'text-gray-600 hover:bg-gray-100' }}">
            Kelas
        </a>

        <a href="{{ route('mentor.index') }}"
            class="block px-4 py-2 rounded-xl text-sm font-medium
            {{ request()->routeIs('mentor.*') 
                ? 'bg-indigo-100 text-indigo-600' 
                : 'text-gray-600 hover:bg-gray-100' }}">
            Mentor
        </a>

        <a href="{{ route('referral.index') }}"
            class="block px-4 py-2 rounded-xl text-sm font-medium
            {{ request()->routeIs('referral.*') 
                ? 'bg-indigo-100 text-indigo-600' 
                : 'text-gray-600 hover:bg-gray-100' }}">
            Referral
        </a>

        <!-- SCHEDULE (NEW) -->
        <a href="{{ route('schedule.index') }}"
            class="block px-4 py-2 rounded-xl text-sm font-medium
            {{ request()->routeIs('schedule.*') 
                ? 'bg-indigo-100 text-indigo-600' 
                : 'text-gray-600 hover:bg-gray-100' }}">
            Schedule
        </a>

        @endif
        @endauth

        <!-- USER -->
        <div class="border-t pt-3 mt-3">

            <div class="px-2 text-sm text-gray-700 font-semibold">
                {{ Auth::user()->name }}
            </div>
            <div class="px-2 text-xs text-gray-500 mb-2">
                {{ Auth::user()->email }}
            </div>

            <a href="{{ route('profile.edit') }}"
                class="block px-4 py-2 rounded-lg text-sm hover:bg-gray-100">
                Profile
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    class="w-full text-left px-4 py-2 rounded-lg text-sm text-red-500 hover:bg-red-50">
                    Logout
                </button>
            </form>

        </div>

    </div>

</nav>