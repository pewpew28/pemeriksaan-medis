<x-staff-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Role User') }}
            </h2>
            <div class="text-sm text-gray-600">
                {{ now()->format('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <div class="mb-6">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('staff.users.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                <i class="fas fa-users mr-2"></i>
                                Manajemen User
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="text-sm font-medium text-gray-500">Edit Role</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- User Info Card -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user text-gray-600 mr-2"></i>
                        Informasi User
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-user text-gray-500 text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h4>
                            <p class="text-gray-600">{{ $user->email }}</p>
                            <div class="mt-2 space-y-1">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    Bergabung: {{ $user->created_at->format('d F Y') }}
                                </div>
                                <div class="flex items-center text-sm">
                                    @if($user->email_verified_at)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Email Terverifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            Email Belum Terverifikasi
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Role Saat Ini:</p>
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
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $colorClass }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            @else
                                <span class="text-gray-400">Tidak ada role</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Role Form -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user-cog text-gray-600 mr-2"></i>
                        Edit Role User
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Pilih role yang akan diberikan kepada user ini</p>
                </div>
                
                <form method="POST" action="{{ route('staff.users.update_role', $user->id) }}" class="p-6" id="roleForm">
                    @csrf
                    @method('PUT')

                    <!-- Current Spatie Roles -->
                    @if($user->roles->count() > 0)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Role Spatie Saat Ini
                            </label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($user->roles as $role)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Role Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Pilih Role Baru <span class="text-red-500">*</span>
                        </label>
                        
                        @error('role')
                            <div class="mb-3 p-3 bg-red-50 border border-red-200 rounded-md">
                                <div class="flex">
                                    <i class="fas fa-exclamation-circle text-red-400 mr-2 mt-0.5"></i>
                                    <div class="text-sm text-red-700">{{ $message }}</div>
                                </div>
                            </div>
                        @enderror

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($roles as $role)
                                @php
                                    $roleInfo = [
                                        'admin' => [
                                            'icon' => 'fas fa-user-shield',
                                            'color' => 'border-red-200 hover:border-red-300',
                                            'bg' => 'bg-red-50',
                                            'text' => 'text-red-700',
                                            'description' => 'Akses penuh ke seluruh sistem'
                                        ],
                                        'dokter' => [
                                            'icon' => 'fas fa-user-md',
                                            'color' => 'border-blue-200 hover:border-blue-300',
                                            'bg' => 'bg-blue-50',
                                            'text' => 'text-blue-700',
                                            'description' => 'Mengelola pasien dan diagnosis'
                                        ],
                                        'perawat' => [
                                            'icon' => 'fas fa-user-nurse',
                                            'color' => 'border-green-200 hover:border-green-300',
                                            'bg' => 'bg-green-50',
                                            'text' => 'text-green-700',
                                            'description' => 'Perawatan dan monitoring pasien'
                                        ],
                                        'cs' => [
                                            'icon' => 'fas fa-headset',
                                            'color' => 'border-yellow-200 hover:border-yellow-300',
                                            'bg' => 'bg-yellow-50',
                                            'text' => 'text-yellow-700',
                                            'description' => 'Layanan pelanggan dan registrasi'
                                        ],
                                        'pasien' => [
                                            'icon' => 'fas fa-user-injured',
                                            'color' => 'border-purple-200 hover:border-purple-300',
                                            'bg' => 'bg-purple-50',
                                            'text' => 'text-purple-700',
                                            'description' => 'Akses portal pasien'
                                        ]
                                    ];
                                    $info = $roleInfo[$role->name] ?? [
                                        'icon' => 'fas fa-user',
                                        'color' => 'border-gray-200 hover:border-gray-300',
                                        'bg' => 'bg-gray-50',
                                        'text' => 'text-gray-700',
                                        'description' => 'Role khusus'
                                    ];
                                    
                                    // Check if this role is currently assigned to user
                                    $isSelected = $user->hasRole($role->name);
                                @endphp
                                
                                <div class="role-option relative">
                                    <input type="radio" 
                                           name="role" 
                                           value="{{ $role->name }}"
                                           id="role_{{ $role->id }}"
                                           {{ $isSelected ? 'checked' : '' }}
                                           class="role-radio absolute opacity-0 w-full h-full cursor-pointer z-10">
                                    
                                    <label for="role_{{ $role->id }}" class="role-card block border-2 {{ $info['color'] }} rounded-lg p-4 transition-all duration-200 cursor-pointer hover:shadow-md">
                                        <div class="flex items-center mb-2">
                                            <i class="{{ $info['icon'] }} {{ $info['text'] }} text-lg mr-3"></i>
                                            <span class="font-medium text-gray-900">{{ ucfirst($role->name) }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600">{{ $info['description'] }}</p>
                                        
                                        <!-- Selected icon -->
                                        <div class="selected-icon absolute top-2 right-2 opacity-0 transition-opacity duration-200">
                                            <i class="fas fa-dot-circle text-blue-500 text-lg"></i>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-3 text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Pilih satu role yang akan diberikan kepada user ini.
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('staff.users.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                        
                        <div class="flex space-x-3">
                            <button type="button" 
                                    onclick="resetForm()"
                                    class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-undo mr-2"></i>
                                Reset
                            </button>
                            
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Role Permissions Info -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <i class="fas fa-info-circle text-blue-400 mr-3 mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-medium text-blue-800 mb-2">Informasi Role</h4>
                        <div class="text-sm text-blue-700 space-y-1">
                            <p><strong>Admin:</strong> Memiliki akses penuh ke seluruh fitur sistem termasuk manajemen user</p>
                            <p><strong>Dokter:</strong> Dapat mengelola data pasien, diagnosis, dan treatment</p>
                            <p><strong>Perawat:</strong> Dapat mengakses data pasien dan melakukan perawatan</p>
                            <p><strong>CS (Customer Service):</strong> Dapat mengelola registrasi dan layanan pelanggan</p>
                            <p><strong>Pasien:</strong> Akses terbatas ke portal pasien dan data pribadi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <!-- SweetAlert2 CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        
        <style>
            /* Custom styles for role selection */
            .role-option .role-radio:checked + .role-card {
                @apply ring-2 ring-blue-500 border-blue-500 bg-blue-50;
            }
            
            .role-option .role-radio:checked + .role-card .selected-icon {
                @apply opacity-100;
            }
            
            .role-card:hover {
                @apply shadow-md transform scale-105;
            }
        </style>
    @endpush

    @push('scripts')
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            $(document).ready(function() {
                // Initialize form
                updateRoleCards();
                
                // Handle radio button changes
                $('.role-radio').on('change', function() {
                    updateRoleCards();
                });
                
                // Handle role card clicks (alternative way to select)
                $('.role-card').on('click', function(e) {
                    if (e.target.type !== 'radio') {
                        e.preventDefault();
                        const radio = $(this).siblings('.role-radio');
                        radio.prop('checked', true).trigger('change');
                    }
                });
                
                // Form submission with confirmation
                $('#roleForm').on('submit', function(e) {
                    e.preventDefault();
                    
                    const selectedRole = $('.role-radio:checked');
                    
                    if (selectedRole.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan!',
                            text: 'Pilih satu role untuk user ini.',
                            confirmButtonColor: '#f59e0b',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }
                    
                    const roleName = selectedRole.val();
                    
                    Swal.fire({
                        title: 'Konfirmasi Perubahan Role',
                        html: `Apakah Anda yakin ingin mengubah role <strong>{{ $user->name }}</strong> menjadi: <br><strong>${roleName}</strong>?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="fas fa-save mr-1"></i> Ya, Simpan!',
                        cancelButtonText: '<i class="fas fa-times mr-1"></i> Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading
                            Swal.fire({
                                title: 'Menyimpan...',
                                html: 'Mohon tunggu sebentar',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading()
                                }
                            });
                            
                            // Submit form using native submit (avoid jQuery)
                            document.getElementById('roleForm').submit();
                        }
                    });
                });
                
                // Show success/error messages
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                @endif

                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: '{{ session('error') }}',
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                @endif
            });

            // Function to update role card appearances
            function updateRoleCards() {
                $('.role-radio').each(function() {
                    const $radio = $(this);
                    const $card = $radio.siblings('.role-card');
                    const $selectedIcon = $card.find('.selected-icon');
                    
                    if ($radio.is(':checked')) {
                        $card.addClass('ring-2 ring-blue-500 border-blue-500 bg-blue-50');
                        $selectedIcon.addClass('opacity-100').removeClass('opacity-0');
                    } else {
                        $card.removeClass('ring-2 ring-blue-500 border-blue-500 bg-blue-50');
                        $selectedIcon.removeClass('opacity-100').addClass('opacity-0');
                    }
                });
            }

            // Reset form function
            function resetForm() {
                Swal.fire({
                    title: 'Reset Form?',
                    text: 'Ini akan mengembalikan pilihan role ke kondisi awal.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#f59e0b',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Reset!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Uncheck all radio buttons first
                        $('.role-radio').prop('checked', false);
                        
                        // Check original user role
                        @if($user->roles->count() > 0)
                            @php $firstRole = $user->roles->first(); @endphp
                            $('input[name="role"][value="{{ $firstRole->name }}"]').prop('checked', true);
                        @endif
                        
                        // Update visual appearance
                        updateRoleCards();
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Form direset!',
                            timer: 1500,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    }
                });
            }
        </script>
    @endpush
</x-staff-layout>