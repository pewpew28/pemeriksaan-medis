<x-patient-layout>
    {{-- Header --}}
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
            {{-- Progress Steps --}}
            <div class="mb-8">
                <div class="flex items-center justify-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold">
                            1</div>
                        <span class="text-sm font-medium text-blue-600">Isi Data</span>
                    </div>
                    <div class="w-16 h-1 bg-gray-200 rounded"></div>
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-10 h-10 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-semibold">
                            2</div>
                        <span class="text-sm font-medium text-gray-500">Konfirmasi</span>
                    </div>
                    <div class="w-16 h-1 bg-gray-200 rounded"></div>
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-10 h-10 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center font-semibold">
                            3</div>
                        <span class="text-sm font-medium text-gray-500">Selesai</span>
                    </div>
                </div>
            </div>

            {{-- Main Form Card --}}
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
                                        class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition duration-200">
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

                            {{-- Service Selection Section - Simplified with Dropdowns --}}
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
                                    <h3 class="text-lg font-semibold text-gray-900">Pilih Layanan Pemeriksaan</h3>
                                </div>

                                <div class="grid md:grid-cols-2 gap-6">
                                    {{-- Service Category Dropdown --}}
                                    <div class="form-group">
                                        <label for="service_category"
                                            class="block text-sm font-semibold text-gray-700 mb-2">
                                            Kategori Layanan <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <select name="service_category" id="service_category"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 @error('service_category') border-red-500 ring-2 ring-red-200 @enderror"
                                                required onchange="loadServiceItems(this.value)">
                                                <option value="">Pilih Kategori Layanan</option>
                                                @if ($serviceCategories && $serviceCategories->count() > 0)
                                                    @foreach ($serviceCategories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ old('service_category') == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                            ({{ $category->serviceItems->count() }} layanan)
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        @error('service_category')
                                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Service Item Dropdown --}}
                                    <div class="form-group">
                                        <label for="service_item_id"
                                            class="block text-sm font-semibold text-gray-700 mb-2">
                                            Jenis Pemeriksaan <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <select name="service_item_id" id="service_item_id"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('service_item_id') border-red-500 ring-2 ring-red-200 @enderror"
                                                required onchange="updateServiceSummary()" disabled>
                                                <option value="">Pilih kategori terlebih dahulu</option>
                                            </select>
                                            <div
                                                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        @error('service_item_id')
                                            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Selected Service Summary --}}
                                <div id="selected_service_summary"
                                    class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
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
                                            <p class="text-lg font-bold text-blue-900" id="selected_service_price">-
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Schedule Section --}}
                            <div class="space-y-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Jadwal Pemeriksaan</h3>
                                </div>

                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label for="scheduled_date"
                                            class="block text-sm font-semibold text-gray-700 mb-2">
                                            Tanggal Jadwal <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="date" name="scheduled_date" id="scheduled_date"
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 @error('scheduled_date') border-red-500 ring-2 ring-red-200 @enderror"
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
                                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 @error('scheduled_time') border-red-500 ring-2 ring-red-200 @enderror"
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
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Layanan Jemput Pasien</h3>
                                        <p class="text-sm text-gray-600">Opsional - Kami dapat menjemput Anda di lokasi
                                            yang diinginkan</p>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="flex items-center space-x-3">
                                        <input type="checkbox" name="pickup_request" id="pickup_request"
                                            value="1"
                                            class="w-5 h-5 text-purple-600 border-2 border-gray-300 rounded focus:ring-purple-500 focus:ring-2"
                                            {{ old('pickup_request') ? 'checked' : '' }}
                                            onchange="togglePickupFields()">
                                        <label for="pickup_request" class="text-sm font-medium text-gray-700">
                                            Ya, saya memerlukan layanan jemput pasien
                                        </label>
                                    </div>

                                    <div id="pickup_fields" class="space-y-4" style="display: none;">
                                        <div class="bg-white rounded-lg p-4 border border-purple-200">
                                            <div class="form-group">
                                                <label for="pickup_address"
                                                    class="block text-sm font-semibold text-gray-700 mb-2">
                                                    Alamat Jemput <span class="text-red-500">*</span>
                                                </label>
                                                <textarea name="pickup_address" id="pickup_address" rows="3"
                                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200"
                                                    placeholder="Masukkan alamat lengkap untuk penjemputan...">{{ old('pickup_address') }}</textarea>
                                            </div>

                                            <div class="grid md:grid-cols-2 gap-4 mt-4">
                                                <div class="form-group">
                                                    <label for="pickup_time"
                                                        class="block text-sm font-semibold text-gray-700 mb-2">Waktu
                                                        Jemput</label>
                                                    <input type="time" name="pickup_time" id="pickup_time"
                                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200"
                                                        value="{{ old('pickup_time') }}">
                                                </div>

                                                <div class="form-group">
                                                    <label for="pickup_location_map"
                                                        class="block text-sm font-semibold text-gray-700 mb-2">Maps</label>
                                                    <input type="tel" name="pickup_location_map" id="pickup_location_map"
                                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200"
                                                        placeholder="Link gmaps untuk penjemputan"
                                                        value="{{ old('pickup_location_map') }}">
                                                </div>
                                            </div>

                                            <div class="mt-4 p-3 bg-purple-50 rounded-lg">
                                                <p class="text-sm text-purple-700">
                                                    <svg class="w-4 h-4 inline mr-1" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    Biaya tambahan mungkin berlaku untuk layanan jemput. Tim kami akan
                                                    menghubungi Anda untuk konfirmasi biaya dan detail.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Additional Notes Section --}}
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-1l-4 4z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Catatan Tambahan</h3>
                                        <p class="text-sm text-gray-600">Informasi tambahan yang perlu kami ketahui</p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Keluhan atau Catatan Khusus
                                    </label>
                                    <textarea name="notes" id="notes" rows="4"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition duration-200 @error('notes') border-red-500 ring-2 ring-red-200 @enderror"
                                        placeholder="Jelaskan keluhan Anda atau informasi khusus yang perlu kami ketahui sebelum pemeriksaan...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Submit Buttons --}}
                            <div class="flex flex-col sm:flex-row gap-4 pt-6">
                                <button type="button" onclick="window.history.back()"
                                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </button>

                                <button type="submit"
                                    class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold px-6 py-3 rounded-lg shadow-lg focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 transform hover:scale-105">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Ajukan Pemeriksaan
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Information Cards --}}
            <div class="mt-8 grid md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900">Jadwal Fleksibel</h4>
                    </div>
                    <p class="text-sm text-gray-600">Pilih waktu yang sesuai dengan jadwal Anda. Kami melayani dari
                        Senin hingga Sabtu.</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900">Terjamin Aman</h4>
                    </div>
                    <p class="text-sm text-gray-600">Tim medis profesional dengan protokol keamanan dan kebersihan yang
                        ketat.</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900">Dukungan 24/7</h4>
                    </div>
                    <p class="text-sm text-gray-600">Tim customer service siap membantu Anda kapan saja jika ada
                        pertanyaan.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript for Dynamic Functionality --}}
    <script>
        // Service data for dynamic loading
        @php
            $serviceDataJs = [];
            if ($serviceCategories) {
                foreach ($serviceCategories as $category) {
                    $serviceDataJs[$category->id] = $category->serviceItems
                        ->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'name' => $item->name,
                                'price' => $item->price,
                                'formatted_price' => 'Rp ' . number_format($item->price, 0, ',', '.'),
                            ];
                        })
                        ->toArray();
                }
            }
        @endphp
        const serviceData = @json($serviceDataJs);

        function loadServiceItems(categoryId) {
            const serviceItemSelect = document.getElementById('service_item_id');
            const selectedServiceSummary = document.getElementById('selected_service_summary');

            // Clear existing options
            serviceItemSelect.innerHTML = '<option value="">Pilih jenis pemeriksaan</option>';
            selectedServiceSummary.classList.add('hidden');

            if (categoryId && serviceData[categoryId]) {
                serviceItemSelect.disabled = false;

                serviceData[categoryId].forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = `${item.name} - ${item.formatted_price}`;
                    option.dataset.name = item.name;
                    option.dataset.price = item.formatted_price;
                    serviceItemSelect.appendChild(option);
                });
            } else {
                serviceItemSelect.disabled = true;
            }
        }

        function updateServiceSummary() {
            const serviceItemSelect = document.getElementById('service_item_id');
            const selectedServiceSummary = document.getElementById('selected_service_summary');
            const serviceNameEl = document.getElementById('selected_service_name');
            const servicePriceEl = document.getElementById('selected_service_price');

            if (serviceItemSelect.value) {
                const selectedOption = serviceItemSelect.options[serviceItemSelect.selectedIndex];
                serviceNameEl.textContent = selectedOption.dataset.name;
                servicePriceEl.textContent = selectedOption.dataset.price;
                selectedServiceSummary.classList.remove('hidden');
            } else {
                selectedServiceSummary.classList.add('hidden');
            }
        }

        function togglePickupFields() {
            const pickupCheckbox = document.getElementById('pickup_request');
            const pickupFields = document.getElementById('pickup_fields');
            const pickupAddress = document.getElementById('pickup_address');

            if (pickupCheckbox.checked) {
                pickupFields.style.display = 'block';
                pickupAddress.required = true;
            } else {
                pickupFields.style.display = 'none';
                pickupAddress.required = false;
            }
        }

        // Initialize form state
        document.addEventListener('DOMContentLoaded', function() {
            // Load service items if category is already selected
            const categorySelect = document.getElementById('service_category');
            if (categorySelect.value) {
                loadServiceItems(categorySelect.value);

                // Restore selected service item if exists
                const serviceItemSelect = document.getElementById('service_item_id');
                const oldServiceItem = '{{ old('service_item_id') }}';
                if (oldServiceItem) {
                    setTimeout(() => {
                        serviceItemSelect.value = oldServiceItem;
                        updateServiceSummary();
                    }, 100);
                }
            }

            // Initialize pickup fields visibility
            togglePickupFields();
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const termsAccepted = document.getElementById('terms_accepted').checked;
            const serviceItemSelected = document.getElementById('service_item_id').value;

            if (!termsAccepted) {
                e.preventDefault();
                alert('Anda harus menyetujui syarat dan ketentuan untuk melanjutkan.');
                return false;
            }

            if (!serviceItemSelected) {
                e.preventDefault();
                alert('Silakan pilih jenis pemeriksaan terlebih dahulu.');
                return false;
            }
        });
    </script>
</x-patient-layout>
