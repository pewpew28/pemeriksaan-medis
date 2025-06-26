<x-patient-layout>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Welcome Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                <div class="p-8">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">
                                Selamat datang, {{ $user->name }}
                            </h2>
                            <p class="text-lg text-gray-600 mb-6">
                                Kelola jadwal pemeriksaan dan akses layanan kesehatan Anda dengan mudah
                            </p>
                        </div>
                        <div class="hidden md:block">
                            <div
                                class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-md text-white text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    @if ($patient)
                        {{-- Patient Information Card --}}
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-100">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                <h3 class="text-xl font-semibold text-gray-900">Informasi Profil</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="flex items-center">
                                    <i class="fas fa-user text-blue-500 mr-3"></i>
                                    <div>
                                        <p class="text-sm text-gray-600">Nama Lengkap</p>
                                        <p class="font-semibold text-gray-900">{{ $patient->name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-envelope text-blue-500 mr-3"></i>
                                    <div>
                                        <p class="text-sm text-gray-600">Email</p>
                                        <p class="font-semibold text-gray-900">{{ $patient->email ?? 'Belum diisi' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-blue-500 mr-3"></i>
                                    <div>
                                        <p class="text-sm text-gray-600">No. Telepon</p>
                                        <p class="font-semibold text-gray-900">
                                            {{ $patient->phone_number ?? 'Belum diisi' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Profile Incomplete Alert --}}
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-6">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-amber-500 mr-3 mt-1"></i>
                                <div>
                                    <h3 class="text-lg font-semibold text-amber-800 mb-2">Profil Belum Lengkap</h3>
                                    <p class="text-amber-700 mb-4">
                                        Lengkapi profil Anda untuk dapat menggunakan layanan pemeriksaan kesehatan.
                                    </p>
                                    <a href="{{ route('pasien.profile') }}"
                                        class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors duration-200">
                                        <i class="fas fa-edit mr-2"></i>
                                        Lengkapi Profil
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Notifications --}}
            @if (session('status') || session('success') || session('error'))
                <div class="mb-8">
                    @if (session('status'))
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                                <p class="text-blue-800">{{ session('status') }}</p>
                            </div>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <p class="text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                                <p class="text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Recent Examinations Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-stethoscope text-blue-600 mr-3"></i>
                            <h2 class="text-2xl font-bold text-gray-900">Pemeriksaan Terbaru</h2>
                        </div>
                        {{-- Safe check for examinations --}}
                        @if (!empty($recentExaminations) && $recentExaminations->count() > 0)
                            <a href="{{ route('pasien.examinations.index') }}"
                                class="text-blue-600 hover:text-blue-800 font-semibold text-sm flex items-center transition-colors duration-200">
                                Lihat Semua
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="p-6">
                    @if (!empty($recentExaminations) && $recentExaminations->count() > 0)
                        <div class="space-y-4">
                            @foreach ($recentExaminations as $examination)
                                <div
                                    class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                    <div class="p-4">
                                        {{-- Header Card --}}
                                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-3">
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-900 text-lg">
                                                    {{ optional($examination->serviceItem)->name ?? 'Pemeriksaan Umum' }}
                                                </h3>
                                                @if ($examination->pickup_requested)
                                                    <div class="flex items-center text-xs text-blue-600 mt-1">
                                                        <i class="fas fa-car mr-1"></i>
                                                        <span>Dengan Penjemputan</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="mt-2 sm:mt-0 sm:ml-4">
                                                <div class="font-semibold text-gray-900 text-lg">
                                                    Rp {{ number_format($examination->final_price ?? 0, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Status Badges --}}
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            @php
                                                $statusConfig = [
                                                    'created' => [
                                                        'class' => 'bg-gray-100 text-gray-800',
                                                        'icon' => 'fas fa-plus-circle',
                                                        'label' => 'Dibuat',
                                                    ],
                                                    'pending_payment' => [
                                                        'class' => 'bg-yellow-100 text-yellow-800',
                                                        'icon' => 'fas fa-credit-card',
                                                        'label' => 'Menunggu Pembayaran',
                                                    ],
                                                    'pending_cash_payment' => [
                                                        'class' => 'bg-orange-100 text-orange-800',
                                                        'icon' => 'fas fa-money-bill',
                                                        'label' => 'Bayar di Klinik',
                                                    ],
                                                    'paid' => [
                                                        'class' => 'bg-green-100 text-green-800',
                                                        'icon' => 'fas fa-check-circle',
                                                        'label' => 'Lunas',
                                                    ],
                                                    'expired_payment' => [
                                                        'class' => 'bg-red-100 text-red-800',
                                                        'icon' => 'fas fa-exclamation-triangle',
                                                        'label' => 'Pembayaran Kadaluarsa',
                                                    ],
                                                    'scheduled' => [
                                                        'class' => 'bg-blue-100 text-blue-800',
                                                        'icon' => 'fas fa-calendar-check',
                                                        'label' => 'Terjadwal',
                                                    ],
                                                    'in_progress' => [
                                                        'class' => 'bg-purple-100 text-purple-800',
                                                        'icon' => 'fas fa-spinner',
                                                        'label' => 'Sedang Berlangsung',
                                                    ],
                                                    'completed' => [
                                                        'class' => 'bg-green-100 text-green-800',
                                                        'icon' => 'fas fa-check',
                                                        'label' => 'Selesai',
                                                    ],
                                                    'cancelled' => [
                                                        'class' => 'bg-red-100 text-red-800',
                                                        'icon' => 'fas fa-times',
                                                        'label' => 'Dibatalkan',
                                                    ],
                                                ];
                                                $currentStatus =
                                                    $statusConfig[$examination->status] ?? $statusConfig['created'];

                                                $paymentStatusConfig = [
                                                    'pending' => [
                                                        'class' => 'bg-yellow-100 text-yellow-800',
                                                        'icon' => 'fas fa-hourglass-half',
                                                        'label' => 'Menunggu',
                                                    ],
                                                    'paid' => [
                                                        'class' => 'bg-green-100 text-green-800',
                                                        'icon' => 'fas fa-check-circle',
                                                        'label' => 'Lunas',
                                                    ],
                                                    'failed' => [
                                                        'class' => 'bg-red-100 text-red-800',
                                                        'icon' => 'fas fa-times-circle',
                                                        'label' => 'Gagal',
                                                    ],
                                                ];
                                                $currentPaymentStatus =
                                                    $paymentStatusConfig[$examination->payment_status] ??
                                                    $paymentStatusConfig['pending'];
                                            @endphp

                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $currentStatus['class'] }}">
                                                <i class="{{ $currentStatus['icon'] }} mr-1"></i>
                                                {{ $currentStatus['label'] }}
                                            </span>

                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $currentPaymentStatus['class'] }}">
                                                <i class="{{ $currentPaymentStatus['icon'] }} mr-1"></i>
                                                {{ $currentPaymentStatus['label'] }}
                                            </span>
                                        </div>

                                        {{-- Schedule Info --}}
                                        <div class="flex items-center text-sm text-gray-600 mb-4">
                                            <i class="fas fa-calendar-alt mr-2"></i>
                                            @if ($examination->scheduled_date)
                                                <div>
                                                    <span class="font-medium">
                                                        {{ \Carbon\Carbon::parse($examination->scheduled_date)->format('d M Y') }}
                                                    </span>
                                                    @if ($examination->scheduled_time)
                                                        <span class="ml-2">
                                                            {{ \Carbon\Carbon::parse($examination->scheduled_time)->format('H:i') }}
                                                            WIB
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-500">Belum Dijadwalkan</span>
                                            @endif
                                        </div>

                                        {{-- Payment Method --}}
                                        @if ($examination->payment_method)
                                            <div class="flex items-center text-sm text-gray-600 mb-4">
                                                <i class="fas fa-credit-card mr-2"></i>
                                                <span>{{ $examination->payment_method }}</span>
                                            </div>
                                        @endif

                                        {{-- Action Buttons --}}
                                        <div class="flex flex-wrap gap-2 pt-3 border-t border-gray-100">
                                            <a href="{{ route('pasien.examinations.show', $examination->id) }}"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                                <i class="fas fa-eye mr-2"></i>
                                                Detail
                                            </a>

                                            @if ($examination->result_available)
                                                <a href="{{ route('pasien.result.download', $examination->id) }}"
                                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors duration-200">
                                                    <i class="fas fa-download mr-2"></i>
                                                    Unduh Hasil
                                                </a>
                                            @endif

                                            @if (in_array($examination->status, ['created', 'pending_payment', 'pending_cash_payment']) &&
                                                    $examination->payment_status === 'pending')
                                                <a href="{{ route('pasien.payment.show', $examination->id) }}"
                                                    class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors duration-200">
                                                    <i class="fas fa-credit-card mr-2"></i>
                                                    Bayar Sekarang
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Empty state when no examinations --}}
                        <div class="text-center py-12">
                            <div class="max-w-md mx-auto">
                                <i class="fas fa-clipboard-list text-gray-400 text-5xl mb-4"></i>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Pemeriksaan</h3>
                                <p class="text-gray-600 mb-6">Anda belum memiliki riwayat pemeriksaan. Mulai daftar
                                    pemeriksaan pertama Anda.</p>
                                <a href="{{ route('pasien.examination.register.form') }}"
                                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                    <i class="fas fa-plus mr-2"></i>
                                    Daftar Pemeriksaan
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quick Actions Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <i class="fas fa-bolt text-blue-600 mr-3"></i>
                        <h2 class="text-2xl font-bold text-gray-900">Aksi Cepat</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <a href="{{ route('pasien.examination.register.form') }}"
                            class="group bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-xl text-white hover:from-blue-600 hover:to-blue-700 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                            <div class="flex items-center justify-between mb-4">
                                <i class="fas fa-plus-circle text-3xl opacity-80"></i>
                                <i
                                    class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Daftar Pemeriksaan</h3>
                            <p class="text-blue-100">Buat jadwal pemeriksaan kesehatan baru</p>
                        </a>

                        <a href="{{ route('pasien.examinations.index') }}"
                            class="group bg-gradient-to-br from-green-500 to-green-600 p-6 rounded-xl text-white hover:from-green-600 hover:to-green-700 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                            <div class="flex items-center justify-between mb-4">
                                <i class="fas fa-history text-3xl opacity-80"></i>
                                <i
                                    class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Riwayat Pemeriksaan</h3>
                            <p class="text-green-100">Lihat semua riwayat pemeriksaan Anda</p>
                        </a>

                        <a href="{{ route('pasien.profile') }}"
                            class="group bg-gradient-to-br from-indigo-500 to-indigo-600 p-6 rounded-xl text-white hover:from-indigo-600 hover:to-indigo-700 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                            <div class="flex items-center justify-between mb-4">
                                <i class="fas fa-user-edit text-3xl opacity-80"></i>
                                <i
                                    class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Edit Profil</h3>
                            <p class="text-indigo-100">Perbarui informasi profil Anda</p>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Contact Information Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <i class="fas fa-headset text-blue-600 mr-3"></i>
                        <h2 class="text-2xl font-bold text-gray-900">Pusat Bantuan</h2>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Hubungi Kami</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-phone text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">(021) 123-4567</p>
                                        <p class="text-gray-600 text-sm">Layanan 24 jam</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div
                                        class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-envelope text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">info@klinikmedis.com</p>
                                        <p class="text-gray-600 text-sm">Respon dalam 24 jam</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Jam Operasional</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-700">Senin - Jumat</span>
                                    <span class="font-semibold text-gray-900">08:00 - 17:00</span>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-700">Sabtu</span>
                                    <span class="font-semibold text-gray-900">08:00 - 14:00</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700">Minggu</span>
                                    <span class="font-semibold text-red-600">Tutup</span>
                                </div>
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Layanan darurat 24 jam tersedia
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-patient-layout>
