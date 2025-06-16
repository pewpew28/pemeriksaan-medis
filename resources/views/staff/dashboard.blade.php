{{-- resources/views/staff/dashboard.blade.php --}}
<x-staff-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Staff') }}
            </h2>
            <div class="text-sm text-gray-600">
                {{ now()->format('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="mb-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg overflow-hidden">
                <div class="px-6 py-8 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-md text-2xl"></i>
                            </div>
                        </div>
                        <div class="ml-6">
                            <h1 class="text-2xl font-bold">Selamat Datang, {{ Auth::user()->name }}!</h1>
                            <p class="text-blue-100 mt-1">Role: {{ ucfirst(Auth::user()->role) }}</p>
                            <p class="text-blue-100 text-sm mt-2">Kelola sistem kesehatan dengan mudah dan efisien</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Patients Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-blue-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-users text-blue-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                                    Total Pasien
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ number_format($totalPatients) }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="text-green-600 text-sm font-medium">
                                <i class="fas fa-arrow-up mr-1"></i>
                                Terdaftar di sistem
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Examinations Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-yellow-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                                    Pemeriksaan Pending
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ number_format($pendingExaminations) }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="text-yellow-600 text-sm font-medium">
                                <i class="fas fa-hourglass-half mr-1"></i>
                                Menunggu proses
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Completed Examinations Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-green-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                                    Pemeriksaan Selesai
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ number_format($completedExaminations) }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="text-green-600 text-sm font-medium">
                                <i class="fas fa-check mr-1"></i>
                                Hasil tersedia
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Users Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-purple-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user-friends text-purple-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                                    Total User
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ number_format($totalUsers) }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="text-purple-600 text-sm font-medium">
                                <i class="fas fa-users-cog mr-1"></i>
                                Semua role
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Quick Actions -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-bolt text-blue-600 mr-2"></i>
                            Aksi Cepat
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'cs')
                                <a href="{{ route('staff.patients.create') }}" 
                                   class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors duration-200 group">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-user-plus text-blue-600 text-lg group-hover:text-blue-700"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Tambah Pasien</p>
                                        <p class="text-xs text-gray-500">Daftar pasien baru</p>
                                    </div>
                                </a>
                            @endif

                            <a href="{{ route('staff.examinations.index') }}" 
                               class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors duration-200 group">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-file-medical text-green-600 text-lg group-hover:text-green-700"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Kelola Pemeriksaan</p>
                                    <p class="text-xs text-gray-500">Lihat semua pemeriksaan</p>
                                </div>
                            </a>

                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('staff.users.index') }}" 
                                   class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors duration-200 group">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-users-cog text-purple-600 text-lg group-hover:text-purple-700"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Kelola User</p>
                                        <p class="text-xs text-gray-500">Atur role & akses</p>
                                    </div>
                                </a>

                                <a href="{{ route('staff.export.patients') }}" 
                                   class="flex items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors duration-200 group">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-download text-orange-600 text-lg group-hover:text-orange-700"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">Export Data</p>
                                        <p class="text-xs text-gray-500">Download laporan</p>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Activity or Status -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-chart-line text-green-600 mr-2"></i>
                            Status Sistem
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- System Status Items -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-900">Sistem Online</span>
                                </div>
                                <span class="text-sm text-green-600 font-medium">Normal</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-900">Database</span>
                                </div>
                                <span class="text-sm text-blue-600 font-medium">Terhubung</span>
                            </div>

                            @if($pendingExaminations > 0)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></div>
                                        <span class="text-sm font-medium text-gray-900">Pemeriksaan Pending</span>
                                    </div>
                                    <span class="text-sm text-yellow-600 font-medium">{{ $pendingExaminations }} item</span>
                                </div>
                            @endif
                        </div>

                        <!-- Progress Bar Example -->
                        @php
                            $totalExaminations = $pendingExaminations + $completedExaminations;
                            $completionRate = $totalExaminations > 0 ? ($completedExaminations / $totalExaminations) * 100 : 0;
                        @endphp
                        
                        @if($totalExaminations > 0)
                            <div class="mt-6">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Tingkat Penyelesaian</span>
                                    <span class="text-sm text-gray-500">{{ number_format($completionRate, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full transition-all duration-300" 
                                         style="width: {{ $completionRate }}%"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Role-specific Information -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informasi Role: {{ ucfirst(Auth::user()->role) }}
                    </h3>
                </div>
                <div class="p-6">
                    @if(Auth::user()->role === 'admin')
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-blue-800">
                                <strong>Administrator:</strong> Anda memiliki akses penuh untuk mengelola seluruh sistem, 
                                termasuk user management, export data, dan pengaturan sistem.
                            </p>
                        </div>
                    @elseif(Auth::user()->role === 'perawat')
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-sm text-green-800">
                                <strong>Perawat:</strong> Anda dapat mengelola pemeriksaan pasien dan mengupload hasil pemeriksaan.
                            </p>
                        </div>
                    @elseif(Auth::user()->role === 'cs')
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <p class="text-sm text-purple-800">
                                <strong>Customer Service:</strong> Anda dapat mengelola data pasien dan melihat informasi pemeriksaan 
                                untuk memberikan layanan terbaik kepada pasien.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-staff-layout>