<x-patient-layout>
    {{-- Header Section
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-white">Dashboard Pasien</h1>
                    <div class="text-white text-sm">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        {{ now()->format('d F Y') }}
                    </div>
                </div>
            </div>
        </div>
    </x-slot> --}}

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
                        @if ($recentExaminations && $recentExaminations->count() > 0)
                            <a href="{{ route('pasien.examinations.index') }}"
                                class="text-blue-600 hover:text-blue-800 font-semibold text-sm flex items-center">
                                Lihat Semua
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="p-6">
                    @if ($recentExaminations && $recentExaminations->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-4 px-4 font-semibold text-gray-700">Jenis Pemeriksaan
                                        </th>
                                        <th class="text-left py-4 px-4 font-semibold text-gray-700">Jadwal</th>
                                        <th class="text-left py-4 px-4 font-semibold text-gray-700">Status</th>
                                        <th class="text-left py-4 px-4 font-semibold text-gray-700">Pembayaran</th>
                                        <th class="text-left py-4 px-4 font-semibold text-gray-700">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($recentExaminations as $examination)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="py-4 px-4">
                                                <div class="font-semibold text-gray-900">
                                                    {{ $examination->serviceItem->name }}</div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="text-gray-900">
                                                    {{ \Carbon\Carbon::parse($examination->scheduled_date)->format('d M Y') }}
                                                </div>
                                                <div class="text-sm text-gray-600">
                                                    {{ \Carbon\Carbon::parse($examination->scheduled_time)->format('H:i') }}
                                                    WIB
                                                </div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                    @if ($examination->status == 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($examination->status == 'scheduled') bg-blue-100 text-blue-800
                                                    @elseif($examination->status == 'completed') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    @if ($examination->status == 'pending')
                                                        <i class="fas fa-clock mr-1"></i>
                                                    @elseif($examination->status == 'scheduled')
                                                        <i class="fas fa-calendar-check mr-1"></i>
                                                    @elseif($examination->status == 'completed')
                                                        <i class="fas fa-check mr-1"></i>
                                                    @else
                                                        <i class="fas fa-times mr-1"></i>
                                                    @endif
                                                    {{ ucfirst($examination->status) }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4">
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                    @if ($examination->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($examination->payment_status == 'paid') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    @if ($examination->payment_status == 'pending')
                                                        <i class="fas fa-hourglass-half mr-1"></i>
                                                    @elseif($examination->payment_status == 'paid')
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                    @else
                                                        <i class="fas fa-times-circle mr-1"></i>
                                                    @endif
                                                    {{ ucfirst($examination->payment_status) }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4">
                                                <div class="flex items-center space-x-3">
                                                    <a href="{{ route('pasien.examinations.show', $examination->id) }}"
                                                        class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                                        Detail
                                                    </a>
                                                    @if ($examination->result_available)
                                                        <a href="{{ route('pasien.result.download', $examination->id) }}"
                                                            class="text-green-600 hover:text-green-800 font-medium text-sm flex items-center">
                                                            <i class="fas fa-download mr-1"></i>
                                                            Hasil
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
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
