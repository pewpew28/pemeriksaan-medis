<x-patient-layout>
    {{-- Header Section
    <x-slot name="header">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-700 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-white">Profil Saya</h1>
                    <nav class="text-white text-sm">
                        <a href="{{ route('pasien.dashboard') }}" class="hover:text-indigo-200">Dashboard</a>
                        <span class="mx-2">•</span>
                        <span>Profil</span>
                    </nav>
                </div>
            </div>
        </div>
    </x-slot> --}}

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Success/Error Messages --}}
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-green-600 text-lg">✓</span>
                        </div>
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-red-600 text-lg">⚠</span>
                        </div>
                        <p class="text-red-800 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- Profile Header Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-8 py-6">
                    <div class="flex items-center">
                        <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-6">
                            <span class="text-white text-3xl font-bold">
                                {{ strtoupper(substr($patient->name ?? $user->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="text-white">
                            <h2 class="text-2xl font-bold mb-1">{{ $patient->name ?? $user->name }}</h2>
                            <p class="text-indigo-100">{{ $patient->email ?? $user->email }}</p>
                            @if($patient && $patient->age)
                                <p class="text-indigo-100 text-sm mt-1">{{ $patient->age }} tahun</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('pasien.profile.update') }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Personal Information Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-indigo-600 text-lg">👤</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Informasi Pribadi</h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Name --}}
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $patient->name ?? $user->name) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('name') border-red-500 @enderror"
                                       placeholder="Masukkan nama lengkap"
                                       required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $patient->email ?? $user->email) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('email') border-red-500 @enderror"
                                       placeholder="contoh@email.com"
                                       required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phone Number --}}
                            <div>
                                <label for="phone_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nomor Telepon <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" 
                                       id="phone_number" 
                                       name="phone_number" 
                                       value="{{ old('phone_number', $patient->phone_number ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('phone_number') border-red-500 @enderror"
                                       placeholder="08xxxxxxxxxx"
                                       required>
                                @error('phone_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Date of Birth --}}
                            <div>
                                <label for="date_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tanggal Lahir <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       id="date_of_birth" 
                                       name="date_of_birth" 
                                       value="{{ old('date_of_birth', $patient->date_of_birth ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('date_of_birth') border-red-500 @enderror"
                                       required>
                                @error('date_of_birth')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Age --}}
                            <div>
                                <label for="age" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Usia <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       id="age" 
                                       name="age" 
                                       value="{{ old('age', $patient->age ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('age') border-red-500 @enderror"
                                       placeholder="25"
                                       min="1" 
                                       max="120"
                                       required>
                                @error('age')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Gender --}}
                            <div>
                                <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <select id="gender" 
                                        name="gender" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('gender') border-red-500 @enderror"
                                        required>
                                    <option value="">Pilih jenis kelamin</option>
                                    <option value="Laki-laki" {{ old('gender', $patient->gender ?? '') == 'Laki-laki' ? 'selected' : '' }}>
                                        Laki-laki
                                    </option>
                                    <option value="Perempuan" {{ old('gender', $patient->gender ?? '') == 'Perempuan' ? 'selected' : '' }}>
                                        Perempuan
                                    </option>
                                </select>
                                @error('gender')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Address Information Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <span class="text-green-600 text-lg">📍</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Informasi Alamat</h3>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div>
                            <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea id="address" 
                                      name="address" 
                                      rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 resize-none @error('address') border-red-500 @enderror"
                                      placeholder="Masukkan alamat lengkap termasuk RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Provinsi, dan Kode Pos"
                                      required>{{ old('address', $patient->address ?? '') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-600">
                                💡 Contoh: Jl. Merdeka No. 123, RT 01/RW 05, Kelurahan Menteng, Kecamatan Menteng, Jakarta Pusat, DKI Jakarta 10310
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('pasien.dashboard') }}" 
                       class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-semibold text-center">
                        ← Kembali ke Dashboard
                    </a>
                    
                    <div class="flex space-x-4 w-full sm:w-auto">
                        <button type="reset" 
                                class="flex-1 sm:flex-none px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-semibold">
                            Reset
                        </button>
                        <button type="submit" 
                                class="flex-1 sm:flex-none px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 font-semibold shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            💾 Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>

            {{-- Profile Completion Status --}}
            @php
                $completedFields = 0;
                $totalFields = 7; // name, email, phone, dob, age, gender, address
                
                if($patient) {
                    if($patient->name) $completedFields++;
                    if($patient->email) $completedFields++;
                    if($patient->phone_number) $completedFields++;
                    if($patient->date_of_birth) $completedFields++;
                    if($patient->age) $completedFields++;
                    if($patient->gender) $completedFields++;
                    if($patient->address) $completedFields++;
                } else {
                    if($user->name) $completedFields++;
                    if($user->email) $completedFields++;
                }
                
                $completionPercentage = round(($completedFields / $totalFields) * 100);
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mt-8">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Kelengkapan Profil</h3>
                        <span class="text-2xl font-bold text-indigo-600">{{ $completionPercentage }}%</span>
                    </div>
                    
                    <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-3 rounded-full transition-all duration-500" 
                             style="width: {{ $completionPercentage }}%"></div>
                    </div>
                    
                    <p class="text-sm text-gray-600">
                        @if($completionPercentage == 100)
                            🎉 Profil Anda sudah lengkap! Anda dapat menggunakan semua layanan kami.
                        @elseif($completionPercentage >= 70)
                            ⚡ Profil Anda hampir lengkap! Lengkapi {{ $totalFields - $completedFields }} field lagi.
                        @else
                            📝 Lengkapi profil Anda untuk mendapatkan layanan terbaik dari kami.
                        @endif
                    </p>
                </div>
            </div>

        </div>
    </div>

    {{-- Auto-calculate age from date of birth --}}
    <script>
        document.getElementById('date_of_birth').addEventListener('change', function() {
            const birthDate = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            if (age >= 0 && age <= 120) {
                document.getElementById('age').value = age;
            }
        });

        // Phone number formatting
        document.getElementById('phone_number').addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.startsWith('0')) {
                this.value = value;
            } else if (value.startsWith('62')) {
                this.value = '0' + value.substring(2);
            }
        });
    </script>
</x-patient-layout>