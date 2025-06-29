<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $identity['name'] }}</title>
    <link rel="shortcut icon" href="assets/icon/healthcare-icon.png" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-400 {
            animation-delay: 0.4s;
        }

        .delay-600 {
            animation-delay: 0.6s;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .gradient-text {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.15);
        }

        .section-divider {
            background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
            height: 1px;
            margin: 4rem 0;
        }

        .stat-counter {
            font-size: 2.5rem;
            font-weight: 700;
            color: #3b82f6;
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-900 bg-white">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-md border-b border-gray-100 fixed w-full z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="shrink-0 flex items-center">
                        <div class="flex items-center space-x-3">
                            <span class="text-xl font-bold gradient-text">{{ $identity['name'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="#services"
                        class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Layanan</a>
                    <a href="#about"
                        class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Tentang</a>
                    <a href="#contact"
                        class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Kontak</a>
                    <div class="flex items-center space-x-3">
                        @if (Route::has('login'))
                            <div class="flex items-center space-x-3">
                                @auth
                                    <a href="{{ route('dashboard') }}"
                                        class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Masuk</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}"
                                            class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-6 py-2 rounded-full font-semibold hover:shadow-lg transition-all duration-300">Daftar</a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header
        class="relative bg-gradient-to-br from-blue-600 via-indigo-700 to-purple-800 min-h-screen flex items-center justify-center text-white overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="currentColor" stroke-width="1" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>

        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full animate-pulse"></div>
        <div class="absolute bottom-32 right-16 w-32 h-32 bg-white/5 rounded-full animate-pulse delay-200"></div>
        <div class="absolute top-1/2 right-10 w-16 h-16 bg-white/10 rounded-full animate-pulse delay-400"></div>

        <div class="relative z-20 text-center px-4 max-w-5xl mx-auto">
            <div class="mb-6 animate-fade-in-up">
                <span
                    class="inline-block bg-white/20 text-white px-4 py-2 rounded-full text-sm font-semibold backdrop-blur-sm">
                    ✨ Platform Kesehatan Digital Terdepan
                </span>
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-7xl font-bold leading-tight mb-6 animate-fade-in-up delay-200">
                Kesehatan Anda,<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">Masa Depan
                    Kami</span>
            </h1>

            <p
                class="text-lg sm:text-xl lg:text-2xl mb-10 max-w-3xl mx-auto text-blue-100 animate-fade-in-up delay-400 leading-relaxed">
                Rasakan pengalaman layanan kesehatan yang revolusioner dengan teknologi terkini,
                didukung tenaga medis profesional dan sistem terintegrasi untuk keluarga Indonesia.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in-up delay-600">
                <a href="{{ route('login') }}"
                    class="bg-white text-blue-700 hover:bg-gray-50 px-8 py-4 rounded-full text-lg font-semibold shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105 flex items-center space-x-2">
                    <span>Mulai Konsultasi</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                <a href="#services"
                    class="glass-effect text-white hover:bg-white/20 px-8 py-4 rounded-full text-lg font-semibold transition-all duration-300 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.01M15 10h1.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span>Pelajari Layanan</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Stats Section -->
    <section class="py-16 bg-gradient-to-r from-blue-50 to-indigo-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="hover-lift bg-white p-6 rounded-2xl shadow-sm">
                    <div class="stat-counter">{{ $pasien }}+</div>
                    <p class="text-gray-600 font-medium">Pasien Terlayani</p>
                </div>
                <div class="hover-lift bg-white p-6 rounded-2xl shadow-sm">
                    <div class="stat-counter">{{ $layanan }}+</div>
                    <p class="text-gray-600 font-medium">Layanan</p>
                </div>
                <div class="hover-lift bg-white p-6 rounded-2xl shadow-sm">
                    <div class="stat-counter">24/7</div>
                    <p class="text-gray-600 font-medium">Layanan Darurat</p>
                </div>
                <div class="hover-lift bg-white p-6 rounded-2xl shadow-sm">
                    <div class="stat-counter">99%</div>
                    <p class="text-gray-600 font-medium">Kepuasan Pasien</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-20 bg-white" id="services">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-blue-600 font-semibold text-lg mb-2 block">LAYANAN UNGGULAN</span>
                <h2 class="text-4xl sm:text-5xl font-bold mb-6 text-gray-900">
                    Solusi Kesehatan <span class="gradient-text">Terlengkap</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Dapatkan akses ke berbagai layanan medis berkualitas tinggi dengan teknologi terdepan
                    dan tenaga medis berpengalaman.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="hover-lift bg-white p-8 rounded-2xl shadow-lg border border-gray-100 group">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-900">Pemeriksaan Komprehensif</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Akses lengkap ke berbagai jenis pemeriksaan medis dengan peralatan canggih dan hasil akurat
                        dalam waktu singkat.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Laboratorium Digital
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Radiologi & Imaging
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Medical Check-up Eksekutif
                        </li>
                    </ul>
                </div>

                <!-- Service 2 -->
                <div class="hover-lift bg-white p-8 rounded-2xl shadow-lg border border-gray-100 group">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-900">Janji Temu Digital</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Sistem booking online yang mudah dan fleksibel, memungkinkan Anda mengatur jadwal konsultasi
                        kapan saja.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-indigo-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Booking 24/7 Online
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-indigo-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Reminder Otomatis
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-indigo-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Reschedule Fleksibel
                        </li>
                    </ul>
                </div>

                <!-- Service 3 -->
                <div class="hover-lift bg-white p-8 rounded-2xl shadow-lg border border-gray-100 group">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-900">Rekam Medis Digital</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        Akses mudah ke riwayat kesehatan lengkap Anda dengan sistem keamanan tinggi dan sinkronisasi
                        real-time.
                    </p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-purple-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Cloud Storage Aman
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-purple-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Akses Multi-Device
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-purple-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Sharing dengan Dokter
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-20 bg-gradient-to-br from-gray-50 to-blue-50" id="about">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-blue-600 font-semibold text-lg mb-2 block uppercase">MENGAPA {{ $identity['name'] }}</span>
                <h2 class="text-4xl sm:text-5xl font-bold mb-6 text-gray-900">
                    Komitmen Pada <span class="gradient-text">Keunggulan</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="space-y-8">
                        <div class="flex items-start space-x-4">
                            <div
                                class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Tenaga Medis Tersertifikasi</h3>
                                <p class="text-gray-600">Tim dokter spesialis dan perawat profesional dengan
                                    sertifikasi internasional dan pengalaman puluhan tahun.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div
                                class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Teknologi Medis Terdepan</h3>
                                <p class="text-gray-600">Investasi berkelanjutan dalam peralatan medis canggih dan
                                    sistem informasi terintegrasi untuk diagnosis akurat.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div
                                class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Keamanan Data Terjamin</h3>
                                <p class="text-gray-600">Perlindungan data medis dengan enkripsi tingkat militer dan
                                    kepatuhan penuh terhadap standar privasi internasional.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <div
                                class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Layanan 24/7 Responsif</h3>
                                <p class="text-gray-600">Tim customer service dan layanan darurat yang siaga 24 jam
                                    untuk memberikan bantuan terbaik kapan saja.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-8 text-white">
                        <h3 class="text-2xl font-bold mb-6">Pencapaian Kami</h3>
                        <div class="space-y-6">
                            <div class="flex justify-between items-center">
                                <span>Akreditasi Nasional</span>
                                <span class="font-bold">⭐⭐⭐⭐⭐</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>ISO 27001 Certified</span>
                                <span class="font-bold">✓ Verified</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>JCI Accredited</span>
                                <span class="font-bold">✓ Certified</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Patient Safety Award</span>
                                <span class="font-bold">🏆 2024</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section
        class="py-20 bg-gradient-to-r from-blue-600 via-indigo-700 to-purple-800 text-white text-center relative overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute top-10 left-10 w-32 h-32 bg-white/5 rounded-full animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-24 h-24 bg-white/10 rounded-full animate-pulse delay-200"></div>

        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <span
                    class="inline-block bg-white/20 text-white px-4 py-2 rounded-full text-sm font-semibold backdrop-blur-sm">
                    🚀 Bergabung dengan 10,000+ Pengguna
                </span>
            </div>

            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-6 leading-tight">
                Mulai Perjalanan Kesehatan<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">Digital Anda Hari
                    Ini</span>
            </h2>

            <p class="text-lg sm:text-xl mb-10 max-w-2xl mx-auto text-blue-100 leading-relaxed">
                Dapatkan akses eksklusif ke platform kesehatan terdepan dan rasakan perbedaan layanan medis berkualitas
                tinggi.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('register') }}"
                    class="bg-white text-blue-700 hover:bg-gray-50 px-10 py-4 rounded-full text-xl font-semibold shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105 flex items-center space-x-2">
                    <span>Daftar Gratis Sekarang</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                <a href="#contact"
                    class="glass-effect text-white hover:bg-white/20 px-10 py-4 rounded-full text-xl font-semibold transition-all duration-300 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                        </path>
                    </svg>
                    <span>Hubungi Kami</span>
                </a>
            </div>

            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-blue-100">Registrasi Gratis</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-blue-100">Akses Instan 24/7</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-blue-100">Data Aman Terjamin</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16" id="contact">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <!-- Company Info -->
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-3 mb-6">
                        <span class="text-2xl font-bold">{{ $identity['name'] }}</span>
                    </div>
                    <p class="text-gray-400 mb-6 max-w-md leading-relaxed">
                        Platform kesehatan digital terdepan di Indonesia yang menghubungkan Anda dengan layanan medis
                        berkualitas tinggi, kapan saja dan di mana saja.
                    </p>
                </div>


                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-bold mb-6">Hubungi Kami</h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-blue-400 mt-1 flex-shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <p class="text-gray-400">Jl. Veteran No.4, Tebing Tinggi Lama, Kec. Tebing Tinggi Kota</p>
                                <p class="text-gray-400">Kota Tebing Tinggi, Sumatera Utara 20616</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            <p class="text-gray-400">{{ $identity['no_telp'] }}</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-400">00.08 - 17.00</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-divider"></div>

            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="text-gray-400 text-sm mb-4 md:mb-0">
                    &copy; 2024 {{ $identity['name'] }}. Semua hak dilindungi undang-undang.
                </div>
                <div class="flex space-x-6 text-sm">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">Syarat & Ketentuan</a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">Bantuan</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Animate stats on scroll
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = entry.target.querySelectorAll('.stat-counter');
                    counters.forEach(counter => {
                        const target = counter.textContent;
                        const number = parseInt(target.replace(/\D/g, ''));
                        if (number) {
                            animateCounter(counter, number, target);
                        }
                    });
                }
            });
        }, observerOptions);

        const statsSection = document.querySelector('.py-16.bg-gradient-to-r.from-blue-50');
        if (statsSection) {
            observer.observe(statsSection);
        }

        function animateCounter(element, target, originalText) {
            let current = 0;
            const increment = target / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = originalText;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current) + originalText.replace(/\d/g, '').replace(/\+/g, '') +
                        '+';
                }
            }, 20);
        }

        // Add scroll effect to navbar
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-lg');
            } else {
                nav.classList.remove('shadow-lg');
            }
        });
    </script>
</body>

</html>
