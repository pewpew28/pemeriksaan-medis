<x-staff-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Data Pemeriksaan') }}
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
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-clipboard-list text-green-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Manajemen Pemeriksaan</h3>
                                <p class="text-sm text-gray-500">Kelola data pemeriksaan pasien</p>
                            </div>
                        </div>
                        {{-- @if (Auth::user()->role === 'admin' || Auth::user()->role === 'cs')
                            <a href="{{ route('staff.examinations.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Pemeriksaan
                            </a>
                        @endif --}}
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <!-- Total Examinations -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-blue-500">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clipboard-list text-blue-600 text-lg"></i>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </div>
                                <div class="text-xl font-bold text-gray-900">
                                    {{ number_format($totalExaminations ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Payment -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-yellow-500">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clock text-yellow-600 text-lg"></i>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Menunggu Bayar
                                </div>
                                <div class="text-xl font-bold text-gray-900">
                                    {{ number_format($pendingPayment ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Scheduled -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-orange-500">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar-check text-orange-600 text-lg"></i>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Terjadwal
                                </div>
                                <div class="text-xl font-bold text-gray-900">
                                    {{ number_format($scheduled ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Completed -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-green-500">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Selesai
                                </div>
                                <div class="text-xl font-bold text-gray-900">
                                    {{ number_format($completed ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- This Month -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-purple-500">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar-plus text-purple-600 text-lg"></i>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bulan Ini
                                </div>
                                <div class="text-xl font-bold text-gray-900">
                                    {{ number_format($thisMonthExaminations ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-indigo-500">
                    <div class="p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-money-bill-wave text-indigo-600 text-lg"></i>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pendapatan
                                </div>
                                <div class="text-lg font-bold text-gray-900">
                                    Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-search text-gray-600 mr-2"></i>
                        Pencarian & Filter
                    </h3>
                </div>
                <div class="p-6">
                    <form method="GET" action="{{ route('staff.examinations.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                            <!-- Search Input -->
                            <div class="md:col-span-2">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Pemeriksaan</label>
                                <input type="text" 
                                       name="search" 
                                       id="search"
                                       value="{{ request('search') }}"
                                       placeholder="Cari berdasarkan nama pasien, ID, status..."
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-green-500 focus:border-green-500">
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" id="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-green-500 focus:border-green-500">
                                    <option value="">Semua Status</option>
                                    @foreach($statusOptions as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Payment Status Filter -->
                            <div>
                                <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Status Bayar</label>
                                <select name="payment_status" id="payment_status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-green-500 focus:border-green-500">
                                    <option value="">Semua</option>
                                    @foreach($paymentStatusOptions as $paymentStatus)
                                        <option value="{{ $paymentStatus }}" {{ request('payment_status') == $paymentStatus ? 'selected' : '' }}>
                                            {{ ucfirst($paymentStatus) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date From -->
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                                <input type="date" 
                                       name="date_from" 
                                       id="date_from"
                                       value="{{ request('date_from') }}"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-green-500 focus:border-green-500">
                            </div>

                            <!-- Date To -->
                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                                <input type="date" 
                                       name="date_to" 
                                       id="date_to"
                                       value="{{ request('date_to') }}"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Sort By -->
                            <div>
                                <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-1">Urutkan Berdasarkan</label>
                                <select name="sort_by" id="sort_by" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-green-500 focus:border-green-500">
                                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                                    <option value="scheduled_date" {{ request('sort_by') == 'scheduled_date' ? 'selected' : '' }}>Tanggal Jadwal</option>
                                    <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Status</option>
                                    <option value="payment_status" {{ request('sort_by') == 'payment_status' ? 'selected' : '' }}>Status Bayar</option>
                                    <option value="final_price" {{ request('sort_by') == 'final_price' ? 'selected' : '' }}>Harga</option>
                                </select>
                            </div>

                            <!-- Sort Order -->
                            <div>
                                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                                <select name="sort_order" id="sort_order" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-green-500 focus:border-green-500">
                                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-search mr-2"></i>
                                Cari
                            </button>
                            <a href="{{ route('staff.examinations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-undo mr-2"></i>
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Examinations Table -->
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-table text-gray-600 mr-2"></i>
                            Daftar Pemeriksaan
                        </h3>
                        <div class="flex items-center space-x-4">
                            <!-- Per Page Selection -->
                            <form method="GET" action="{{ route('staff.examinations.index') }}" class="flex items-center space-x-2">
                                @foreach(request()->except('per_page') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <label for="per_page" class="text-sm text-gray-600">Tampilkan:</label>
                                <select name="per_page" id="per_page" onchange="this.form.submit()" class="border border-gray-300 rounded text-sm px-2 py-1">
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                <span class="text-sm text-gray-600">data per halaman</span>
                            </form>
                            
                            <div class="text-sm text-gray-500">
                                Menampilkan {{ $examinations->firstItem() ?? 0 }} - {{ $examinations->lastItem() ?? 0 }} dari {{ $examinations->total() }} data
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($examinations->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID Pemeriksaan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Pasien
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Jadwal
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status Bayar
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Harga
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Metode Bayar
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($examinations as $index => $examination)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                        {{ $examinations->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $examination->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $examination->patient->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $examination->scheduled_date ? \Carbon\Carbon::parse($examination->scheduled_date)->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        @php
                                            $statusClasses = [
                                                'created' => 'bg-gray-100 text-gray-800',
                                                'pending_payment' => 'bg-yellow-100 text-yellow-800',
                                                'pending_cash_payment' => 'bg-orange-100 text-orange-800',
                                                'paid' => 'bg-blue-100 text-blue-800',
                                                'expired_payment' => 'bg-red-100 text-red-800',
                                                'scheduled' => 'bg-indigo-100 text-indigo-800',
                                                'in_progress' => 'bg-purple-100 text-purple-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                            ];
                                            $statusClass = $statusClasses[$examination->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $examination->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        @php
                                            $paymentStatusClasses = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'paid' => 'bg-green-100 text-green-800',
                                                'failed' => 'bg-red-100 text-red-800',
                                            ];
                                            $paymentStatusClass = $paymentStatusClasses[$examination->payment_status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paymentStatusClass }}">
                                            {{ ucfirst($examination->payment_status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        Rp {{ number_format($examination->final_price ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $examination->payment_method ? ucfirst($examination->payment_method) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('staff.examinations.show', $examination->id) }}" 
                                               class="inline-flex items-center px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-md transition-colors duration-200" 
                                               title="Lihat Detail">
                                                <i class="fas fa-eye mr-1"></i>Lihat
                                            </a>

                                            @if (Auth::user()->role === 'admin' || Auth::user()->role === 'cs')
                                                {{-- <a href="{{ route('staff.examinations.edit', $examination->id) }}" 
                                                   class="inline-flex items-center px-3 py-1 bg-green-100 hover:bg-green-200 text-green-700 text-xs font-medium rounded-md transition-colors duration-200" 
                                                   title="Edit Data">
                                                    <i class="fas fa-edit mr-1"></i>Edit
                                                </a> --}}
                                                <button class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded-md transition-colors duration-200 delete-examination" 
                                                        data-id="{{ $examination->id }}" 
                                                        data-patient="{{ $examination->patient->name ?? 'Unknown' }}" 
                                                        title="Hapus Data">
                                                    <i class="fas fa-trash mr-1"></i>Hapus
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $examinations->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-clipboard-list text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data pemeriksaan</h3>
                        <p class="text-gray-500 mb-4">
                            @if(request('search') || request('status') || request('payment_status') || request('date_from') || request('date_to'))
                                Tidak ditemukan pemeriksaan dengan kriteria yang ditentukan
                            @else
                                Belum ada data pemeriksaan yang terdaftar
                            @endif
                        </p>
                        @if(request('search') || request('status') || request('payment_status') || request('date_from') || request('date_to'))
                            <a href="{{ route('staff.examinations.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-undo mr-2"></i>
                                Lihat Semua Data
                            </a>
                        @else
                            @if (Auth::user()->role === 'admin' || Auth::user()->role === 'cs')
                                <a href="{{ route('staff.examinations.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Pemeriksaan Pertama
                                </a>
                            @endif
                        @endif
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
                // Handle delete button click
                $(document).on('click', '.delete-examination', function() {
                    var examinationId = $(this).data('id');
                    var patientName = $(this).data('patient') || 'pemeriksaan ini';

                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        html: `Apakah Anda yakin ingin menghapus pemeriksaan <strong>${patientName}</strong>?<br><small class="text-gray-500">Tindakan ini tidak dapat dibatalkan.</small>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="fas fa-trash mr-1"></i> Ya, Hapus!',
                        cancelButtonText: '<i class="fas fa-times mr-1"></i> Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-lg',
                            confirmButton: 'rounded-lg',
                            cancelButton: 'rounded-lg'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading
                            Swal.fire({
                                title: 'Menghapus...',
                                html: 'Mohon tunggu sebentar',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading()
                                }
                            });

                            // Create form and submit
                            var form = $('<form>', {
                                'method': 'POST',
                                'action': '{{ url("staff/examinations") }}/' + examinationId
                            });

                            form.append($('<input>', {
                                'type': 'hidden',
                                'name': '_token',
                                'value': '{{ csrf_token() }}'
                            }));

                            form.append($('<input>', {
                                'type': 'hidden',
                                'name': '_method',
                                'value': 'DELETE'
                            }));

                            $('body').append(form);
                            form.submit();
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

                // Auto-submit form when date filters change
                $('#date_from, #date_to').on('change', function() {
                    // Optional: Auto-submit when date range changes
                    // $(this).closest('form').submit();
                });

                // Clear date filters
                $('#clear-dates').on('click', function(e) {
                    e.preventDefault();
                    $('#date_from, #date_to').val('');
                    $(this).closest('form').submit();
                });
            });
        </script>
    @endpush
</x-staff-layout>