<x-staff-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen User') }}
            </h2>
            <div class="text-sm text-gray-600">
                {{ now()->format('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-6 bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-users text-blue-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Manajemen User</h3>
                                <p class="text-sm text-gray-500">Kelola user dan role</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total Users -->
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
                                    Total User
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ number_format($users->total()) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Users -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-red-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user-shield text-red-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                                    Admin
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ $totalAdmin }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Staff Users -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-green-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user-tie text-green-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                                    Staff
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ $totalStaff }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Patient Users -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-purple-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user-injured text-purple-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                                    Pasien
                                </div>
                                <div class="text-2xl font-bold text-gray-900">
                                    {{ $totalPasien }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-table text-gray-600 mr-2"></i>
                            Daftar User
                        </h3>
                        <div class="text-sm text-gray-500">
                            Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} user
                        </div>
                    </div>
                </div>
                
                @if($users->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Roles (Spatie)
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Daftar
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $index => $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                        {{ $users->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-user text-gray-500 text-xs"></i>
                                            </div>
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        @if($user->role)
                                            @php
                                                $roleColors = [
                                                    'admin' => 'bg-red-100 text-red-800',
                                                    'dokter' => 'bg-blue-100 text-blue-800',
                                                    'perawat' => 'bg-green-100 text-green-800',
                                                    'cs' => 'bg-yellow-100 text-yellow-800',
                                                    'pasien' => 'bg-purple-100 text-purple-800',
                                                ];
                                                $colorClass = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        @if($user->roles->count() > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($user->roles as $role)
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        {{ $role->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-400">Tidak ada role</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        @if($user->email_verified_at)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Terverifikasi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>
                                                Belum Verifikasi
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        <div class="flex items-center space-x-2">
                                            @if (Auth::user()->role === 'admin')
                                                <a href="{{ route('staff.users.edit_role', $user->id) }}" 
                                                   class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-md transition-colors duration-200" 
                                                   title="Edit Role">
                                                    <i class="fas fa-user-cog mr-1"></i>Edit Role
                                                </a>
                                            @endif
                                            
                                            <button class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-md transition-colors duration-200 view-user-details" 
                                                    data-user-id="{{ $user->id }}"
                                                    data-user-name="{{ $user->name }}"
                                                    data-user-email="{{ $user->email }}"
                                                    data-user-role="{{ $user->role }}"
                                                    data-user-roles="{{ $user->roles->pluck('name')->join(', ') }}"
                                                    data-user-created="{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}"
                                                    data-user-verified="{{ $user->email_verified_at ? 'Ya' : 'Tidak' }}"
                                                    title="Lihat Detail">
                                                <i class="fas fa-eye mr-1"></i>Detail
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-users text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data user</h3>
                        <p class="text-gray-500">Belum ada user yang terdaftar dalam sistem</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
        <!-- SweetAlert2 CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    @endpush

    @push('scripts')
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(document).ready(function() {
                // Handle view user details
                $(document).on('click', '.view-user-details', function() {
                    var userName = $(this).data('user-name');
                    var userEmail = $(this).data('user-email');
                    var userRole = $(this).data('user-role');
                    var userRoles = $(this).data('user-roles');
                    var userCreated = $(this).data('user-created');
                    var userVerified = $(this).data('user-verified');

                    Swal.fire({
                        title: 'Detail User',
                        html: `
                            <div class="text-left space-y-3">
                                <div class="flex justify-between border-b pb-2">
                                    <span class="font-medium text-gray-600">Nama:</span>
                                    <span class="text-gray-900">${userName}</span>
                                </div>
                                <div class="flex justify-between border-b pb-2">
                                    <span class="font-medium text-gray-600">Email:</span>
                                    <span class="text-gray-900">${userEmail}</span>
                                </div>
                                <div class="flex justify-between border-b pb-2">
                                    <span class="font-medium text-gray-600">Role:</span>
                                    <span class="text-gray-900">${userRole || '-'}</span>
                                </div>
                                <div class="flex justify-between border-b pb-2">
                                    <span class="font-medium text-gray-600">Spatie Roles:</span>
                                    <span class="text-gray-900">${userRoles || 'Tidak ada'}</span>
                                </div>
                                <div class="flex justify-between border-b pb-2">
                                    <span class="font-medium text-gray-600">Tanggal Daftar:</span>
                                    <span class="text-gray-900">${userCreated}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600">Email Terverifikasi:</span>
                                    <span class="text-gray-900">${userVerified}</span>
                                </div>
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#3b82f6',
                        customClass: {
                            popup: 'rounded-lg',
                            confirmButton: 'rounded-lg'
                        }
                    });
                });

                // Show success message if exists
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end',
                        customClass: {
                            popup: 'rounded-lg'
                        }
                    });
                @endif

                // Show error message if exists
                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: '{{ session('error') }}',
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end',
                        customClass: {
                            popup: 'rounded-lg'
                        }
                    });
                @endif
            });
        </script>
    @endpush
</x-staff-layout>