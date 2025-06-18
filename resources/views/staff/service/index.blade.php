<x-staff-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    <i class="fas fa-concierge-bell mr-3 text-blue-600"></i>{{ __('Data Layanan') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Kelola semua layanan kesehatan yang tersedia</p>
            </div>
            <a href="{{ route('staff.service.create') }}" 
               class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200">
                <i class="fas fa-plus mr-2"></i>Tambah Layanan
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-600 mb-1">Total Layanan</p>
                                <p class="text-3xl font-bold text-blue-800">{{ $totalServiceItems }}</p>
                            </div>
                            <div class="w-14 h-14 flex items-center justify-center bg-blue-500 rounded-full shadow-lg">
                                <i class="fas fa-concierge-bell text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-600 mb-1">Layanan Aktif</p>
                                <p class="text-3xl font-bold text-green-800">{{ $activeServiceItems }}</p>
                            </div>
                            <div class="w-14 h-14 flex items-center justify-center bg-green-500 rounded-full shadow-lg">
                                <i class="fas fa-check-circle text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-red-600 mb-1">Tidak Aktif</p>
                                <p class="text-3xl font-bold text-red-800">{{ $inactiveServiceItems }}</p>
                            </div>
                            <div class="w-14 h-14 flex items-center justify-center bg-red-500 rounded-full shadow-lg">
                                <i class="fas fa-times-circle text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-yellow-600 mb-1">Rata-rata Harga</p>
                                <p class="text-2xl font-bold text-yellow-800">Rp {{ number_format($averagePrice, 0, ',', '.') }}</p>
                            </div>
                            <div class="w-14 h-14 flex items-center justify-center bg-yellow-500 rounded-full shadow-lg">
                                <i class="fas fa-dollar-sign text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-filter mr-2 text-gray-600"></i>Filter & Pencarian
                    </h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('staff.service.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-search mr-1"></i>Pencarian
                            </label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                   placeholder="Cari nama atau deskripsi layanan..."
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:ring-2 transition-all duration-200">
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-toggle-on mr-1"></i>Status
                            </label>
                            <select name="status" id="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:ring-2 transition-all duration-200">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>

                        <div>
                            <label for="sort_by" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-sort mr-1"></i>Urutkan
                            </label>
                            <select name="sort_by" id="sort_by" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:ring-2 transition-all duration-200">
                                <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nama</option>
                                <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Harga</option>
                                <option value="is_active" {{ request('sort_by') == 'is_active' ? 'selected' : '' }}>Status</option>
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                            </select>
                        </div>

                        <div class="flex items-end space-x-3">
                            <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-4 rounded-lg shadow-md transform hover:scale-105 transition-all duration-200">
                                <i class="fas fa-search mr-2"></i>Cari
                            </button>
                            <a href="{{ route('staff.service.index') }}" class="flex-1 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-semibold py-3 px-4 rounded-lg shadow-md transform hover:scale-105 transition-all duration-200 text-center">
                                <i class="fas fa-undo mr-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Service Items Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-list mr-2 text-gray-600"></i>Daftar Layanan
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    <i class="fas fa-tag mr-1"></i>Nama Layanan
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    <i class="fas fa-align-left mr-1"></i>Deskripsi
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    <i class="fas fa-money-bill mr-1"></i>Harga
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    <i class="fas fa-circle mr-1"></i>Status
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    <i class="fas fa-calendar mr-1"></i>Dibuat
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    <i class="fas fa-cog mr-1"></i>Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($serviceItems as $item)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fas fa-stethoscope text-white text-sm"></i>
                                            </div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $item->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700 max-w-xs">
                                            {{ Str::limit($item->description, 50) ?: '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-green-600">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->is_active)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                                <i class="fas fa-check-circle mr-1"></i>Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                                <i class="fas fa-times-circle mr-1"></i>Tidak Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar-alt mr-2 text-gray-400"></i>
                                            {{ $item->created_at->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center space-x-2">
                                            <a href="{{ route('staff.service.show', $item) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 hover:text-blue-700 transition-all duration-200" 
                                               title="Detail">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            <a href="{{ route('staff.service.edit', $item) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 hover:bg-indigo-200 hover:text-indigo-700 transition-all duration-200" 
                                               title="Edit">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                            <form action="{{ route('staff.service.toggle_status', $item) }}" 
                                                  method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $item->is_active ? 'bg-yellow-100 text-yellow-600 hover:bg-yellow-200 hover:text-yellow-700' : 'bg-green-100 text-green-600 hover:bg-green-200 hover:text-green-700' }} transition-all duration-200" 
                                                        title="{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="fas fa-{{ $item->is_active ? 'pause' : 'play' }} text-sm"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('staff.service.destroy', $item) }}" 
                                                  method="POST" class="inline delete-form" 
                                                  data-service-name="{{ $item->name }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" 
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-700 transition-all duration-200 delete-btn" 
                                                        title="Hapus">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-search text-gray-400 text-4xl mb-4"></i>
                                            <h3 class="text-lg font-semibold text-gray-600 mb-2">Tidak ada data layanan</h3>
                                            <p class="text-gray-500">Tidak ada layanan yang ditemukan dengan kriteria pencarian saat ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($serviceItems->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $serviceItems->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Handle delete confirmation with SweetAlert
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const form = this.closest('.delete-form');
                    const serviceName = form.getAttribute('data-service-name');
                    
                    Swal.fire({
                        title: '<strong>Konfirmasi Hapus</strong>',
                        html: `
                            <div class="text-center">
                                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                                </div>
                                <p class="text-gray-700 mb-2">Apakah Anda yakin ingin menghapus layanan</p>
                                <p class="font-semibold text-gray-900 mb-4">"${serviceName}"?</p>
                                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                    <p class="text-sm text-red-800">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Tindakan ini tidak dapat dibatalkan!
                                    </p>
                                </div>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus!',
                        cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-xl',
                            confirmButton: 'font-semibold py-2 px-6 rounded-lg shadow-lg',
                            cancelButton: 'font-semibold py-2 px-6 rounded-lg shadow-lg'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading state
                            Swal.fire({
                                title: '<strong>Menghapus Layanan</strong>',
                                html: `
                                    <div class="text-center">
                                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                                            <i class="fas fa-spinner fa-spin text-blue-600 text-xl"></i>
                                        </div>
                                        <p class="text-gray-700">Mohon tunggu sebentar...</p>
                                    </div>
                                `,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                customClass: {
                                    popup: 'rounded-xl'
                                }
                            });
                            
                            // Submit the form
                            form.submit();
                        }
                    });
                });
            });
        });

        // Show success/error messages from session
        @if(session('success'))
            Swal.fire({
                title: '<strong>Berhasil!</strong>',
                html: `
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                            <i class="fas fa-check text-green-600 text-xl"></i>
                        </div>
                        <p class="text-gray-700">{{ session('success') }}</p>
                    </div>
                `,
                confirmButtonColor: '#059669',
                confirmButtonText: '<i class="fas fa-check mr-2"></i>OK',
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'font-semibold py-2 px-6 rounded-lg shadow-lg'
                },
                buttonsStyling: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: '<strong>Error!</strong>',
                html: `
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                            <i class="fas fa-times text-red-600 text-xl"></i>
                        </div>
                        <p class="text-gray-700">{{ session('error') }}</p>
                    </div>
                `,
                confirmButtonColor: '#dc2626',
                confirmButtonText: '<i class="fas fa-times mr-2"></i>OK',
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'font-semibold py-2 px-6 rounded-lg shadow-lg'
                },
                buttonsStyling: false
            });
        @endif
    </script>
    @endpush
</x-staff-layout>