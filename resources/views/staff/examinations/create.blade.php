<x-staff-layout>
    <x-slot name="title">Buat Pemeriksaan Baru</x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Buat Pemeriksaan Baru</h1>
                        <nav class="flex" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                                <li>
                                    <a href="{{ route('staff.dashboard') }}"
                                        class="hover:text-blue-600 transition-colors">
                                        Dashboard
                                    </a>
                                </li>
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <a href="{{ route('staff.examinations.index') }}"
                                        class="hover:text-blue-600 transition-colors">
                                        Pemeriksaan
                                    </a>
                                </li>
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-gray-700 font-medium">Buat Baru</span>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <svg class="h-6 w-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Form Pemeriksaan Baru
                    </h2>
                </div>

                <!-- Card Body -->
                <div class="p-6">
                    <!-- Error Alert -->
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-red-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <h3 class="text-sm font-medium text-red-800 mb-2">Terdapat kesalahan:</h3>
                                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Form -->
                    <form action="{{ route('staff.examinations.store') }}" method="POST" id="examinationForm"
                        class="space-y-6">
                        @csrf

                        <!-- Patient and Service Information -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Patient Selection with Search -->
                            <div class="space-y-2">
                                <label for="patient_search" class="block text-sm font-medium text-gray-700">
                                    <svg class="inline h-4 w-4 mr-1 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Pasien <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text"
                                        class="block w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        id="patient_search" placeholder="Cari nama pasien atau nomor telepon..."
                                        autocomplete="off">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <div id="patient_dropdown"
                                        class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto hidden">
                                        <!-- Patient options will be populated here -->
                                    </div>
                                </div>
                                <input type="hidden" id="patient_id" name="patient_id" value="{{ old('patient_id') }}"
                                    required>
                                <p class="text-xs text-gray-500">Ketik untuk mencari pasien berdasarkan nama atau nomor
                                    telepon</p>
                            </div>

                            <!-- Service Category with Search -->
                            <div class="space-y-2">
                                <label for="service_category_search" class="block text-sm font-medium text-gray-700">
                                    <svg class="inline h-4 w-4 mr-1 text-blue-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                    Kategori Layanan <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text"
                                        class="block w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        id="service_category_search" placeholder="Cari kategori layanan..."
                                        autocomplete="off">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <div id="category_dropdown"
                                        class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto hidden">
                                        <!-- Category options will be populated here -->
                                    </div>
                                </div>
                                <input type="hidden" id="service_category" name="service_category"
                                    value="{{ old('service_category') }}">
                            </div>

                            <!-- Service Item with Search -->
                            <div class="space-y-2">
                                <label for="service_item_search" class="block text-sm font-medium text-gray-700">
                                    <svg class="inline h-4 w-4 mr-1 text-blue-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                    Jenis Pemeriksaan <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text"
                                        class="block w-full px-3 py-2 pr-10 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        id="service_item_search" placeholder="Pilih kategori terlebih dahulu..."
                                        autocomplete="off" disabled>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <div id="service_item_dropdown"
                                        class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto hidden">
                                        <!-- Service item options will be populated here -->
                                    </div>
                                </div>
                                <input type="hidden" id="service_item_id" name="service_item_id"
                                    value="{{ old('service_item_id') }}" required>
                                <p class="text-xs text-gray-500">Pilih jenis pemeriksaan yang akan dilakukan</p>
                            </div>

                            <!-- Price Display -->
                            <div class="space-y-2">
                                <label for="final_price" class="block text-sm font-medium text-gray-700">
                                    <svg class="inline h-4 w-4 mr-1 text-blue-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                    Harga <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500 text-sm">Rp</span>
                                    <input type="number"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 transition-colors"
                                        id="final_price" name="final_price" value="{{ old('final_price') }}"
                                        min="0" step="0.01" required readonly>
                                </div>
                                <p class="text-xs text-gray-500">Harga akan diisi otomatis sesuai jenis pemeriksaan</p>
                            </div>
                        </div>

                        <!-- Schedule Information -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <svg class="h-5 w-5 mr-2 text-blue-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Jadwal Pemeriksaan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Schedule Date -->
                                <div class="space-y-2">
                                    <label for="scheduled_date" class="block text-sm font-medium text-gray-700">
                                        Tanggal Pemeriksaan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        id="scheduled_date" name="scheduled_date" value="{{ old('scheduled_date') }}"
                                        min="{{ date('Y-m-d') }}" required>
                                </div>

                                <!-- Schedule Time -->
                                <div class="space-y-2">
                                    <label for="scheduled_time" class="block text-sm font-medium text-gray-700">
                                        Waktu Pemeriksaan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="time"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        id="scheduled_time" name="scheduled_time"
                                        value="{{ old('scheduled_time') }}" required>
                                </div>

                                <!-- Payment Method -->
                                <div class="space-y-2">
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700">
                                        <svg class="inline h-4 w-4 mr-1 text-blue-500" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        Metode Pembayaran <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        id="payment_method" name="payment_method" required>
                                        <option value="">Pilih Metode Pembayaran</option>
                                        <option value="cash"
                                            {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                                        <option value="online"
                                            {{ old('payment_method') == 'online' ? 'selected' : '' }}>Online</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="border-t pt-6 space-y-6">
                            <!-- Notes -->
                            <div class="space-y-2">
                                <label for="notes" class="block text-sm font-medium text-gray-700">
                                    <svg class="inline h-4 w-4 mr-1 text-blue-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Catatan Tambahan
                                </label>
                                <textarea
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    id="notes" name="notes" rows="4" placeholder="Masukkan catatan khusus untuk pemeriksaan ini...">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="border-t pt-6">
                            <div class="flex flex-col sm:flex-row sm:justify-between space-y-3 sm:space-y-0">
                                <a href="{{ route('staff.examinations.index') }}"
                                    class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    Kembali
                                </a>
                                <div class="flex space-x-3">
                                    <button type="reset"
                                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Reset
                                    </button>
                                    <button type="submit"
                                        class="inline-flex items-center justify-center px-6 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-colors">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                        </svg>
                                        Simpan Pemeriksaan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Data from Laravel
            const patients = {!! json_encode($patient) !!};
            const serviceCategories = {!! json_encode($serviceCategories) !!};

            // Search functionality for patients
            function setupPatientSearch() {
                const searchInput = document.getElementById('patient_search');
                const dropdown = document.getElementById('patient_dropdown');
                const hiddenInput = document.getElementById('patient_id');

                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase();

                    if (query.length === 0) {
                        dropdown.classList.add('hidden');
                        hiddenInput.value = '';
                        return;
                    }

                    const filteredPatients = patients.filter(patient =>
                        patient.name.toLowerCase().includes(query) ||
                        (patient.phone && patient.phone.includes(query))
                    );

                    if (filteredPatients.length > 0) {
                        dropdown.innerHTML = filteredPatients.map(patient =>
                            `<div class="px-4 py-2 hover:bg-blue-50 cursor-pointer border-b last:border-b-0" 
                                  onclick="selectPatient(${patient.id}, '${patient.name}', '${patient.phone || 'No Phone'}')">
                                <div class="font-medium text-gray-900">${patient.name}</div>
                                <div class="text-sm text-gray-500">${patient.phone || 'No Phone'}</div>
                            </div>`
                        ).join('');
                        dropdown.classList.remove('hidden');
                    } else {
                        dropdown.innerHTML = '<div class="px-4 py-2 text-gray-500">Tidak ada pasien ditemukan</div>';
                        dropdown.classList.remove('hidden');
                    }
                });

                // Hide dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                });
            }

            function selectPatient(id, name, phone) {
                document.getElementById('patient_search').value = `${name} - ${phone}`;
                document.getElementById('patient_id').value = id;
                document.getElementById('patient_dropdown').classList.add('hidden');
            }

            // Search functionality for service categories
            function setupCategorySearch() {
                const searchInput = document.getElementById('service_category_search');
                const dropdown = document.getElementById('category_dropdown');
                const hiddenInput = document.getElementById('service_category');

                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase();

                    if (query.length === 0) {
                        dropdown.classList.add('hidden');
                        hiddenInput.value = '';
                        resetServiceItems();
                        return;
                    }

                    const filteredCategories = serviceCategories.filter(category =>
                        category.name.toLowerCase().includes(query)
                    );

                    if (filteredCategories.length > 0) {
                        dropdown.innerHTML = filteredCategories.map(category =>
                            `<div class="px-4 py-2 hover:bg-blue-50 cursor-pointer border-b last:border-b-0" 
                                  onclick="selectCategory(${category.id}, '${category.name}')">
                                <div class="font-medium text-gray-900">${category.name}</div>
                            </div>`
                        ).join('');
                        dropdown.classList.remove('hidden');
                    } else {
                        dropdown.innerHTML = '<div class="px-4 py-2 text-gray-500">Tidak ada kategori ditemukan</div>';
                        dropdown.classList.remove('hidden');
                    }
                });

                // Hide dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                });
            }

            function selectCategory(id, name) {
                document.getElementById('service_category_search').value = name;
                document.getElementById('service_category').value = id;
                document.getElementById('category_dropdown').classList.add('hidden');

                // Enable service item search and load items
                const serviceItemInput = document.getElementById('service_item_search');
                serviceItemInput.disabled = false;
                serviceItemInput.placeholder = 'Cari jenis pemeriksaan...';

                setupServiceItemSearch(id);
            }

            // Search functionality for service items
            function setupServiceItemSearch(categoryId) {
                const searchInput = document.getElementById('service_item_search');
                const dropdown = document.getElementById('service_item_dropdown');
                const hiddenInput = document.getElementById('service_item_id');

                // Get service items for selected category
                const category = serviceCategories.find(cat => cat.id == categoryId);
                const serviceItems = category ? category.service_items : [];

                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase();

                    if (query.length === 0) {
                        dropdown.classList.add('hidden');
                        hiddenInput.value = '';
                        document.getElementById('final_price').value = '';
                        return;
                    }

                    const filteredItems = serviceItems.filter(item =>
                        item.name.toLowerCase().includes(query)
                    );

                    if (filteredItems.length > 0) {
                        dropdown.innerHTML = filteredItems.map(item =>
                            `<div class="px-4 py-2 hover:bg-blue-50 cursor-pointer border-b last:border-b-0" 
                                  onclick="selectServiceItem(${item.id}, '${item.name}', ${item.price})">
                                <div class="font-medium text-gray-900">${item.name}</div>
                                <div class="text-sm text-gray-500">Rp ${item.price.toLocaleString('id-ID')}</div>
                            </div>`
                        ).join('');
                        dropdown.classList.remove('hidden');
                    } else {
                        dropdown.innerHTML =
                            '<div class="px-4 py-2 text-gray-500">Tidak ada jenis pemeriksaan ditemukan</div>';
                        dropdown.classList.remove('hidden');
                    }
                });

                // Hide dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                });
            }

            function selectServiceItem(id, name, price) {
                document.getElementById('service_item_search').value = name;
                document.getElementById('service_item_id').value = id;
                document.getElementById('service_item_dropdown').classList.add('hidden');
                document.getElementById('final_price').value = price;
            }

            function resetServiceItems() {
                const serviceItemInput = document.getElementById('service_item_search');
                serviceItemInput.disabled = true;
                serviceItemInput.placeholder = 'Pilih kategori terlebih dahulu...';
                serviceItemInput.value = '';
                document.getElementById('service_item_id').value = '';
                document.getElementById('final_price').value = '';
                document.getElementById('service_item_dropdown').classList.add('hidden');
            }

            // Initialize search functionality when page loads
            document.addEventListener('DOMContentLoaded', function() {
                setupPatientSearch();
                setupCategorySearch();

                // Form validation
                document.getElementById('examinationForm').addEventListener('submit', function(e) {
                    const patientId = document.getElementById('patient_id').value;
                    const serviceItemId = document.getElementById('service_item_id').value;
                    const finalPrice = document.getElementById('final_price').value;

                    if (!patientId) {
                        e.preventDefault();
                        alert('Silakan pilih pasien terlebih dahulu');
                        return false;
                    }

                    if (!serviceItemId) {
                        e.preventDefault();
                        alert('Silakan pilih jenis pemeriksaan terlebih dahulu');
                        return false;
                    }

                    if (!finalPrice || finalPrice <= 0) {
                        e.preventDefault();
                        alert('Harga pemeriksaan tidak valid');
                        return false;
                    }
                });
            });
        </script>
    @endpush
</x-staff-layout>
