<x-staff-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">Detail Layanan</h1>
                        <p class="text-gray-600">Informasi lengkap layanan dan statistik terkait</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('staff.service.edit', $serviceItem) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Layanan
                        </a>
                        <a href="{{ route('staff.service.index') }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Service Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Layanan</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Layanan</label>
                                <p class="text-lg text-gray-900 font-medium">{{ $serviceItem->name }}</p>
                            </div>

                            <!-- Kategori -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                <div class="inline-flex items-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        {{ $serviceItem->category->name }}
                                    </span>
                                </div>
                            </div>

                            <!-- Kode Layanan -->
                            @if($serviceItem->code)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Layanan</label>
                                <p class="text-gray-900 font-mono bg-gray-50 px-3 py-1 rounded border inline-block">{{ $serviceItem->code }}</p>
                            </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                <p class="text-gray-700 leading-relaxed">
                                    {{ $serviceItem->description ?: 'Tidak ada deskripsi' }}
                                </p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                                    <p class="text-lg font-semibold text-green-600">
                                        Rp {{ number_format($serviceItem->price, 0, ',', '.') }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    @if($serviceItem->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <span class="w-1.5 h-1.5 bg-red-400 rounded-full mr-1.5"></span>
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Unit & Normal Range -->
                            @if($serviceItem->unit || $serviceItem->normal_range)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @if($serviceItem->unit)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                                    <p class="text-gray-900">{{ $serviceItem->unit }}</p>
                                </div>
                                @endif

                                @if($serviceItem->normal_range)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Rentang Normal</label>
                                    <p class="text-gray-900">{{ $serviceItem->normal_range }}</p>
                                </div>
                                @endif
                            </div>
                            @endif

                            <!-- Sort Order -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Urutan Tampil</label>
                                <p class="text-gray-900">{{ $serviceItem->sort_order ?? 0 }}</p>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-500">
                                    <div>
                                        <span class="font-medium">Dibuat:</span>
                                        {{ $serviceItem->created_at->format('d M Y, H:i') }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Diperbarui:</span>
                                        {{ $serviceItem->updated_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistik</h2>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                <div>
                                    <p class="text-sm font-medium text-blue-900">Total Pemeriksaan</p>
                                    <p class="text-2xl font-bold text-blue-600">{{ $statistics['totalExaminations'] }}</p>
                                </div>
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                <div>
                                    <p class="text-sm font-medium text-green-900">Pemeriksaan Selesai</p>
                                    <p class="text-2xl font-bold text-green-600">{{ $statistics['completedExaminations'] }}</p>
                                </div>
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                                <div>
                                    <p class="text-sm font-medium text-purple-900">Total Pendapatan</p>
                                    <p class="text-lg font-bold text-purple-600">
                                        Rp {{ number_format($statistics['totalRevenue'], 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="p-2 bg-purple-100 rounded-lg">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                                <div>
                                    <p class="text-sm font-medium text-yellow-900">Rata-rata Harga</p>
                                    <p class="text-lg font-bold text-yellow-600">
                                        Rp {{ number_format($statistics['averagePrice'], 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="p-2 bg-yellow-100 rounded-lg">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($statistics['totalExaminations'] > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-md font-semibold text-gray-900 mb-3">Tingkat Penyelesaian</h3>
                        <div class="relative">
                            @php
                                $completionRate = $statistics['totalExaminations'] > 0 
                                    ? ($statistics['completedExaminations'] / $statistics['totalExaminations']) * 100 
                                    : 0;
                            @endphp
                            <div class="flex mb-2 items-center justify-between">
                                <div>
                                    <span class="text-xs font-semibold text-gray-600">Progress</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-semibold text-gray-600">{{ number_format($completionRate, 1) }}%</span>
                                </div>
                            </div>
                            <div class="overflow-hidden h-2 text-xs flex rounded bg-gray-200">
                                <div style="width: {{ $completionRate }}%" 
                                     class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500 transition-all duration-500">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Recent Examinations -->
                    @if($serviceItem->examinations->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-md font-semibold text-gray-900 mb-4">Pemeriksaan Terbaru</h3>
                        <div class="space-y-3">
                            @foreach($serviceItem->examinations->take(5) as $examination)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $examination->patient->name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $examination->created_at->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="ml-3">
                                    @php
                                        $statusColors = [
                                            'completed' => 'bg-green-100 text-green-800',
                                            'scheduled' => 'bg-blue-100 text-blue-800',
                                            'in_progress' => 'bg-yellow-100 text-yellow-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                            'pending_payment' => 'bg-orange-100 text-orange-800',
                                        ];
                                        $statusColor = $statusColors[$examination->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                        {{ ucfirst(str_replace('_', ' ', $examination->status)) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        @if($serviceItem->examinations->count() > 5)
                        <div class="mt-4 text-center">
                            <a href="{{ route('staff.examinations.index', ['service_item' => $serviceItem->id]) }}" 
                               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                Lihat semua pemeriksaan ({{ $serviceItem->examinations->count() }})
                            </a>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-staff-layout>