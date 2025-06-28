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

            @if (Auth::user()->role === 'admin')
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
            @endif

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
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari
                                    Pemeriksaan</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    placeholder="Cari berdasarkan nama pasien, ID, status..."
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-green-500 focus:border-green-500">
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label for="status"
                                    class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" id="status"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-green-500 focus:border-green-500">
                                    <option value="">Semua Status</option>
                                    @foreach ($statusOptions as $status)
                                        <option value="{{ $status }}"
                                            {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Payment Status Filter -->
                            <div>
                                <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Status
                                    Bayar</label>
                                <select name="payment_status" id="payment_status"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-green-500 focus:border-green-500">
                                    <option value="">Semua</option>
                                    @foreach ($paymentStatusOptions as $paymentStatus)
                                        <option value="{{ $paymentStatus }}"
                                            {{ request('payment_status') == $paymentStatus ? 'selected' : '' }}>
                                            {{ ucfirst($paymentStatus) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date From -->
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Dari
                                    Tanggal</label>
                                <input type="date" name="date_from" id="date_from"
                                    value="{{ request('date_from') }}"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-green-500 focus:border-green-500">
                            </div>

                            <!-- Date To -->
                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Sampai
                                    Tanggal</label>
                                <input type="date" name="date_to" id="date_to"
                                    value="{{ request('date_to') }}"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Sort By -->
                            <div>
                                <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-1">Urutkan
                                    Berdasarkan</label>
                                <select name="sort_by" id="sort_by"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-green-500 focus:border-green-500">
                                    <option value="created_at"
                                        {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat
                                    </option>
                                    <option value="scheduled_date"
                                        {{ request('sort_by') == 'scheduled_date' ? 'selected' : '' }}>Tanggal Jadwal
                                    </option>
                                    <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>
                                        Status</option>
                                    <option value="payment_status"
                                        {{ request('sort_by') == 'payment_status' ? 'selected' : '' }}>Status Bayar
                                    </option>
                                    <option value="final_price"
                                        {{ request('sort_by') == 'final_price' ? 'selected' : '' }}>Harga</option>
                                </select>
                            </div>

                            <!-- Sort Order -->
                            <div>
                                <label for="sort_order"
                                    class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                                <select name="sort_order" id="sort_order"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-green-500 focus:border-green-500">
                                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>
                                        Ascending</option>
                                    <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>
                                        Descending</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-search mr-2"></i>
                                Cari
                            </button>
                            <a href="{{ route('staff.examinations.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
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
                            <form method="GET" action="{{ route('staff.examinations.index') }}"
                                class="flex items-center space-x-2">
                                @foreach (request()->except('per_page') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <label for="per_page" class="text-sm text-gray-600">Tampilkan:</label>
                                <select name="per_page" id="per_page" onchange="this.form.submit()"
                                    class="border border-gray-300 rounded text-sm px-2 py-1">
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10
                                    </option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25
                                    </option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50
                                    </option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100
                                    </option>
                                </select>
                                <span class="text-sm text-gray-600">data per halaman</span>
                            </form>

                            <div class="text-sm text-gray-500">
                                Menampilkan {{ $examinations->firstItem() ?? 0 }} -
                                {{ $examinations->lastItem() ?? 0 }} dari {{ $examinations->total() }} data
                            </div>
                        </div>
                    </div>
                </div>

                @if ($examinations->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID Pemeriksaan
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Pasien
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Jadwal
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status Bayar
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Harga
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Metode Bayar
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($examinations as $index => $examination)
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
                                                $statusClass =
                                                    $statusClasses[$examination->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
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
                                                $paymentStatusClass =
                                                    $paymentStatusClasses[$examination->payment_status] ??
                                                    'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paymentStatusClass }}">
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
                                                
                                                @if (Auth::user()->role === 'perawat')
                                                    @if (in_array($examination->status, ['scheduled', 'paid', 'in_progress']))
                                                        <button
                                                            class="inline-flex items-center px-3 py-1 bg-purple-100 hover:bg-purple-200 text-purple-700 text-xs font-medium rounded-md transition-colors duration-200 update-status-examination"
                                                            data-id="{{ $examination->id }}"
                                                            data-patient="{{ $examination->patient->name ?? 'Unknown' }}"
                                                            data-current-status="{{ $examination->status }}"
                                                            title="Ubah Status">
                                                            <i class="fas fa-edit mr-1"></i>Status
                                                        </button>
                                                    @endif
                                                @endif

                                                @if (Auth::user()->role === 'admin' || Auth::user()->role === 'cs')
                                                    @if (
                                                        $examination->status === 'created' ||
                                                            $examination->status === 'pending_cash_payment' ||
                                                            $examination->status === 'pending_payment')
                                                        <a href="{{ route('staff.examinations.payment.form', $examination->id) }}"
                                                            class="inline-flex items-center px-3 py-1 bg-green-100 hover:bg-green-200 text-green-700 text-xs font-medium rounded-md transition-colors duration-200"
                                                            title="Pembayaran">
                                                            <i class="fas fa-edit mr-1"></i>Bayar
                                                        </a>
                                                    @endif
                                                    {{-- <button
                                                        class="inline-flex items-center px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded-md transition-colors duration-200 delete-examination"
                                                        data-id="{{ $examination->id }}"
                                                        data-patient="{{ $examination->patient->name ?? 'Unknown' }}"
                                                        title="Hapus Data">
                                                        <i class="fas fa-trash mr-1"></i>Hapus
                                                    </button> --}}
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
                            @if (request('search') || request('status') || request('payment_status') || request('date_from') || request('date_to'))
                                Tidak ditemukan pemeriksaan dengan kriteria yang ditentukan
                            @else
                                Belum ada data pemeriksaan yang terdaftar
                            @endif
                        </p>
                        @if (request('search') || request('status') || request('payment_status') || request('date_from') || request('date_to'))
                            <a href="{{ route('staff.examinations.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-undo mr-2"></i>
                                Lihat Semua Data
                            </a>
                        @else
                            @if (Auth::user()->role === 'admin' || Auth::user()->role === 'cs')
                                <a href="{{ route('staff.examinations.create') }}"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
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

    <!-- Status Update Modal -->
    <!-- Status Update Modal -->
    <div id="statusUpdateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Update Status Pemeriksaan</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeStatusModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="statusUpdateForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pasien:</label>
                        <p id="patientName" class="text-sm text-gray-900 font-medium"></p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Saat Ini:</label>
                        <p id="currentStatus" class="text-sm text-gray-600"></p>
                    </div>
                    <div class="mb-4">
                        <label for="newStatus" class="block text-sm font-medium text-gray-700 mb-2">Status Baru:</label>
                        <select name="status" id="newStatus" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-green-500 focus:border-green-500">
                            <option value="">Pilih Status</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional):</label>
                        <textarea name="notes" id="notes" rows="3" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-green-500 focus:border-green-500"
                            placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeStatusModal()"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm font-medium rounded-md transition-colors duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeDeleteModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mb-4">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                    <p class="text-sm text-gray-500 text-center mb-2">
                        Apakah Anda yakin ingin menghapus data pemeriksaan untuk pasien:
                    </p>
                    <p id="deletePatientName" class="text-sm font-medium text-gray-900 text-center mb-4"></p>
                    <p class="text-xs text-red-600 text-center">
                        Tindakan ini tidak dapat dibatalkan!
                    </p>
                </div>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="closeDeleteModal()"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm font-medium rounded-md transition-colors duration-200">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            Hapus Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Status Update Modal Functions
        function closeStatusModal() {
            document.getElementById('statusUpdateModal').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Status Update Modal
            const statusButtons = document.querySelectorAll('.update-status-examination');
            statusButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const examinationId = this.getAttribute('data-id');
                    const patientName = this.getAttribute('data-patient');
                    const currentStatus = this.getAttribute('data-current-status');
                    
                    // Set form action URL
                    const form = document.getElementById('statusUpdateForm');
                    form.action = `/staff/examinations/${examinationId}/status`;
                    
                    // Set modal content
                    document.getElementById('patientName').textContent = patientName;
                    document.getElementById('currentStatus').textContent = currentStatus.replace('_', ' ').toUpperCase();
                    
                    // Reset form
                    document.getElementById('newStatus').value = '';
                    document.getElementById('notes').value = '';
                    
                    // Show modal
                    document.getElementById('statusUpdateModal').classList.remove('hidden');
                });
            });

            // Delete Modal
            const deleteButtons = document.querySelectorAll('.delete-examination');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const examinationId = this.getAttribute('data-id');
                    const patientName = this.getAttribute('data-patient');
                    
                    // Set form action URL
                    const form = document.getElementById('deleteForm');
                    form.action = `/staff/examinations/${examinationId}`;
                    
                    // Set modal content
                    document.getElementById('deletePatientName').textContent = patientName;
                    
                    // Show modal
                    document.getElementById('deleteModal').classList.remove('hidden');
                });
            });

            // Status Update Form Submission
            document.getElementById('statusUpdateForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const actionUrl = this.action;
                
                fetch(actionUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message || 'Status berhasil diupdate!');
                        closeStatusModal();
                        // Reload page after short delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showAlert('error', data.message || 'Terjadi kesalahan saat mengupdate status!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'Terjadi kesalahan saat mengupdate status!');
                });
            });

            // Delete Form Submission
            document.getElementById('deleteForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const actionUrl = this.action;
                
                fetch(actionUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message || 'Data berhasil dihapus!');
                        closeDeleteModal();
                        // Reload page after short delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        showAlert('error', data.message || 'Terjadi kesalahan saat menghapus data!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'Terjadi kesalahan saat menghapus data!');
                });
            });
        });

        // Delete Modal Functions
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Alert Function
        function showAlert(type, message) {
            // Create alert element
            const alertDiv = document.createElement('div');
            alertDiv.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg max-w-sm ${
                type === 'success' 
                    ? 'bg-green-100 border border-green-400 text-green-700' 
                    : 'bg-red-100 border border-red-400 text-red-700'
            }`;
            
            alertDiv.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <div class="ml-auto">
                        <button type="button" class="text-gray-400 hover:text-gray-600" onclick="this.parentElement.parentElement.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            
            // Add to body
            document.body.appendChild(alertDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }

        // Close modals when clicking outside
        window.addEventListener('click', function(event) {
            const statusModal = document.getElementById('statusUpdateModal');
            const deleteModal = document.getElementById('deleteModal');
            
            if (event.target === statusModal) {
                closeStatusModal();
            }
            
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
        });

        // Handle Escape key to close modals
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeStatusModal();
                closeDeleteModal();
            }
        });

        // Auto-submit date filters when changed
        document.getElementById('date_from').addEventListener('change', function() {
            if (this.value && document.getElementById('date_to').value) {
                this.form.submit();
            }
        });

        document.getElementById('date_to').addEventListener('change', function() {
            if (this.value && document.getElementById('date_from').value) {
                this.form.submit();
            }
        });

        // Auto-submit status filters when changed
        document.getElementById('status').addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('payment_status').addEventListener('change', function() {
            this.form.submit();
        });
    </script>

</x-staff-layout>