<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Layanan Kesehatan Digital</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900">
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-100 fixed w-full z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('welcome') }}">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                            </a>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        @if (Route::has('login'))
                            <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10 hidden sm:block">
                                @auth
                                    <a href="{{ url('/dashboard') }}"
                                        class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:outline-red-500">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:outline-red-500">Log
                                        in</a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}"
                                            class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:outline-red-500">Register</a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <header
            class="relative bg-gradient-to-r from-blue-600 to-indigo-700 h-screen flex items-center justify-center text-white overflow-hidden">
            <video autoplay loop muted playsinline
                class="absolute z-10 w-auto min-w-full min-h-full max-w-none opacity-20">
                <source
                    src="https://assets.mixkit.co/videos/preview/mixkit-health-specialist-woman-explaining-to-patient-48356-large.mp4"
                    type="video/mp4" />
                Your browser does not support the video tag.
            </video>
            <div class="relative z-20 text-center px-4">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-4 animate-fade-in-up">
                    Kesehatan Anda, Prioritas Kami
                </h1>
                <p class="text-lg sm:text-xl lg:text-2xl mb-8 animate-fade-in-up delay-200">
                    Solusi layanan medis terpadu dan mudah diakses untuk Anda dan keluarga.
                </p>
                <div class="space-x-4 animate-fade-in-up delay-400">
                    <a href="{{ route('register') }}"
                        class="inline-block bg-white text-blue-700 hover:bg-gray-100 px-8 py-3 rounded-full text-lg font-semibold shadow-lg transition duration-300">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}"
                        class="inline-block border-2 border-white text-white hover:bg-white hover:text-blue-700 px-8 py-3 rounded-full text-lg font-semibold shadow-lg transition duration-300">
                        Masuk Akun
                    </a>
                </div>
            </div>
        </header>

        <section class="py-16 bg-white" id="services">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl sm:text-4xl font-bold mb-10 text-gray-800">Layanan Unggulan Kami</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div
                        class="bg-white p-6 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition duration-300">
                        <div class="text-blue-600 mb-4">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Pemeriksaan Lengkap</h3>
                        <p class="text-gray-600">Akses berbagai jenis pemeriksaan kesehatan, mulai dari cek darah hingga
                            rontgen, dengan mudah.</p>
                    </div>
                    <div
                        class="bg-white p-6 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition duration-300">
                        <div class="text-blue-600 mb-4">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2m-6 0h.01M12 12H9.5M12 18h.01">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Jadwal Online Fleksibel</h3>
                        <p class="text-gray-600">Pesan jadwal pemeriksaan Anda kapan saja dan di mana saja sesuai
                            kenyamanan Anda.</p>
                    </div>
                    <div
                        class="bg-white p-6 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition duration-300">
                        <div class="text-blue-600 mb-4">
                            <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-4 0h6">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Hasil Digital Cepat</h3>
                        <p class="text-gray-600">Dapatkan hasil pemeriksaan Anda secara digital dan aman, langsung ke
                            akun Anda.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 bg-blue-50" id="why-us">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl sm:text-4xl font-bold mb-10 text-gray-800">Mengapa Memilih Kami?</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="text-left bg-white p-6 rounded-lg shadow-md border border-gray-200">
                        <h3 class="text-xl font-semibold text-blue-700 mb-2">Tenaga Medis Berpengalaman</h3>
                        <p class="text-gray-700">Tim kami terdiri dari dokter dan perawat yang profesional dan sangat
                            berpengalaman di bidangnya.</p>
                    </div>
                    <div class="text-left bg-white p-6 rounded-lg shadow-md border border-gray-200">
                        <h3 class="text-xl font-semibold text-blue-700 mb-2">Teknologi Modern & Akurat</h3>
                        <p class="text-gray-700">Kami menggunakan peralatan medis terkini untuk memastikan hasil
                            pemeriksaan yang akurat dan cepat.</p>
                    </div>
                    <div class="text-left bg-white p-6 rounded-lg shadow-md border border-gray-200">
                        <h3 class="text-xl font-semibold text-blue-700 mb-2">Proses Pendaftaran Mudah</h3>
                        <p class="text-gray-700">Daftarkan pemeriksaan Anda dalam hitungan menit melalui platform online
                            kami yang intuitif.</p>
                    </div>
                    <div class="text-left bg-white p-6 rounded-lg shadow-md border border-gray-200">
                        <h3 class="text-xl font-semibold text-blue-700 mb-2">Pelayanan Pelanggan Responsif</h3>
                        <p class="text-gray-700">Tim dukungan kami siap membantu Anda dengan pertanyaan atau kendala
                            kapan saja.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-16 bg-indigo-700 text-white text-center">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl sm:text-4xl font-bold mb-6">Siap Mengatur Kesehatan Anda?</h2>
                <p class="text-lg sm:text-xl mb-8">
                    Daftar sekarang dan mulai perjalanan kesehatan Anda bersama kami.
                </p>
                <a href="{{ route('register') }}"
                    class="inline-block bg-white text-indigo-700 hover:bg-gray-100 px-10 py-4 rounded-full text-xl font-semibold shadow-lg transition duration-300">
                    Mulai Sekarang
                </a>
            </div>
        </section>

        <footer class="bg-gray-800 text-white py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">{{ config('app.name', 'Laravel') }}</h3>
                    <p class="text-gray-400">Menyediakan layanan kesehatan yang mudah diakses dan akurat untuk
                        masyarakat Indonesia.</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Layanan Cepat</h3>
                    <ul>
                        <li><a href="{{ route('login') }}" class="text-gray-400 hover:text-white">Login</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-400 hover:text-white">Daftar</a></li>
                        <li><a href="#services" class="text-gray-400 hover:text-white">Layanan Kami</a></li>
                        {{-- Tambahkan link ke halaman kontak jika ada --}}
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Kontak Kami</h3>
                    <p class="text-gray-400">Jalan Sehat Selalu No. 123, Medan, Sumatera Utara</p>
                    <p class="text-gray-400">Telepon: (061) 123-4567</p>
                    <p class="text-gray-400">Email: info@klinikmedis.com</p>
                    <div class="flex mt-4 space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white"><svg class="w-6 h-6"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07c3.252.148 4.779 1.674 4.931 4.931.058 1.265.07 1.645.07 4.85s-.012 3.584-.07 4.85c-.148 3.252-1.674 4.779-4.931 4.931-.058.058-1.265.07-4.85.07s-3.584-.012-4.85-.07c-3.252-.148-4.779-1.674-4.931-4.931-.058-1.265-.07-1.645-.07-4.85s.012-3.584.07-4.85c.148-3.252 1.674-4.779 4.931-4.931.058-.058 1.265-.07 4.85-.07zm0-2.163c-3.259 0-3.664.014-4.954.072-4.351.201-6.19 2.049-6.392 6.392-.058 1.29-.072 1.695-.072 4.954 0 3.259.014 3.664.072 4.954.201 4.351 2.049 6.19 6.392 6.392 1.29.058 1.695.072 4.954.072s3.664-.014 4.954-.072c4.351-.201 6.19-2.049 6.392-6.392.058-1.29.072-1.695.072-4.954 0-3.259-.014-3.664-.072-4.954-.201-4.351-2.049-6.19-6.392-6.392-1.29-.058-1.695-.072-4.954-.072zM12 6.865c-2.842 0-5.135 2.293-5.135 5.135s2.293 5.135 5.135 5.135 5.135-2.293 5.135-5.135-2.293-5.135-5.135-5.135zm0 8.569c-1.996 0-3.613-1.617-3.613-3.613s1.617-3.613 3.613-3.613 3.613 1.617 3.613 3.613-1.617 3.613-3.613 3.613zm5.725-11.776c-.958 0-1.734.777-1.734 1.734s.776 1.734 1.734 1.734 1.734-.777 1.734-1.734-.777-1.734-1.734-1.734z" />
                            </svg></a>
                        <a href="#" class="text-gray-400 hover:text-white"><svg class="w-6 h-6"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M7 10.749h2.336L9.333 13H7c-1.378 0-2.5 1.121-2.5 2.5V22h4.5v-7.25h2.128l.25-2.75H11.5V10h3.5a1 1 0 001-1V5a1 1 0 00-1-1H11.5a1 1 0 00-1 1v1H7v-1a1 1 0 00-1-1H3.5a1 1 0 00-1 1v4a1 1 0 001 1h.5a1 1 0 001-1zM22 12a10 10 0 11-20 0 10 10 0 0120 0z" />
                            </svg></a>
                        {{-- Add more social media icons if needed --}}
                    </div>
                </div>
            </div>
            <div class="text-center text-gray-500 mt-8">
                &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
            </div>
        </footer>
    </div>
</body>

</html>
