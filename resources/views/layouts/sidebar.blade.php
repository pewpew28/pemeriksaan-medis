{{-- resources/views/components/sidebar.blade.php --}}
@props(['user' => null])

@php
    $user = $user ?? Auth::user();
    $currentRoute = request()->route()->getName();
@endphp

<!-- Mobile Toggle Button -->
<button x-on:click="sidebarOpen = !sidebarOpen"
    class="fixed top-4 left-4 z-50 lg:hidden bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 transition-all duration-200">
    <svg x-show="!sidebarOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
    </svg>
    <svg x-show="sidebarOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
    </svg>
</button>

<!-- Overlay for mobile -->
<div x-show="sidebarOpen" x-on:click="sidebarOpen = false"
    x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden" style="display: none;"></div>

<!-- Sidebar -->
<aside x-show="sidebarOpen || window.innerWidth >= 1024"
    x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform"
    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
    class="fixed left-0 top-0 z-40 w-80 h-screen bg-gradient-to-b from-blue-600 to-blue-800 shadow-2xl lg:translate-x-0">

    <!-- Logo Section -->
    <div class="flex items-center justify-center h-20 border-b border-blue-500 bg-blue-700">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                <svg class="w-7 h-7 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm8 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V8z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div>
                <h1 class="text-white font-bold text-xl">HealthCare+</h1>
                <p class="text-blue-200 text-xs">Sistem Manajemen Kesehatan</p>
            </div>
        </div>
    </div>

    <!-- User Info -->
    <div class="p-6 border-b border-blue-500 bg-blue-700">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center">
                <span class="text-blue-600 font-bold text-xl">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </span>
            </div>
            <div class="flex-1">
                <h3 class="text-white font-semibold text-lg">{{ $user->name }}</h3>
                <p class="text-blue-200 text-sm">{{ $user->email }}</p>
                <div class="mt-2">
                    <span
                        class="inline-block px-3 py-1 bg-blue-500 text-white text-xs font-semibold rounded-full uppercase">
                        {{ $user->role }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        @if ($user->role === 'admin')
            <!-- Admin Menu -->
            <div class="mb-4">
                <h2 class="text-blue-200 text-xs font-semibold uppercase tracking-wider px-3">Menu Administrator</h2>
            </div>

            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="fas fa-tachometer-alt">
                Dashboard
            </x-sidebar-link>

            <x-sidebar-link :href="route('staff.dashboard')" :active="request()->routeIs('staff.dashboard')" icon="fas fa-hospital-user">
                Staff Dashboard
            </x-sidebar-link>

            <x-sidebar-link :href="route('staff.patients.index')" :active="request()->routeIs('staff.patients*')" icon="fas fa-user-injured">
                Kelola Pasien
            </x-sidebar-link>

            <x-sidebar-link :href="route('staff.examinations.index')" :active="request()->routeIs('staff.examinations*')" icon="fas fa-file-medical">
                Kelola Pemeriksaan
            </x-sidebar-link>

            <x-sidebar-link :href="route('staff.users.index')" :active="request()->routeIs('staff.users*')" icon="fas fa-users">
                Kelola User
            </x-sidebar-link>

            <div x-data="{ open: false }" class="relative">
                <button type="button" @click="open = !open"
                    class="flex items-center w-full px-4 py-2 text-blue-200 hover:text-white hover:bg-blue-600 rounded-lg transition-all duration-200 focus:outline-none {{ request()->routeIs('staff.export*') ? 'bg-blue-700 text-white shadow-inner' : '' }}">
                    <i class="fas fa-download w-5"></i>
                    <span class="ml-4 text-sm flex-1 text-left">Export Data</span>
                    <svg class="w-4 h-4 ml-2 transform transition-transform duration-200" :class="{ 'rotate-90': open }"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95" class="mt-2 space-y-1 pl-8">
                    {{-- Added pl-8 for indentation --}}
                    <x-sidebar-link :href="route('staff.export.patients')" :active="request()->routeIs('staff.export.patients')" icon="fas fa-file-excel">
                        {{-- Changed icon for clarity --}}
                        Data Pasien
                    </x-sidebar-link>
                    <x-sidebar-link :href="route('staff.export.examinations')" {{-- Assuming a new route for examination export --}} :active="request()->routeIs('staff.export.examinations')" icon="fas fa-file-csv">
                        {{-- Changed icon for clarity --}}
                        Data Pemeriksaan
                    </x-sidebar-link>
                </div>
            </div>
        @elseif($user->role === 'perawat')
            <!-- Perawat Menu -->
            <div class="mb-4">
                <h2 class="text-blue-200 text-xs font-semibold uppercase tracking-wider px-3">Menu Perawat</h2>
            </div>

            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="fas fa-home">
                Dashboard
            </x-sidebar-link>

            <x-sidebar-link :href="route('staff.dashboard')" :active="request()->routeIs('staff.dashboard')" icon="fas fa-hospital-user">
                Staff Dashboard
            </x-sidebar-link>

            <x-sidebar-link :href="route('staff.examinations.index')" :active="request()->routeIs('staff.examinations*')" icon="fas fa-file-medical">
                Kelola Pemeriksaan
            </x-sidebar-link>
        @elseif($user->role === 'cs')
            <!-- CS Menu -->
            <div class="mb-4">
                <h2 class="text-blue-200 text-xs font-semibold uppercase tracking-wider px-3">Menu Customer Service</h2>
            </div>

            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="fas fa-home">
                Dashboard
            </x-sidebar-link>

            <x-sidebar-link :href="route('staff.dashboard')" :active="request()->routeIs('staff.dashboard')" icon="fas fa-hospital-user">
                Staff Dashboard
            </x-sidebar-link>

            <x-sidebar-link :href="route('staff.patients.index')" :active="request()->routeIs('staff.patients*')" icon="fas fa-user-injured">
                Kelola Pasien
            </x-sidebar-link>

            <x-sidebar-link :href="route('staff.examinations.index')" :active="request()->routeIs('staff.examinations*')" icon="fas fa-file-medical">
                Kelola Pemeriksaan
            </x-sidebar-link>
        @elseif($user->role === 'pasien')
            <!-- Pasien Menu -->
            <div class="mb-4">
                <h2 class="text-blue-200 text-xs font-semibold uppercase tracking-wider px-3">Menu Pasien</h2>
            </div>

            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="fas fa-home">
                Dashboard
            </x-sidebar-link>

            <x-sidebar-link :href="route('pasien.dashboard')" :active="request()->routeIs('pasien.dashboard')" icon="fas fa-user-circle">
                Dashboard Pasien
            </x-sidebar-link>

            <x-sidebar-link :href="route('pasien.profile')" :active="request()->routeIs('pasien.profile*')" icon="fas fa-user">
                Profil Saya
            </x-sidebar-link>

            <x-sidebar-link :href="route('pasien.examination.register.form')" :active="request()->routeIs('pasien.examination.register*')" icon="fas fa-plus-circle">
                Daftar Pemeriksaan
            </x-sidebar-link>

            <x-sidebar-link :href="route('pasien.examinations.index')" :active="request()->routeIs('pasien.examinations*')" icon="fas fa-file-medical-alt">
                Pemeriksaan Saya
            </x-sidebar-link>
        @endif
    </nav>

    <!-- Bottom Section -->
    <div class="p-4 border-t border-blue-500 bg-blue-700">
        <!-- Profile & Logout -->
        <div class="space-y-2">
            <a href="{{ route('profile.edit') }}"
                class="flex items-center px-4 py-2 text-blue-200 hover:text-white hover:bg-blue-600 rounded-lg transition-all duration-200">
                <i class="fas fa-user w-5"></i>
                <span class="ml-4 text-sm">Profil Saya</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" onclick="return confirm('Yakin ingin keluar?')"
                    class="w-full flex items-center px-4 py-2 text-red-200 hover:text-white hover:bg-red-600 rounded-lg transition-all duration-200">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span class="ml-4 text-sm">Keluar</span>
                </button>
            </form>
        </div>
    </div>
</aside>
