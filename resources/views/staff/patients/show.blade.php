<x-staff-layout>
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Detail Pasien</h1>
            <p class="text-gray-600">Informasi lengkap data pasien</p>
        </div>

        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('staff.patients.index') }}"
                class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Pasien
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Patient Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Informasi Pasien</h2>
                        <div class="flex space-x-2">
                            <a href="{{ route('staff.patients.edit', $patient) }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('staff.patients.destroy', $patient) }}" method="POST" class="inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pasien ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors text-sm">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Nama Lengkap</label>
                                <p class="text-gray-900 font-medium">{{ $patient->name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                                <p class="text-gray-900">{{ $patient->email ?: '-' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Nomor Telepon</label>
                                <p class="text-gray-900">{{ $patient->phone_number ?: '-' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Umur</label>
                                <p class="text-gray-900">{{ $patient->age }} tahun</p>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Lahir</label>
                                <p class="text-gray-900">
                                    {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('d F Y') : '-' }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Jenis Kelamin</label>
                                <p class="text-gray-900">{{ $patient->gender ?: '-' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Status Registrasi</label>
                                <p class="text-gray-900">
                                    @if ($patient->user_id)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Terdaftar
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Belum Terdaftar
                                        </span>
                                    @endif
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Dibuat</label>
                                <p class="text-gray-900">{{ $patient->created_at->format('d F Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <label class="block text-sm font-medium text-gray-500 mb-2">Alamat</label>
                        <p class="text-gray-900">{{ $patient->address ?: 'Alamat belum diisi' }}</p>
                    </div>
                </div>
            </div>

            <!-- Examination History -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Pemeriksaan</h3>

                    @if ($patient->examinations && $patient->examinations->count() > 0)
                        <div class="space-y-3">
                            @foreach ($patient->examinations->take(5) as $examination)
                                <div class="p-3 border border-gray-200 rounded-md">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-900">
                                            Pemeriksaan #{{ $examination->id }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ $examination->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        {{ Str::limit($examination->serviceItem->name ?? 'Belum ada diagnosis', 50) }}
                                    </p>
                                </div>
                            @endforeach

                            @if ($patient->examinations->count() > 5)
                                <div class="text-center">
                                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Lihat Semua ({{ $patient->examinations->count() }} pemeriksaan)
                                    </button>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Belum ada riwayat pemeriksaan</p>
                        </div>
                    @endif
                </div>

                <!-- Quick Stats -->
                <div class="mt-6 bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik Cepat</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Pemeriksaan</span>
                            <span
                                class="font-semibold text-gray-900">{{ $patient->examinations ? $patient->examinations->count() : 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Pemeriksaan Terakhir</span>
                            <span class="font-semibold text-gray-900">
                                @if ($patient->examinations && $patient->examinations->count() > 0)
                                    {{ $patient->examinations->sortByDesc('created_at')->first()->created_at->diffForHumans() }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Menjadi Pasien Sejak</span>
                            <span
                                class="font-semibold text-gray-900">{{ $patient->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-staff-layout>
