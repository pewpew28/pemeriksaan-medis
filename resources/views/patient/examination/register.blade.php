<x-patient-layout>
    {{-- Slot header untuk layout, jika ada --}}
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">{{ __('Daftar Pemeriksaan Baru') }}</h1>
                        <p class="text-blue-100 text-sm">Ajukan permintaan pemeriksaan medis dengan mudah dan cepat</p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold">
                            1
                        </div>
                        <span class="text-sm font-medium text-blue-600">Isi Data</span>
                    </div>
                    <div class="w-16 h-1 bg-gray-200 rounded"></div>
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-10 h-10 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-semibold">
                            2
                        </div>
                        <span class="text-sm font-medium text-gray-500">Konfirmasi</span>
                    </div>
                    <div class="w-16 h-1 bg-gray-200 rounded"></div>
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-10 h-10 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-semibold">
                            3
                        </div>
                        <span class="text-sm font-medium text-gray-500">Selesai</span>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Ajukan Permintaan Pemeriksaan Medis</h2>
                            <p class="text-sm text-gray-600 mt-1">Lengkapi formulir di bawah ini untuk mengajukan
                                pemeriksaan</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    {{-- Alert Messages --}}
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded-r-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-red-800 font-medium mb-2">Terjadi kesalahan:</p>
                                    <ul class="list-disc list-inside text-red-700 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li class="text-sm">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (!$patient)
                        <div class="mb-8 p-6 bg-amber-50 border-l-4 border-amber-400 rounded-r-lg">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-amber-800 mb-2">Profil Belum Lengkap</h3>
                                    <p class="text-amber-700 mb-4">Untuk dapat mengajukan pemeriksaan, Anda perlu
                                        melengkapi data profil pasien terlebih dahulu.</p>
                                    <a href="{{ route('pasien.profile') }}"
                                        class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition duration-200 ease-in-out transform hover:scale-105">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Lengkapi Profil Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <form method="POST" action="{{ route('pasien.examination.register.store') }}"
                            class="space-y-8">
                            @csrf

                            {{-- Patient Information Section --}}
                            <div
                                class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Informasi Pasien</h3>
                                </div>

                                <div class="grid md:grid-cols-3 gap-4">
                                    <div class="bg-white rounded-lg p-4 border border-blue-200">
                                        <dt class="text-sm font-medium text-gray-500 mb-1">Nama Lengkap</dt>
                                        <dd class="text-base font-semibold text-gray-900">{{ $patient->name }}</dd>
                                    </div>
                                    <div class="bg-white rounded-lg p-4 border border-blue-200">
                                        <dt class="text-sm font-medium text-gray-500 mb-1">Email</dt>
                                        <dd class="text-base font-semibold text-gray-900">
                                            {{ $patient->email ?? 'Tidak tersedia' }}</dd>
                                    </div>
                                    <div class="bg-white rounded-lg p-4 border border-blue-200">
                                        <dt class="text-sm font-medium text-gray-500 mb-1">No. Telepon</dt>
                                        <dd class="text-base font-semibold text-gray-900">
                                            {{ $patient->phone_number ?? 'Tidak tersedia' }}</dd>
                                    </div>
                                </div>

                                <div class="mt-4 p-3 bg-blue-100 rounded-lg">
                                    <p class="text-sm text-blue-800">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Pastikan data di atas sudah benar. Jika perlu diubah, silakan
                                        <a href="{{ route('pasien.profile') }}"
                                            class="text-blue-600 hover:text-blue-800 font-semibold underline">ubah
                                            profil pasien Anda</a>.
                                    </p>
                                </div>
                            </div>

                            {{-- Examination Details Section --}}
                            <div class="space-y-6">
                                <div class="flex items-center space-x-3 mb-6">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Detail Pemeriksaan</h3>
                                </div>

                                <div class="form-group">
                                    <label for="service_item_id"
                                        class="block text-sm font-semibold text-gray-700 mb-3">
                                        Pilih Jenis Pemeriksaan <span class="text-red-500">*</span>
                                    </label>

                                    @if ($serviceItems && $serviceItems->count() > 0)
                                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                                            @foreach ($serviceItems as $service)
                                                <label class="relative cursor-pointer">
                                                    <input type="radio" name="service_item_id"
                                                        value="{{ $service->id }}" class="sr-only peer"
                                                        {{ old('service_item_id') == $service->id ? 'checked' : '' }}
                                                        onchange="updateSelectedService({{ $service->id }}, '{{ $service->name }}', {{ $service->price }})">

                                                    <div
                                                        class="bg-white border-2 border-gray-200 rounded-xl p-4 hover:border-blue-300 hover:shadow-md transition-all duration-200 peer-checked:border-blue-600 peer-checked:ring-2 peer-checked:ring-blue-200 peer-checked:bg-blue-50">
                                                        <div class="flex items-start justify-between mb-2">
                                                            <h4 class="font-semibold text-gray-900 text-sm">
                                                                {{ $service->name }}</h4>
                                                            <div
                                                                class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-blue-600 peer-checked:bg-blue-600 flex items-center justify-center transition-all">
                                                                <div
                                                                    class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @if ($service->description)
                                                            <p class="text-xs text-gray-600 mb-3 line-clamp-2">
                                                                {{ $service->description }}</p>
                                                        @endif

                                                        <div class="flex items-center justify-between">
                                                            <span class="text-lg font-bold text-blue-600">
                                                                Rp {{ number_format($service->price, 0, ',', '.') }}
                                                            </span>
                                                            <span
                                                                class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                                                Aktif
                                                            </span>
                                                        </div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>

                                        <!-- Selected Service Summary -->
                                        <div id="selected_service_summary"
                                            class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4 class="font-semibold text-blue-900">Layanan Dipilih:</h4>
                                                    <p class="text-sm text-blue-800" id="selected_service_name">-</p>
                                                    <p class="text-lg font-bold text-blue-900"
                                                        id="selected_service_price">-</p>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="p-6 bg-gray-50 border border-gray-200 rounded-lg text-center">
                                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Layanan
                                                Tersedia</h3>
                                            <p class="text-gray-600 text-sm">Saat ini belum ada layanan pemeriksaan
                                                yang tersedia. Silakan hubungi admin untuk informasi lebih lanjut.</p>
                                        </div>
                                    @endif

                                    @error('service_item_id')
                                        <p class="text-red-600 text-sm mt-2 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Schedule Section --}}
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label for="scheduled_date"
                                            class="block text-sm font-semibold text-gray-700 mb-2">
                                            Tanggal Jadwal <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="date" name="scheduled_date" id="scheduled_date"
                                                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('scheduled_date') border-red-500 ring-2 ring-red-200 @enderror"
                                                value="{{ old('scheduled_date') }}" required
                                                min="{{ now()->format('Y-m-d') }}">
                                            <div
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        @error('scheduled_date')
                                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="scheduled_time"
                                            class="block text-sm font-semibold text-gray-700 mb-2">
                                            Waktu Jadwal <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="time" name="scheduled_time" id="scheduled_time"
                                                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('scheduled_time') border-red-500 ring-2 ring-red-200 @enderror"
                                                value="{{ old('scheduled_time') }}" required>
                                            <div
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        @error('scheduled_time')
                                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Pickup Request Section --}}
                            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Layanan Penjemputan</h3>
                                </div>

                                <div class="mb-6">
                                    <label
                                        class="flex items-start space-x-3 cursor-pointer p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-400 hover:bg-purple-50 transition duration-200">
                                        <input type="checkbox" name="pickup_requested" id="pickup_requested"
                                            value="1"
                                            class="mt-1 rounded border-gray-300 text-purple-600 shadow-sm focus:ring-purple-500 h-5 w-5"
                                            {{ old('pickup_requested') ? 'checked' : '' }}
                                            onchange="togglePickupFields()">
                                        <div class="flex-1">
                                            <span class="text-base font-medium text-gray-900">Request Penjemputan
                                                Sampel/Pasien</span>
                                            <p class="text-sm text-gray-600 mt-1">Kami akan mengirim petugas untuk
                                                menjemput sampel atau mendampingi Anda ke fasilitas kesehatan</p>
                                        </div>
                                    </label>
                                    @error('pickup_requested')
                                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Pickup Details --}}
                                <div id="pickup_fields"
                                    class="space-y-6 p-6 bg-white rounded-xl border-2 border-purple-200"
                                    style="display: {{ old('pickup_requested') ? 'block' : 'none' }};">
                                    <div class="flex items-center space-x-2 mb-4">
                                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                                        <h4 class="text-lg font-semibold text-purple-800">Detail Penjemputan</h4>
                                    </div>

                                    <div class="form-group">
                                        <label for="pickup_address"
                                            class="block text-sm font-semibold text-gray-700 mb-2">
                                            Alamat Penjemputan <span class="text-red-500">*</span>
                                        </label>
                                        <textarea name="pickup_address" id="pickup_address" rows="4"
                                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200 @error('pickup_address') border-red-500 ring-2 ring-red-200 @enderror"
                                            placeholder="Masukkan alamat lengkap untuk penjemputan...">{{ old('pickup_address') }}</textarea>
                                        @error('pickup_address')
                                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="pickup_location_map"
                                            class="block text-sm font-semibold text-gray-700 mb-2">
                                            Link Lokasi Peta (Opsional)
                                        </label>
                                        <div class="relative">
                                            <input type="url" name="pickup_location_map" id="pickup_location_map"
                                                class="mt-1 block w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200 @error('pickup_location_map') border-red-500 ring-2 ring-red-200 @enderror"
                                                value="{{ old('pickup_location_map') }}"
                                                placeholder="https://maps.app.goo.gl/abcdef123">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">Link Google Maps, Waze, atau aplikasi
                                            peta lainnya</p>
                                        @error('pickup_location_map')
                                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="pickup_time"
                                            class="block text-sm font-semibold text-gray-700 mb-2">
                                            Estimasi Waktu Penjemputan <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="time" name="pickup_time" id="pickup_time"
                                                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200 @error('pickup_time') border-red-500 ring-2 ring-red-200 @enderror"
                                                value="{{ old('pickup_time') }}">
                                            <div
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="mt-2 p-3 bg-purple-50 rounded-lg">
                                            <p class="text-xs text-purple-700 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Estimasi waktu penjemputan, staf kami akan mengkonfirmasi jadwal yang
                                                tepat
                                            </p>
                                        </div>
                                        @error('pickup_time')
                                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div
                                class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-6 border-t border-gray-200">
                                <div class="mb-4 sm:mb-0">
                                    <p class="text-sm text-gray-600 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Semua field bertanda <span class="text-red-500 font-semibold">*</span> wajib
                                        diisi
                                    </p>
                                </div>
                                <div class="flex space-x-4">
                                    <button type="button" onclick="window.history.back()"
                                        class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 ease-in-out">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                                        </svg>
                                        Kembali
                                    </button>
                                    <button type="submit"
                                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 ease-in-out transform hover:scale-105">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Ajukan Pendaftaran
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Help Section --}}
            <div class="mt-8 bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Butuh Bantuan?</h3>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <h4 class="font-semibold text-gray-800">Informasi Penting:</h4>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-start space-x-2">
                                <div class="w-1.5 h-1.5 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                <span>Pastikan jadwal yang dipilih sesuai dengan ketersediaan Anda</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <div class="w-1.5 h-1.5 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                <span>Kami akan mengkonfirmasi jadwal dalam 1x24 jam</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <div class="w-1.5 h-1.5 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                <span>Layanan penjemputan tersedia untuk area tertentu</span>
                            </li>
                        </ul>
                    </div>
                    <div class="space-y-3">
                        <h4 class="font-semibold text-gray-800">Hubungi Kami:</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center space-x-2 text-gray-600">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z">
                                    </path>
                                </svg>
                                <span>Telepon: (021) 1234-5678</span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-600">
                                <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                    </path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                <span>Email: info@klinik.com</span>
                            </div>
                            <div class="flex items-center space-x-2 text-gray-600">
                                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>WhatsApp: +62 812-3456-7890</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function togglePickupFields() {
                const pickupRequestedCheckbox = document.getElementById('pickup_requested');
                const pickupFieldsDiv = document.getElementById('pickup_fields');
                const pickupAddressInput = document.getElementById('pickup_address');
                const pickupLocationMapInput = document.getElementById('pickup_location_map');
                const pickupTimeInput = document.getElementById('pickup_time');

                if (pickupRequestedCheckbox.checked) {
                    pickupFieldsDiv.style.display = 'block';
                    pickupFieldsDiv.style.opacity = '0';
                    pickupFieldsDiv.style.transform = 'translateY(-10px)';

                    // Animate in
                    setTimeout(() => {
                        pickupFieldsDiv.style.transition = 'all 0.3s ease-in-out';
                        pickupFieldsDiv.style.opacity = '1';
                        pickupFieldsDiv.style.transform = 'translateY(0)';
                    }, 10);

                    pickupAddressInput.setAttribute('required', 'required');
                    pickupTimeInput.setAttribute('required', 'required');

                    // Focus on first field
                    setTimeout(() => {
                        pickupAddressInput.focus();
                    }, 300);
                } else {
                    pickupFieldsDiv.style.transition = 'all 0.3s ease-in-out';
                    pickupFieldsDiv.style.opacity = '0';
                    pickupFieldsDiv.style.transform = 'translateY(-10px)';

                    setTimeout(() => {
                        pickupFieldsDiv.style.display = 'none';
                    }, 300);

                    pickupAddressInput.removeAttribute('required');
                    pickupTimeInput.removeAttribute('required');
                }
            }

            // Enhanced form validation
            function validateForm() {
                const form = document.querySelector('form');
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('border-red-500', 'ring-2', 'ring-red-200');
                        isValid = false;
                    } else {
                        field.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
                    }
                });

                return isValid;
            }

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                togglePickupFields();

                // Add smooth transitions to form elements
                const formElements = document.querySelectorAll('input, select, textarea');
                formElements.forEach(element => {
                    element.addEventListener('focus', function() {
                        this.classList.add('ring-2', 'ring-blue-200');
                    });

                    element.addEventListener('blur', function() {
                        this.classList.remove('ring-2', 'ring-blue-200');
                    });
                });

                // Form submission with loading state
                const submitButton = document.querySelector('button[type="submit"]');
                const form = document.querySelector('form');

                if (form && submitButton) {
                    form.addEventListener('submit', function(e) {
                        if (!validateForm()) {
                            e.preventDefault();
                            return;
                        }

                        submitButton.disabled = true;
                        submitButton.innerHTML = `
                            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses...
                        `;
                    });
                }
            });

            // Auto-resize textarea
            const textarea = document.getElementById('pickup_address');
            if (textarea) {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = this.scrollHeight + 'px';
                });
            }
        </script>
    @endpush
</x-patient-layout>
