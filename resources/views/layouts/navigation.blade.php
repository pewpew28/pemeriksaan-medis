<nav x-data="{ open: false }" class="bg-gradient-to-r from-blue-600 to-blue-800 shadow-lg border-b-4 border-blue-300">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm8 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="text-white font-bold text-lg hidden md:block">HealthCare+</span>
                    </a>
                </div>

                <!-- Navigation Links berdasarkan Role -->
                <div class="hidden space-x-1 sm:ms-10 sm:flex">
                    @if(Auth::user()->role === 'admin')
                        <!-- Admin Navigation -->
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                                   class="text-white hover:text-blue-200 hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200">
                            <i class="fas fa-tachometer-alt mr-2"></i>{{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')" 
                                   class="text-white hover:text-blue-200 hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200">
                            <i class="fas fa-users mr-2"></i>{{ __('Kelola User') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')" 
                                   class="text-white hover:text-blue-200 hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200">
                            <i class="fas fa-chart-bar mr-2"></i>{{ __('Laporan') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.settings')" :active="request()->routeIs('admin.settings')" 
                                   class="text-white hover:text-blue-200 hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200">
                            <i class="fas fa-cog mr-2"></i>{{ __('Pengaturan') }}
                        </x-nav-link>

                    @elseif(Auth::user()->role === 'perawat')
                        <!-- Perawat Navigation -->
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                                   class="text-white hover:text-blue-200 hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200">
                            <i class="fas fa-home mr-2"></i>{{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('perawat.patients')" :active="request()->routeIs('perawat.patients')" 
                                   class="text-white hover:text-blue-200 hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200">
                            <i class="fas fa-user-injured mr-2"></i>{{ __('Data Pasien') }}
                        </x-nav-link>
                        <x-nav-link :href="route('perawat.schedule')" :active="request()->routeIs('perawat.schedule')" 
                                   class="text-white hover:text-blue-200 hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200">
                            <i class="fas fa-calendar mr-2"></i>{{ __('Jadwal') }}
                        </x-nav-link>
                        <x-nav-link :href="route('perawat.records')" :active="request()->routeIs('perawat.records')" 
                                   class="text-white hover:text-blue-200 hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200">
                            <i class="fas fa-file-medical mr-2"></i>{{ __('Rekam Medis') }}
                        </x-nav-link>

                    @elseif(Auth::user()->role === 'cs')
                        <!-- Customer Service Navigation -->
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                                   class="text-white hover:text-blue-200 hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200">
                            <i class="fas fa-home mr-2"></i>{{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('cs.appointments')" :active="request()->routeIs('cs.appointments')" 
                                   class="text-white hover:text-blue-200 hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200">
                            <i class="fas fa-calendar-check mr-2"></i>{{ __('Janji Temu') }}
                        </x-nav-link>
                        <x-nav-link :href="route('cs.registrations')" :active="request()->routeIs('cs.registrations')" 
                                   class="text-white hover:text-blue-200 hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200">
                            <i class="fas fa-user-plus mr-2"></i>{{ __('Registrasi') }}
                        </x-nav-link>
                        <x-nav-link :href="route('cs.inquiries')" :active="request()->routeIs('cs.inquiries')" 
                                   class="text-white hover:text-blue-200 hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200">
                            <i class="fas fa-comments mr-2"></i>{{ __('Pertanyaan') }}
                        </x-nav-link>

                    @elseif(Auth::user()->role === 'pasien')
                        <!-- Pasien Navigation -->
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                                   class="text-white hover:text-blue-200 hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200">
                            <i class="fas fa-home mr-2"></i>{{ __('Beranda') }}
                        </x-nav-link>
                        <x-nav-link :href="route('pasien.examination.register.form')" :active="request()->routeIs('pasien.examination.register.form')" 
                                   class="text-white hover:text-blue-200 hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200">
                            <i class="fas fa-calendar-plus mr-2"></i>{{ __('Buat Janji') }}
                        </x-nav-link>
                        <x-nav-link :href="route('pasien.examinations.index')" :active="request()->routeIs('pasien.examinations.index')" 
                                   class="text-white hover:text-blue-200 hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium transition-all duration-200">
                            <i class="fas fa-history mr-2"></i>{{ __('Riwayat') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Right Side: Notifications & User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                <!-- Notifications -->
                @if(Auth::user()->role !== 'pasien')
                <div class="relative">
                    <button class="text-white hover:text-blue-200 p-2 rounded-full hover:bg-blue-700 transition-all duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5v5zm-6-12a3 3 0 013 3v6l2 2h-10l2-2V8a3 3 0 013-3z"></path>
                        </svg>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                    </button>
                </div>
                @endif

                <!-- Role Badge -->
                <div class="px-3 py-1 bg-blue-500 rounded-full">
                    <span class="text-white text-xs font-semibold uppercase">
                        {{ ucfirst(Auth::user()->role) }}
                    </span>
                </div>

                <!-- Settings Dropdown -->
                <x-dropdown align="right" width="64">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:bg-blue-600 transition ease-in-out duration-150">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-bold text-sm">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </span>
                                </div>
                                <div class="hidden md:block">{{ Auth::user()->name }}</div>
                            </div>
                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <div class="text-sm text-gray-900 font-medium">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                            <div class="text-xs text-blue-600 mt-1">{{ ucfirst(Auth::user()->role) }}</div>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center">
                            <i class="fas fa-user mr-3 text-gray-400"></i>
                            {{ __('Profil Saya') }}
                        </x-dropdown-link>

                        {{-- @if(Auth::user()->role === 'pasien')
                        <x-dropdown-link :href="route('pasien.settings')" class="flex items-center">
                            <i class="fas fa-cog mr-3 text-gray-400"></i>
                            {{ __('Pengaturan') }}
                        </x-dropdown-link>
                        @endif --}}

                        <div class="border-t border-gray-100"></div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="flex items-center text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-3"></i>
                                {{ __('Keluar') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-blue-200 hover:bg-blue-700 focus:outline-none focus:bg-blue-700 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-blue-700">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::user()->role === 'admin')
                <!-- Admin Mobile Menu -->
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:bg-blue-600">
                    <i class="fas fa-tachometer-alt mr-2"></i>{{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')" class="text-white hover:bg-blue-600">
                    <i class="fas fa-users mr-2"></i>{{ __('Kelola User') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')" class="text-white hover:bg-blue-600">
                    <i class="fas fa-chart-bar mr-2"></i>{{ __('Laporan') }}
                </x-responsive-nav-link>

            @elseif(Auth::user()->role === 'perawat')
                <!-- Perawat Mobile Menu -->
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:bg-blue-600">
                    <i class="fas fa-home mr-2"></i>{{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('perawat.patients')" :active="request()->routeIs('perawat.patients')" class="text-white hover:bg-blue-600">
                    <i class="fas fa-user-injured mr-2"></i>{{ __('Data Pasien') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('perawat.schedule')" :active="request()->routeIs('perawat.schedule')" class="text-white hover:bg-blue-600">
                    <i class="fas fa-calendar mr-2"></i>{{ __('Jadwal') }}
                </x-responsive-nav-link>

            @elseif(Auth::user()->role === 'cs')
                <!-- CS Mobile Menu -->
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:bg-blue-600">
                    <i class="fas fa-home mr-2"></i>{{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('cs.appointments')" :active="request()->routeIs('cs.appointments')" class="text-white hover:bg-blue-600">
                    <i class="fas fa-calendar-check mr-2"></i>{{ __('Janji Temu') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('cs.registrations')" :active="request()->routeIs('cs.registrations')" class="text-white hover:bg-blue-600">
                    <i class="fas fa-user-plus mr-2"></i>{{ __('Registrasi') }}
                </x-responsive-nav-link>

            @elseif(Auth::user()->role === 'pasien')
                <!-- Pasien Mobile Menu -->
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:bg-blue-600">
                    <i class="fas fa-home mr-2"></i>{{ __('Beranda') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pasien.examination.register.form')" :active="request()->routeIs('pasien.examination.register.form')" class="text-white hover:bg-blue-600">
                    <i class="fas fa-calendar-plus mr-2"></i>{{ __('Buat Janji') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pasien.examinations.index')" :active="request()->routeIs('pasien.examinations.index')" class="text-white hover:bg-blue-600">
                    <i class="fas fa-history mr-2"></i>{{ __('Riwayat') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-blue-600">
            <div class="px-4 mb-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-blue-200">{{ Auth::user()->email }}</div>
                        <div class="text-xs text-blue-300 bg-blue-600 px-2 py-1 rounded mt-1 inline-block">
                            {{ ucfirst(Auth::user()->role) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-white hover:bg-blue-600">
                    <i class="fas fa-user mr-2"></i>{{ __('Profil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-red-200 hover:bg-red-600">
                        <i class="fas fa-sign-out-alt mr-2"></i>{{ __('Keluar') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Include Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">