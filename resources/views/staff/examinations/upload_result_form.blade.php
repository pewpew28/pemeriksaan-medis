<x-staff-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Upload Hasil Pemeriksaan') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Pemeriksaan #{{ $examination->id }} - {{ $examination->patient->name }}
                </p>
            </div>
            <div class="text-sm text-gray-600">
                {{ now()->format('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('staff.examinations.show', $examination) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-600 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-green-800">{{ session('success') }}</p>
                        </div>
                        <div class="ml-auto pl-3">
                            <button type="button" class="text-green-400 hover:text-green-600" onclick="this.parentElement.parentElement.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-red-800">{{ session('error') }}</p>
                        </div>
                        <div class="ml-auto pl-3">
                            <button type="button" class="text-red-400 hover:text-red-600" onclick="this.parentElement.parentElement.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Patient & Examination Info -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                        <div class="px-6 py-4 bg-blue-600 text-white">
                            <h3 class="text-lg font-semibold flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                Informasi Pemeriksaan
                            </h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">ID Pemeriksaan:</label>
                                <p class="text-gray-900 font-semibold">#{{ $examination->id }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Nama Pasien:</label>
                                <p class="text-gray-900">{{ $examination->patient->name }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Status Saat Ini:</label>
                                <div>
                                    @switch($examination->status)
                                        @case('created')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Dibuat</span>
                                            @break
                                        @case('pending_payment')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu Pembayaran</span>
                                            @break
                                        @case('pending_cash_payment')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Menunggu Pembayaran Cash</span>
                                            @break
                                        @case('paid')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Dibayar</span>
                                            @break
                                        @case('scheduled')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">Dijadwalkan</span>
                                            @break
                                        @case('in_progress')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Sedang Berlangsung</span>
                                            @break
                                        @case('completed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($examination->status) }}</span>
                                    @endswitch
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Terjadwal:</label>
                                <p class="text-gray-900">
                                    {{ $examination->scheduled_date ? \Carbon\Carbon::parse($examination->scheduled_date)->format('d/m/Y H:i') : '-' }}
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Status Hasil:</label>
                                <div>
                                    @if($examination->result_available)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Tersedia</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Belum Tersedia</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Result (if exists) -->
                    @if($examination->result_available && $examination->getFirstMedia('results'))
                        <div class="bg-white overflow-hidden shadow-lg rounded-lg mt-6">
                            <div class="px-6 py-4 bg-cyan-600 text-white">
                                <h4 class="text-base font-semibold flex items-center">
                                    <i class="fas fa-file-medical mr-2"></i>
                                    Hasil Saat Ini
                                </h4>
                            </div>
                            <div class="p-6">
                                @php
                                    $currentResult = $examination->getFirstMedia('results');
                                @endphp
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-file-pdf text-red-600 text-2xl"></i>
                                    </div>
                                    <p class="font-medium text-gray-900 mb-1">{{ $currentResult->file_name }}</p>
                                    <p class="text-sm text-gray-500 mb-4">{{ $currentResult->human_readable_size }}</p>
                                    <a href="{{ $currentResult->getUrl() }}" target="_blank" 
                                       class="inline-flex items-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded-lg transition-colors duration-200">
                                        <i class="fas fa-eye mr-2"></i>Lihat
                                    </a>
                                </div>
                                <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                    <p class="text-sm text-yellow-800 flex items-start">
                                        <i class="fas fa-exclamation-triangle mr-2 mt-0.5 flex-shrink-0"></i>
                                        Upload file baru akan mengganti file yang ada.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Upload Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                        <div class="px-6 py-4 bg-green-600 text-white">
                            <h3 class="text-lg font-semibold flex items-center">
                                <i class="fas fa-upload mr-2"></i>
                                Upload Hasil Pemeriksaan
                            </h3>
                        </div>
                        <div class="p-6">
                            <form action="{{ route('staff.examinations.upload_result.store', $examination) }}" 
                                  method="POST" enctype="multipart/form-data" id="uploadForm">
                                @csrf
                                
                                <!-- File Upload Area -->
                                <div class="mb-6">
                                    <label for="result_file" class="block text-sm font-medium text-gray-700 mb-2">
                                        File Hasil Pemeriksaan <span class="text-red-500">*</span>
                                    </label>
                                    <div class="upload-area border-2 border-dashed border-blue-300 rounded-lg p-8 text-center hover:border-blue-400 hover:bg-blue-50 transition-colors duration-200 cursor-pointer" id="uploadArea">
                                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-cloud-upload-alt text-blue-600 text-2xl"></i>
                                        </div>
                                        <h4 class="text-lg font-semibold text-blue-600 mb-2">Drag & Drop file PDF di sini</h4>
                                        <p class="text-gray-500 mb-4">atau</p>
                                        <input type="file" name="result_file" id="result_file" class="hidden" accept=".pdf" required>
                                        <button type="button" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200" id="selectFileBtn">
                                            <i class="fas fa-folder-open mr-2"></i>Pilih File
                                        </button>
                                        <div class="mt-4">
                                            <p class="text-sm text-gray-500 flex items-center justify-center">
                                                <i class="fas fa-info-circle mr-2"></i>
                                                Format: PDF | Ukuran maksimal: 10MB
                                            </p>
                                        </div>
                                    </div>
                                    @error('result_file')
                                        <div class="mt-2 text-sm text-red-600 flex items-center">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- File Preview -->
                                <div id="filePreview" class="mb-6 hidden">
                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                                                <i class="fas fa-file-pdf text-red-600 text-xl"></i>
                                            </div>
                                            <div class="flex-1">
                                                <h5 class="font-semibold text-gray-900" id="fileName"></h5>
                                                <p class="text-sm text-gray-500" id="fileSize"></p>
                                            </div>
                                            <button type="button" class="p-2 text-gray-400 hover:text-gray-600 transition-colors duration-200" id="removeFile">
                                                <i class="fas fa-times text-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div id="uploadProgress" class="mb-6 hidden">
                                    <div class="bg-gray-200 rounded-full h-4 mb-2">
                                        <div class="bg-green-600 h-4 rounded-full transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                    <p class="text-sm text-gray-600">Mengupload file...</p>
                                </div>

                                <!-- Upload Information -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                    <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                                        <i class="fas fa-info-circle mr-2"></i>Informasi Upload
                                    </h4>
                                    <ul class="text-sm text-blue-800 space-y-1">
                                        <li class="flex items-start">
                                            <i class="fas fa-check mr-2 mt-0.5 text-blue-600 flex-shrink-0"></i>
                                            File harus berformat PDF
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-check mr-2 mt-0.5 text-blue-600 flex-shrink-0"></i>
                                            Ukuran maksimal file adalah 10MB
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-check mr-2 mt-0.5 text-blue-600 flex-shrink-0"></i>
                                            Pastikan file tidak rusak atau terproteksi password
                                        </li>
                                        <li class="flex items-start">
                                            <i class="fas fa-check mr-2 mt-0.5 text-blue-600 flex-shrink-0"></i>
                                            Setelah upload berhasil, status pemeriksaan akan otomatis berubah menjadi "Selesai"
                                        </li>
                                        @if($examination->result_available)
                                            <li class="flex items-start text-orange-700">
                                                <i class="fas fa-exclamation-triangle mr-2 mt-0.5 text-orange-600 flex-shrink-0"></i>
                                                <strong>File yang diupload akan mengganti file hasil yang sudah ada sebelumnya</strong>
                                            </li>
                                        @endif
                                    </ul>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <a href="{{ route('staff.examinations.show', $examination) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            <i class="fas fa-times mr-2"></i>Batal
                                        </a>
                                    </div>
                                    <div class="space-x-3">
                                        <button type="button" 
                                                class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors duration-200" 
                                                id="markAvailableBtn">
                                            <i class="fas fa-check mr-2"></i>Tandai Tersedia Tanpa Upload
                                        </button>
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" 
                                                id="submitBtn" disabled>
                                            <i class="fas fa-upload mr-2"></i>Upload Hasil
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mark Available Modal -->
    <div id="markAvailableModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Konfirmasi</h3>
                    <button type="button" class="text-gray-400 hover:text-gray-600" id="closeModal">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>
                <div class="mt-2 px-2 py-3">
                    <p class="text-gray-600 mb-3">Apakah Anda yakin ingin menandai hasil pemeriksaan sebagai tersedia tanpa mengupload file?</p>
                    <p class="text-sm text-gray-500">Tindakan ini akan mengubah status hasil menjadi "Tersedia" namun tidak ada file yang diupload.</p>
                </div>
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button type="button" 
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200" 
                            id="cancelModal">
                        Batal
                    </button>
                    <form action="{{ route('staff.examinations.mark_available', $examination) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            Ya, Tandai Tersedia
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('result_file');
            const selectFileBtn = document.getElementById('selectFileBtn');
            const filePreview = document.getElementById('filePreview');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const removeFileBtn = document.getElementById('removeFile');
            const submitBtn = document.getElementById('submitBtn');
            const uploadForm = document.getElementById('uploadForm');
            const uploadProgress = document.getElementById('uploadProgress');
            const markAvailableBtn = document.getElementById('markAvailableBtn');
            const markAvailableModal = document.getElementById('markAvailableModal');
            const closeModal = document.getElementById('closeModal');
            const cancelModal = document.getElementById('cancelModal');

            // File input change handler
            fileInput.addEventListener('change', handleFileSelect);
            
            // Select file button click
            selectFileBtn.addEventListener('click', () => fileInput.click());
            
            // Upload area click
            uploadArea.addEventListener('click', () => fileInput.click());
            
            // Remove file button
            removeFileBtn.addEventListener('click', clearFile);
            
            // Mark available button
            markAvailableBtn.addEventListener('click', () => markAvailableModal.classList.remove('hidden'));
            
            // Close modal buttons
            closeModal.addEventListener('click', () => markAvailableModal.classList.add('hidden'));
            cancelModal.addEventListener('click', () => markAvailableModal.classList.add('hidden'));
            
            // Close modal when clicking outside
            markAvailableModal.addEventListener('click', function(e) {
                if (e.target === markAvailableModal) {
                    markAvailableModal.classList.add('hidden');
                }
            });
            
            // Drag and drop handlers
            uploadArea.addEventListener('dragover', handleDragOver);
            uploadArea.addEventListener('dragleave', handleDragLeave);
            uploadArea.addEventListener('drop', handleDrop);
            
            // Form submit handler
            uploadForm.addEventListener('submit', handleFormSubmit);

            function handleFileSelect(e) {
                const file = e.target.files[0];
                if (file) {
                    if (validateFile(file)) {
                        showFilePreview(file);
                    } else {
                        clearFile();
                    }
                }
            }

            function handleDragOver(e) {
                e.preventDefault();
                uploadArea.classList.add('border-blue-500', 'bg-blue-100');
            }

            function handleDragLeave(e) {
                e.preventDefault();
                uploadArea.classList.remove('border-blue-500', 'bg-blue-100');
            }

            function handleDrop(e) {
                e.preventDefault();
                uploadArea.classList.remove('border-blue-500', 'bg-blue-100');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const file = files[0];
                    if (validateFile(file)) {
                        fileInput.files = files;
                        showFilePreview(file);
                    }
                }
            }

            function validateFile(file) {
                // Check file type
                if (file.type !== 'application/pdf') {
                    alert('File harus berformat PDF!');
                    return false;
                }
                
                // Check file size (10MB = 10 * 1024 * 1024 bytes)
                if (file.size > 10 * 1024 * 1024) {
                    alert('Ukuran file tidak boleh lebih dari 10MB!');
                    return false;
                }
                
                return true;
            }

            function showFilePreview(file) {
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                filePreview.classList.remove('hidden');
                submitBtn.disabled = false;
            }

            function clearFile() {
                fileInput.value = '';
                filePreview.classList.add('hidden');
                submitBtn.disabled = true;
                uploadArea.classList.remove('border-blue-500', 'bg-blue-100');
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            function handleFormSubmit(e) {
                e.preventDefault();
                
                if (!fileInput.files[0]) {
                    alert('Silakan pilih file terlebih dahulu!');
                    return;
                }

                // Show progress bar
                uploadProgress.classList.remove('hidden');
                submitBtn.disabled = true;
                
                // Create FormData
                const formData = new FormData(uploadForm);
                
                // Create XMLHttpRequest for progress tracking
                const xhr = new XMLHttpRequest();
                
                // Progress handler
                xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                        const percentComplete = (e.loaded / e.total) * 100;
                        const progressBar = uploadProgress.querySelector('.bg-green-600');
                        progressBar.style.width = percentComplete + '%';
                    }
                });
                
                // Success handler
                xhr.addEventListener('load', function() {
                    if (xhr.status === 200) {
                        // Redirect to examinations index with success message
                        window.location.href = "{{ route('staff.examinations.index') }}";
                    } else {
                        // Handle error
                        uploadProgress.classList.add('hidden');
                        submitBtn.disabled = false;
                        alert('Terjadi kesalahan saat mengupload file. Silakan coba lagi.');
                    }
                });
                
                // Error handler
                xhr.addEventListener('error', function() {
                    uploadProgress.classList.add('hidden');
                    submitBtn.disabled = false;
                    alert('Terjadi kesalahan saat mengupload file. Silakan coba lagi.');
                });
                
                // Send request
                xhr.open('POST', uploadForm.action);
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                xhr.send(formData);
            }
        });
    </script>
    @endpush
</x-staff-layout>