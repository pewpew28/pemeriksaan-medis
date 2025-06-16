<x-staff-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pemeriksaan') }} #{{ $examination->id }}
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
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-eye text-indigo-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Detail Pemeriksaan #{{ $examination->id }}</h3>
                                <p class="text-sm text-gray-500">Informasi lengkap mengenai pemeriksaan pasien</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('staff.examinations.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali ke Daftar
                            </a>
                            @if (auth()->user()->hasRole(['admin', 'perawat']) && !$examination->result_available)
                                <a href="{{ route('staff.examinations.upload_result.form', $examination) }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    <i class="fas fa-upload mr-2"></i>
                                    Upload Hasil
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Patient Information Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-user-injured text-blue-600 text-sm"></i>
                            </div>
                            Informasi Pasien
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <dl class="divide-y divide-gray-200">
                            <div class="py-3 flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                                <dd class="text-sm text-gray-900 font-semibold">{{ $examination->patient->name }}</dd>
                            </div>
                            <div class="py-3 flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900">{{ $examination->patient->email ?: 'Tidak Tersedia' }}</dd>
                            </div>
                            <div class="py-3 flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">No. Telepon</dt>
                                <dd class="text-sm text-gray-900">{{ $examination->patient->phone_number ?: 'Tidak Tersedia' }}</dd>
                            </div>
                            <div class="py-3 grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Umur</dt>
                                    <dd class="text-sm text-gray-900 font-medium">{{ $examination->patient->age }} tahun</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Jenis Kelamin</dt>
                                    <dd class="text-sm text-gray-900">{{ $examination->patient->gender ? ($examination->patient->gender == 'L' ? 'Laki-laki' : 'Perempuan') : 'Tidak Tersedia' }}</dd>
                                </div>
                            </div>
                            <div class="py-3 flex justify-between items-start">
                                <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                                <dd class="text-sm text-gray-900 text-right max-w-md">{{ $examination->patient->address ?: 'Tidak Tersedia' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Examination Detail Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-stethoscope text-green-600 text-sm"></i>
                            </div>
                            Detail Pemeriksaan
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <dl class="divide-y divide-gray-200">
                            <div class="py-3 flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">ID Pemeriksaan</dt>
                                <dd class="text-sm text-gray-900 font-mono font-semibold">#{{ $examination->id }}</dd>
                            </div>
                            <div class="py-3 flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Jenis Layanan</dt>
                                <dd class="text-sm text-gray-900 font-medium">{{ $examination->serviceItem->name ?: 'Tidak Tersedia' }}</dd>
                            </div>
                            <div class="py-3 flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Status Pemeriksaan</dt>
                                <dd>
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
                                </dd>
                            </div>
                            <div class="py-3 flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Tanggal & Waktu Terjadwal</dt>
                                <dd class="text-sm text-gray-900">
                                    {{ $examination->scheduled_date && $examination->scheduled_time ? \Carbon\Carbon::parse($examination->scheduled_date)->format('d/m/Y') . ' pukul ' . \Carbon\Carbon::parse($examination->scheduled_time)->format('H:i') : 'Belum Dijadwalkan' }}
                                </dd>
                            </div>
                            <div class="py-3 flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Dibuat Pada</dt>
                                <dd class="text-sm text-gray-900">{{ $examination->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div class="py-3 flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Terakhir Diperbarui</dt>
                                <dd class="text-sm text-gray-900">{{ $examination->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Second Row Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <!-- Payment Information Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-money-bill-wave text-indigo-600 text-sm"></i>
                            </div>
                            Informasi Pembayaran
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <dl class="divide-y divide-gray-200">
                            <div class="py-3 flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Status Pembayaran</dt>
                                <dd>
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
                                </dd>
                            </div>
                            <div class="py-3 flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Metode Pembayaran</dt>
                                <dd class="text-sm text-gray-900">{{ $examination->payment_method ?: 'Tidak Tersedia' }}</dd>
                            </div>
                            <div class="py-3 flex justify-between">
                                <dt class="text-sm font-medium text-gray-500">Total Pembayaran</dt>
                                <dd>
                                    @if ($examination->final_price)
                                        <span class="text-lg font-bold text-green-700">
                                            Rp {{ number_format($examination->final_price, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-900">Tidak Tersedia</span>
                                    @endif
                                </dd>
                            </div>
                            @if ($examination->pickup_requested)
                                <div class="py-3">
                                    <h4 class="text-sm font-semibold text-gray-800 mb-3 flex items-center">
                                        <i class="fas fa-truck text-orange-600 mr-2"></i>
                                        Informasi Penjemputan
                                    </h4>
                                    <dl class="space-y-2 text-sm ml-6">
                                        <div class="flex justify-between">
                                            <dt class="text-gray-500">Alamat Penjemputan</dt>
                                            <dd class="text-gray-900 text-right max-w-xs">{{ $examination->pickup_address ?: 'Tidak Tersedia' }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-gray-500">Waktu Penjemputan</dt>
                                            <dd class="text-gray-900">{{ $examination->pickup_time ? \Carbon\Carbon::parse($examination->pickup_time)->format('d/m/Y H:i') : 'Tidak Tersedia' }}</dd>
                                        </div>
                                        @if ($examination->pickup_location_map)
                                            <div class="text-right">
                                                <a href="{{ $examination->pickup_location_map }}" target="_blank" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                                    <i class="fas fa-map-marker-alt mr-1"></i> Lihat Peta
                                                </a>
                                            </div>
                                        @endif
                                    </dl>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Examination Results Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-file-medical-alt text-amber-600 text-sm"></i>
                            </div>
                            Hasil Pemeriksaan
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        @if ($examination->result_available && $examination->getFirstMedia('results'))
                            @php
                                $resultFile = $examination->getFirstMedia('results');
                            @endphp
                            {{-- {{ dd($resultFile->getUrl()) }} --}}
                            <dl class="divide-y divide-gray-200">
                                <div class="py-3 flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Status Hasil</dt>
                                    <dd>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Tersedia
                                        </span>
                                    </dd>
                                </div>
                                <div class="py-3 flex justify-between items-center">
                                    <dt class="text-sm font-medium text-gray-500">File Hasil</dt>
                                    <dd>
                                        <a href="{{ $resultFile->getUrl() }}" target="_blank"
                                            class="inline-flex items-center px-3 py-1 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 text-xs font-medium rounded-md transition-colors duration-200">
                                            <i class="fas fa-download mr-1"></i>Download PDF
                                        </a>
                                    </dd>
                                </div>
                                <div class="py-3 flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Ukuran File</dt>
                                    <dd class="text-sm text-gray-900">{{ $resultFile->human_readable_size }}</dd>
                                </div>
                                <div class="py-3 flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Upload</dt>
                                    <dd class="text-sm text-gray-900">{{ $resultFile->created_at->format('d/m/Y H:i') }}</dd>
                                </div>
                            </dl>
                        @else
                            <div class="text-center py-8">
                                <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-file-upload text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-sm font-medium text-gray-900 mb-2">Hasil Belum Tersedia</h3>
                                <p class="text-sm text-gray-500 mb-4">Hasil pemeriksaan belum diupload oleh petugas</p>
                                @if (auth()->user()->hasRole(['admin', 'perawat']))
                                    <a href="{{ route('staff.examinations.upload_result.form', $examination) }}"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                        <i class="fas fa-upload mr-2"></i>Upload Hasil
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Additional Information Section -->
            @if ($examination->notes || $examination->description || $examination->pickup_requested)
                <div class="mt-6">
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-gray-200">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-info-circle text-purple-600 text-sm"></i>
                                </div>
                                Informasi Tambahan
                            </h3>
                        </div>
                        <div class="px-6 py-6">
                            @if ($examination->description)
                                <div class="mb-6 pb-6 border-b border-gray-200 last:mb-0 last:pb-0 last:border-b-0">
                                    <dt class="text-sm font-medium text-gray-500 mb-2">Deskripsi Pemeriksaan</dt>
                                    <dd class="text-sm text-gray-900 bg-gray-50 rounded-lg p-4 leading-relaxed border border-gray-200">{{ $examination->description }}</dd>
                                </div>
                            @endif

                            @if ($examination->notes)
                                <div class="{{ $examination->description ? 'pt-6' : '' }} mb-0">
                                    <dt class="text-sm font-medium text-gray-500 mb-2">Catatan Khusus</dt>
                                    <dd class="text-sm text-gray-900 bg-blue-50 rounded-lg p-4 border-l-4 border-blue-400 text-blue-800 leading-relaxed">{{ $examination->notes }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-staff-layout>