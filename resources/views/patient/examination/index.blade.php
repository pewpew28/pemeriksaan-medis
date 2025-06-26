<x-patient-layout>
    {{-- Enhanced header with breadcrumb --}}
    <x-slot name="header">
        <div class="flex flex-col space-y-4 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
            <div class="min-w-0 flex-1">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('pasien.dashboard') }}" class="text-gray-500 hover:text-blue-600 text-sm">
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-700 truncate">Riwayat Pemeriksaan</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900 leading-tight">
                    {{ __('Riwayat Pemeriksaan Medis') }}
                </h2>
                <p class="text-gray-600 text-sm mt-1">Kelola dan pantau semua pemeriksaan medis Anda</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('pasien.examination.register.form') }}" 
                   class="inline-flex items-center px-3 py-2 sm:px-4 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs sm:text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="hidden sm:inline">Pemeriksaan Baru</span>
                    <span class="sm:hidden">Baru</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Alert Messages --}}
            @if (session('success'))
                <div class="mb-4 sm:mb-6 flex items-start p-4 bg-green-50 border border-green-200 rounded-lg">
                    <svg class="w-5 h-5 text-green-400 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-green-800 font-medium text-sm">{{ session('success') }}</div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 sm:mb-6 flex items-start p-4 bg-red-50 border border-red-200 rounded-lg">
                    <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-red-800 font-medium text-sm">{{ session('error') }}</div>
                </div>
            @endif

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6 sm:mb-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                                <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Total</p>
                                <p class="text-lg sm:text-2xl font-bold text-gray-900">
                                    @if(method_exists($examinations, 'total'))
                                        {{ $examinations->total() }}
                                    @else
                                        {{ $examinations->count() }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                                <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Selesai</p>
                                <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $examinations->where('status', 'completed')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                                <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Menunggu</p>
                                <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $examinations->whereIn('status', ['created', 'pending_payment', 'pending_cash_payment', 'scheduled', 'in_progress'])->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                                <p class="text-xs sm:text-sm font-medium text-gray-500 truncate">Hasil</p>
                                <p class="text-lg sm:text-2xl font-bold text-gray-900">{{ $examinations->where('result_available', true)->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content Card --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-200">
                <div class="px-4 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                        <h3 class="text-lg font-semibold text-gray-900">Daftar Pemeriksaan</h3>
                        {{-- Filter Options --}}
                        <div class="flex items-center justify-between sm:justify-end space-x-3">
                            <select class="flex-1 sm:flex-none text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" onchange="window.location.href = this.value;">
                                <option value="{{ route('pasien.examinations.index') }}">Semua Status</option>
                                <option value="{{ route('pasien.examinations.index', ['status' => 'created']) }}" @if(request('status') == 'created') selected @endif>Baru Dibuat</option>
                                <option value="{{ route('pasien.examinations.index', ['status' => 'pending_payment']) }}" @if(request('status') == 'pending_payment') selected @endif>Menunggu Pembayaran Online</option>
                                <option value="{{ route('pasien.examinations.index', ['status' => 'pending_cash_payment']) }}" @if(request('status') == 'pending_cash_payment') selected @endif>Menunggu Pembayaran Tunai</option>
                                <option value="{{ route('pasien.examinations.index', ['status' => 'paid']) }}" @if(request('status') == 'paid') selected @endif>Sudah Dibayar</option>
                                <option value="{{ route('pasien.examinations.index', ['status' => 'expired_payment']) }}" @if(request('status') == 'expired_payment') selected @endif>Pembayaran Kadaluarsa</option>
                                <option value="{{ route('pasien.examinations.index', ['status' => 'scheduled']) }}" @if(request('status') == 'scheduled') selected @endif>Terjadwal</option>
                                <option value="{{ route('pasien.examinations.index', ['status' => 'in_progress']) }}" @if(request('status') == 'in_progress') selected @endif>Sedang Berjalan</option>
                                <option value="{{ route('pasien.examinations.index', ['status' => 'completed']) }}" @if(request('status') == 'completed') selected @endif>Selesai</option>
                                <option value="{{ route('pasien.examinations.index', ['status' => 'cancelled']) }}" @if(request('status') == 'cancelled') selected @endif>Dibatalkan</option>
                            </select>
                            <button class="text-gray-400 hover:text-gray-600 p-2 rounded-lg hover:bg-gray-100 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                @if($examinations->count() > 0)
                    {{-- Mobile Card View --}}
                    <div class="block sm:hidden">
                        <div class="divide-y divide-gray-200">
                            @foreach($examinations as $examination)
                                <div class="p-4 hover:bg-gray-50 transition-colors duration-150">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $examination->serviceItem->name ?? 'N/A' }}
                                                </p>
                                                @php
                                                    $statusClass = '';
                                                    switch ($examination->status) {
                                                        case 'created':
                                                        case 'pending_payment':
                                                        case 'pending_cash_payment':
                                                            $statusClass = 'bg-yellow-100 text-yellow-800';
                                                            break;
                                                        case 'scheduled':
                                                        case 'in_progress':
                                                            $statusClass = 'bg-blue-100 text-blue-800';
                                                            break;
                                                        case 'paid':
                                                        case 'completed':
                                                            $statusClass = 'bg-green-100 text-green-800';
                                                            break;
                                                        case 'cancelled':
                                                        case 'expired_payment':
                                                            $statusClass = 'bg-red-100 text-red-800';
                                                            break;
                                                        default:
                                                            $statusClass = 'bg-gray-100 text-gray-800';
                                                            break;
                                                    }
                                                @endphp
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                                    {{ ucfirst(str_replace('_', ' ', $examination->status)) }}
                                                </span>
                                            </div>
                                            
                                            <p class="text-xs text-gray-500 mt-1">ID: {{ $examination->id }}</p>
                                            
                                            <div class="mt-2 flex items-center text-xs text-gray-500">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 011-1h6a1 1 0 011 1v4h3a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2v-9a2 2 0 012-2h3z"></path>
                                                </svg>
                                                {{ $examination->scheduled_date ? \Carbon\Carbon::parse($examination->scheduled_date)->format('d M Y') : 'Belum Dijadwalkan' }}
                                                @if($examination->scheduled_time)
                                                    • {{ \Carbon\Carbon::parse($examination->scheduled_time)->format('H:i') }} WIB
                                                @endif
                                            </div>

                                            <div class="mt-2 flex items-center justify-between">
                                                <div class="flex items-center space-x-2">
                                                    @if($examination->result_available)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Hasil Tersedia
                                                        </span>
                                                    @endif
                                                    @php
                                                        $paymentStatusClass = '';
                                                        switch ($examination->payment_status) {
                                                            case 'pending':
                                                                $paymentStatusClass = 'bg-yellow-100 text-yellow-800';
                                                                break;
                                                            case 'paid':
                                                                $paymentStatusClass = 'bg-green-100 text-green-800';
                                                                break;
                                                            case 'failed':
                                                                $paymentStatusClass = 'bg-red-100 text-red-800';
                                                                break;
                                                            default:
                                                                $paymentStatusClass = 'bg-gray-100 text-gray-800';
                                                                break;
                                                        }
                                                    @endphp
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $paymentStatusClass }}">
                                                        {{ ucfirst($examination->payment_status) }}
                                                    </span>
                                                </div>
                                                
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('pasien.examinations.show', $examination->id) }}" 
                                                       class="text-blue-600 hover:text-blue-900 text-xs font-medium">
                                                        Detail
                                                    </a>
                                                    @if($examination->result_available)
                                                        <a href="{{ route('pasien.result.download', $examination->id) }}" 
                                                           class="text-green-600 hover:text-green-900 text-xs font-medium">
                                                            Unduh
                                                        </a>
                                                    @endif
                                                    @if($examination->payment_status == 'pending')
                                                        <a href="{{ route('pasien.payment.show', $examination->id) }}" 
                                                           class="text-orange-600 hover:text-orange-900 text-xs font-medium">
                                                            Bayar
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Desktop Table View --}}
                    <div class="hidden sm:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID & Pemeriksaan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pemeriksaan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hasil</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pembayaran</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($examinations as $examination)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $examination->serviceItem->name ?? 'N/A' }}</div>
                                                    <div class="text-xs text-gray-500">ID: {{ $examination->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $examination->scheduled_date ? \Carbon\Carbon::parse($examination->scheduled_date)->format('d M Y') : 'Belum Dijadwalkan' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $examination->scheduled_time ? \Carbon\Carbon::parse($examination->scheduled_time)->format('H:i') . ' WIB' : '' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClass = '';
                                                switch ($examination->status) {
                                                    case 'created':
                                                    case 'pending_payment':
                                                    case 'pending_cash_payment':
                                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                                        break;
                                                    case 'scheduled':
                                                    case 'in_progress':
                                                        $statusClass = 'bg-blue-100 text-blue-800';
                                                        break;
                                                    case 'paid':
                                                    case 'completed':
                                                        $statusClass = 'bg-green-100 text-green-800';
                                                        break;
                                                    case 'cancelled':
                                                    case 'expired_payment':
                                                        $statusClass = 'bg-red-100 text-red-800';
                                                        break;
                                                    default:
                                                        $statusClass = 'bg-gray-100 text-gray-800';
                                                        break;
                                                }
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                                <svg class="w-1.5 h-1.5 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3"/>
                                                </svg>
                                                {{ ucfirst(str_replace('_', ' ', $examination->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($examination->result_available)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-1.5 h-1.5 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3"/>
                                                    </svg>
                                                    Tersedia
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                    <svg class="w-1.5 h-1.5 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3"/>
                                                    </svg>
                                                    Belum Tersedia
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $paymentStatusClass = '';
                                                switch ($examination->payment_status) {
                                                    case 'pending':
                                                        $paymentStatusClass = 'bg-yellow-100 text-yellow-800';
                                                        break;
                                                    case 'paid':
                                                        $paymentStatusClass = 'bg-green-100 text-green-800';
                                                        break;
                                                    case 'failed':
                                                        $paymentStatusClass = 'bg-red-100 text-red-800';
                                                        break;
                                                    default:
                                                        $paymentStatusClass = 'bg-gray-100 text-gray-800';
                                                        break;
                                                }
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paymentStatusClass }}">
                                                <svg class="w-1.5 h-1.5 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="3"/>
                                                </svg>
                                                {{ ucfirst($examination->payment_status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ route('pasien.examinations.show', $examination->id) }}" 
                                                   class="text-blue-600 hover:text-blue-900 transition-colors duration-150">
                                                    Detail
                                                </a>
                                                @if($examination->result_available)
                                                    <a href="{{ route('pasien.result.download', $examination->id) }}" 
                                                       class="text-green-600 hover:text-green-900 transition-colors duration-150 flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        Unduh
                                                    </a>
                                                @endif
                                                @if($examination->payment_status == 'pending')
                                                    <a href="{{ route('pasien.payment.show', $examination->id) }}" 
                                                       class="text-orange-600 hover:text-orange-900 transition-colors duration-150 flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                        </svg>
                                                        Bayar
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if(method_exists($examinations, 'links'))
                        <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
                            {{ $examinations->links() }}
                        </div>
                    @endif
                   
                @else
                    {{-- Enhanced Empty State --}}
                    <div class="text-center py-12 sm:py-16 px-4">
                        <div class="mx-auto h-20 w-20 sm:h-24 sm:w-24 bg-gray-100 rounded-full flex items-center justify-center mb-4 sm:mb-6">
                            <svg class="h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Belum Ada Riwayat Pemeriksaan</h3>
                        <p class="text-gray-500 text-sm sm:text-base mb-6 sm:mb-8 max-w-md mx-auto">
                            Anda belum memiliki riwayat pemeriksaan medis. Mulai jaga kesehatan Anda dengan melakukan pemeriksaan pertama.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="{{ route('pasien.examination.register.form') }}" 
                               class="inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Daftar Pemeriksaan Baru
                            </a>
                            <a href="{{ route('pasien.dashboard') }}" 
                               class="inline-flex items-center justify-center px-4 sm:px-6 py-3 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-patient-layout>