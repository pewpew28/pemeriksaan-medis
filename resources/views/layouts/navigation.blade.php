<nav x-data="{ open: false, notificationOpen: false }"
    class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 shadow-xl backdrop-blur-sm border-b border-blue-400/20">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center space-x-3 group transition-all duration-300 hover:scale-105">
                        <div
                            class="w-10 h-10 bg-white/90 backdrop-blur rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300 group-hover:bg-white">
                            <img src="{{ asset('assets/icon/healthcare-icon.png') }}" alt="HealthCare+" class="w-6 h-6">
                        </div>
                        <div class="hidden md:block">
                            <span class="text-white font-bold text-xl tracking-tight">HealthCare</span>
                            <span class="text-blue-200 font-light text-xl">+</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links berdasarkan Role -->
                <div class="hidden space-x-2 sm:ms-10 sm:flex">
                    <!-- Pasien Navigation -->
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        class="text-white/90 hover:text-white hover:bg-white/10 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 flex items-center space-x-2 backdrop-blur-sm border border-transparent hover:border-white/20 hover:shadow-lg">
                        <i class="fas fa-home text-sm"></i>
                        <span>{{ __('Beranda') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('pasien.examination.register.form')" :active="request()->routeIs('pasien.examination.register.form')"
                        class="text-white/90 hover:text-white hover:bg-white/10 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 flex items-center space-x-2 backdrop-blur-sm border border-transparent hover:border-white/20 hover:shadow-lg">
                        <i class="fas fa-calendar-plus text-sm"></i>
                        <span>{{ __('Buat Janji') }}</span>
                    </x-nav-link>
                    <x-nav-link :href="route('pasien.examinations.index')" :active="request()->routeIs('pasien.examinations.index')"
                        class="text-white/90 hover:text-white hover:bg-white/10 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-300 flex items-center space-x-2 backdrop-blur-sm border border-transparent hover:border-white/20 hover:shadow-lg">
                        <i class="fas fa-history text-sm"></i>
                        <span>{{ __('Riwayat') }}</span>
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Side: Notifications & User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:space-x-3">

                <!-- Role Badge -->
                <div
                    class="px-4 py-2 bg-gradient-to-r from-white/10 to-white/5 backdrop-blur-sm rounded-xl border border-white/20 shadow-lg">
                    <span class="text-white text-xs font-semibold uppercase tracking-wider">
                        {{ ucfirst(Auth::user()->role) }}
                    </span>
                </div>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="72">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2.5 text-sm leading-4 font-medium rounded-xl text-white bg-white/10 hover:bg-white/20 focus:outline-none focus:bg-white/20 transition-all duration-300 backdrop-blur-sm border border-white/20 hover:border-white/30 group hover:shadow-lg">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-8 h-8 bg-gradient-to-br from-white to-blue-50 rounded-xl flex items-center justify-center shadow-md group-hover:shadow-lg transition-all duration-300">
                                    <span class="text-blue-600 font-bold text-sm">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="hidden md:block text-left">
                                    <div class="text-white font-medium text-sm">{{ Auth::user()->name }}</div>
                                    <div class="text-blue-200 text-xs">{{ Auth::user()->email }}</div>
                                </div>
                            </div>
                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4 text-white/70 group-hover:text-white transition-colors duration-300"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                                    <span
                                        class="text-white font-bold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-900 font-semibold">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-gray-600">{{ Auth::user()->email }}</div>
                                    <div
                                        class="text-xs text-blue-600 mt-1 px-2 py-1 bg-blue-100 rounded-full inline-block">
                                        {{ ucfirst(Auth::user()->role) }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="py-2">
                            <x-dropdown-link :href="route('profile.edit')"
                                class="flex items-center px-4 py-3 hover:bg-blue-50 transition-colors duration-200 group">
                                <i class="fas fa-user mr-3 text-blue-500 group-hover:text-blue-600"></i>
                                <span class="text-gray-700 group-hover:text-gray-900">{{ __('Profil Saya') }}</span>
                            </x-dropdown-link>
                        </div>

                        <div class="border-t border-gray-100 py-2">
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="flex items-center px-4 py-3 text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors duration-200 group">
                                    <i
                                        class="fas fa-sign-out-alt mr-3 group-hover:scale-110 transition-transform duration-200"></i>
                                    <span>{{ __('Keluar') }}</span>
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2.5 rounded-xl text-white/80 hover:text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 transition-all duration-300 backdrop-blur-sm border border-transparent hover:border-white/20">
                    <svg class="h-6 w-6 transition-transform duration-300" :class="{ 'rotate-90': open }"
                        stroke="currentColor" fill="none" viewBox="0 0 24 24">
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

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="hidden sm:hidden bg-gradient-to-b from-blue-700 to-blue-800 backdrop-blur-md border-t border-blue-500/20">

        <!-- Mobile Menu Items -->
        <div class="pt-4 pb-3 space-y-2 px-4">

            <!-- Pasien Mobile Menu -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                class="text-white hover:bg-white/10 rounded-xl px-4 py-3 flex items-center space-x-3 transition-all duration-300 border border-transparent hover:border-white/20">
                <i class="fas fa-home text-blue-200"></i>
                <span>{{ __('Beranda') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pasien.examination.register.form')" :active="request()->routeIs('pasien.examination.register.form')"
                class="text-white hover:bg-white/10 rounded-xl px-4 py-3 flex items-center space-x-3 transition-all duration-300 border border-transparent hover:border-white/20">
                <i class="fas fa-calendar-plus text-blue-200"></i>
                <span>{{ __('Buat Janji') }}</span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pasien.examinations.index')" :active="request()->routeIs('pasien.examinations.index')"
                class="text-white hover:bg-white/10 rounded-xl px-4 py-3 flex items-center space-x-3 transition-all duration-300 border border-transparent hover:border-white/20">
                <i class="fas fa-history text-blue-200"></i>
                <span>{{ __('Riwayat') }}</span>
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-6 border-t border-blue-600/30 mx-4">
            <!-- User Info Card -->
            <div class="mb-4 p-4 bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-white to-blue-50 rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-blue-600 font-bold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-base text-white">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-blue-200 truncate">{{ Auth::user()->email }}</div>
                        <div
                            class="text-xs text-blue-300 bg-blue-600/30 px-3 py-1 rounded-full mt-2 inline-block backdrop-blur-sm border border-blue-400/20">
                            {{ ucfirst(Auth::user()->role) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Items -->
            <div class="space-y-2">
                <x-responsive-nav-link :href="route('profile.edit')"
                    class="text-white hover:bg-white/10 rounded-xl px-4 py-3 flex items-center space-x-3 transition-all duration-300 border border-transparent hover:border-white/20">
                    <i class="fas fa-user text-blue-200"></i>
                    <span>{{ __('Profil') }}</span>
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="text-red-200 hover:bg-red-500/20 hover:text-red-100 rounded-xl px-4 py-3 flex items-center space-x-3 transition-all duration-300 border border-transparent hover:border-red-400/30">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>{{ __('Keluar') }}</span>
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Enhanced Font Awesome and additional styles -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Custom Active States */
    .nav-link-active {
        @apply bg-white/20 text-white border-white/30 shadow-lg;
    }

    /* Smooth transitions for Alpine.js */
    [x-cloak] {
        display: none !important;
    }

    /* Custom scrollbar for notification dropdown */
    .max-h-96::-webkit-scrollbar {
        width: 4px;
    }

    .max-h-96::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .max-h-96::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .max-h-96::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Loading animation for notification badge */
    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: .7;
        }
    }

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    /* Responsive improvements */
    @media (max-width: 640px) {
        .gradient-mobile {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #1e40af 100%);
        }
    }
</style>
