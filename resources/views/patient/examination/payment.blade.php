<x-patient-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pembayaran Pemeriksaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembayaran Pemeriksaan</h1>
                        <p class="text-gray-600">Silakan pilih metode pembayaran yang sesuai untuk menyelesaikan
                            transaksi Anda</p>
                    </div>

                    <div class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">Detail Pemeriksaan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-blue-700">ID Pemeriksaan</p>
                                <p class="font-bold text-blue-900">#{{ $examination->id }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-blue-700">Jenis Pemeriksaan</p>
                                <p class="font-bold text-blue-900">{{ $examination->serviceItem->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-blue-700">Tanggal Pemeriksaan</p>
                                <p class="font-bold text-blue-900">
                                    {{ $examination->scheduled_date ? \Carbon\Carbon::parse($examination->scheduled_date)->format('d F Y') : 'Belum ditentukan' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-blue-700">Total Biaya</p>
                                <p class="font-bold text-blue-900 text-xl">Rp
                                    {{ number_format($examination->serviceItem->price ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-amber-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-amber-800">Pembayaran Belum Lunas</h4>
                                <p class="text-amber-700 mt-1">Pemeriksaan Anda menunggu konfirmasi pembayaran. Silakan
                                    pilih metode pembayaran di bawah ini.</p>
                            </div>
                        </div>
                    </div>

                    <div id="loading-overlay"
                        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                            <div class="mt-3 text-center">
                                <div
                                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mb-4">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                </div>
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Memproses Pembayaran</h3>
                                <div class="mt-2 px-7 py-3">
                                    <p class="text-sm text-gray-500">Mohon tunggu, kami sedang membuat invoice
                                        pembayaran untuk Anda...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Pilih Metode Pembayaran</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow cursor-pointer group payment-method"
                                data-method="qris" onclick="selectPayment('qris')">
                                <div class="text-center">
                                    <div
                                        class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-105 transition-transform">
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm10 0h2v2h-2v-2zm0 4h2v2h-2v-2zm-2-2h2v2h-2v-2zm4 0h2v2h-2v-2zm0-4h2v2h-2v-2z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">QRIS</h4>
                                    <p class="text-sm text-gray-600 mb-4">Scan QR Code dengan aplikasi e-wallet atau
                                        mobile banking</p>
                                    <div class="text-green-600 font-medium">
                                        <span class="inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Instant
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow cursor-pointer group payment-method"
                                data-method="transfer" onclick="selectPayment('transfer')">
                                <div class="text-center">
                                    <div
                                        class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-105 transition-transform">
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M2 6a2 2 0 012-2h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6zm2 0v2h16V6H4zm0 4v8h16v-8H4z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Transfer Bank</h4>
                                    <p class="text-sm text-gray-600 mb-4">Transfer melalui ATM, Internet Banking, atau
                                        Mobile Banking</p>
                                    <div class="text-blue-600 font-medium">
                                        <span class="inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            24 Jam
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow cursor-pointer group payment-method"
                                data-method="cash" onclick="selectPayment('cash')">
                                <div class="text-center">
                                    <div
                                        class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-105 transition-transform">
                                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-2">Tunai</h4>
                                    <p class="text-sm text-gray-600 mb-4">Bayar langsung di kasir klinik pada saat
                                        pemeriksaan</p>
                                    <div class="text-emerald-600 font-medium">
                                        <span class="inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            Langsung
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="payment-details" class="hidden mb-8">
                        <div id="qris-details" class="hidden bg-purple-50 border border-purple-200 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-purple-900 mb-4">Pembayaran QRIS</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <div class="bg-white p-4 rounded-lg text-center">
                                        <div id="qris-code-container"
                                            class="w-48 h-48 mx-auto bg-gray-200 rounded-lg flex items-center justify-center mb-4">
                                            <span class="text-gray-500">QR Code akan muncul di sini</span>
                                        </div>
                                        <p class="text-sm text-gray-600">Scan QR Code dengan aplikasi e-wallet favorit
                                            Anda</p>
                                        <div id="qris-expiry" class="mt-2 text-sm text-red-600 font-medium hidden">
                                            Expires in: <span id="qris-timer">00:00</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm text-purple-700">Nominal Pembayaran</p>
                                        <p class="text-2xl font-bold text-purple-900">Rp
                                            {{ number_format($examination->serviceItem->price ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="bg-white p-4 rounded-lg">
                                        <h5 class="font-medium text-gray-900 mb-2">Langkah Pembayaran:</h5>
                                        <ol class="list-decimal list-inside text-sm text-gray-700 space-y-1">
                                            <li>Buka aplikasi e-wallet atau mobile banking</li>
                                            <li>Pilih menu "Scan QR" atau "QRIS"</li>
                                            <li>Scan QR Code di samping</li>
                                            <li>Konfirmasi nominal pembayaran</li>
                                            <li>Selesaikan pembayaran</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="transfer-details" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-blue-900 mb-4">Transfer Bank</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="bg-white p-4 rounded-lg">
                                        <h5 class="font-medium text-gray-900 mb-3">Rekening Tujuan:</h5>
                                        <div id="bank-accounts-container" class="space-y-3">
                                            </div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm text-blue-700">Nominal Transfer</p>
                                        <p class="text-2xl font-bold text-blue-900">Rp
                                            {{ number_format($examination->serviceItem->price ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="bg-white p-4 rounded-lg">
                                        <h5 class="font-medium text-gray-900 mb-2">Catatan Penting:</h5>
                                        <ul class="list-disc list-inside text-sm text-gray-700 space-y-1">
                                            <li>Transfer sesuai nominal exact tanpa pembulatan</li>
                                            <li>Simpan bukti transfer untuk konfirmasi</li>
                                            <li>Konfirmasi pembayaran ke Customer Service</li>
                                            <li>Pembayaran akan diverifikasi dalam 1x24 jam</li>
                                        </ul>
                                    </div>
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                        <p class="text-sm text-yellow-800">
                                            <strong>Berita Transfer:</strong> <span
                                                id="transfer-reference">EXAM#{{ $examination->id }}</span>
                                        </p>
                                    </div>
                                    <div id="transfer-expiry"
                                        class="bg-red-50 border border-red-200 rounded-lg p-3 hidden">
                                        <p class="text-sm text-red-800">
                                            <strong>Batas Waktu Transfer:</strong> <span
                                                id="transfer-timer">00:00:00</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="cash-details" class="hidden bg-green-50 border border-green-200 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-green-900 mb-4">Pembayaran Tunai</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <div class="bg-white p-4 rounded-lg">
                                        <h5 class="font-medium text-gray-900 mb-3">Informasi Pembayaran:</h5>
                                        <div class="space-y-3">
                                            <div>
                                                <p class="text-sm text-green-700">Nominal Pembayaran</p>
                                                <p class="text-2xl font-bold text-green-900">Rp
                                                    {{ number_format($examination->serviceItem->price ?? 0, 0, ',', '.') }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-green-700">Lokasi Pembayaran</p>
                                                <p class="font-medium text-green-900">Kasir Klinik Medis Sejahtera</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-green-700">Jam Operasional</p>
                                                <p class="font-medium text-green-900">Senin - Sabtu: 08:00 - 20:00</p>
                                                <p class="font-medium text-green-900">Minggu: 08:00 - 16:00</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="bg-white p-4 rounded-lg">
                                        <h5 class="font-medium text-gray-900 mb-2">Petunjuk Pembayaran:</h5>
                                        <ol class="list-decimal list-inside text-sm text-gray-700 space-y-1">
                                            <li>Datang ke klinik sesuai jadwal pemeriksaan</li>
                                            <li>Tunjukkan ID pemeriksaan #{{ $examination->id }} kepada petugas</li>
                                            <li>Lakukan pembayaran di kasir</li>
                                            <li>Simpan bukti pembayaran (struk)</li>
                                            <li>Lanjutkan ke ruang pemeriksaan</li>
                                        </ol>
                                    </div>
                                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mt-4">
                                        <p class="text-sm text-amber-800">
                                            <strong>Catatan:</strong> Pembayaran tunai dapat dilakukan pada hari
                                            pemeriksaan, minimal 30 menit sebelum jadwal.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="payment-status" class="hidden mb-8">
                        <div id="payment-pending" class="hidden p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-yellow-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-yellow-800">Menunggu Pembayaran</h4>
                                    <p class="text-yellow-700 mt-1">Invoice telah dibuat. Silakan lakukan pembayaran
                                        sesuai instruksi di atas.</p>
                                </div>
                            </div>
                        </div>

                        <div id="payment-success" class="hidden p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-green-800">Pembayaran Berhasil</h4>
                                    <p class="text-green-700 mt-1">Terima kasih! Pembayaran Anda telah dikonfirmasi.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8 bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Butuh Bantuan?</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z">
                                    </path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Telepon</p>
                                    <p class="text-sm text-gray-600">(021) 1234-5678</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                    </path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Email</p>
                                    <p class="text-sm text-gray-600">cs@klinikmedis.com</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">WhatsApp</p>
                                    <p class="text-sm text-gray-600">+62 812-3456-7890</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('pasien.examinations.show', $examination->id) }}"
                            class="inline-flex items-center px-6 py-3 bg-gray-200 border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 uppercase tracking-wide hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Kembali ke Detail Pemeriksaan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Include qrcode.js library for client-side QR code generation --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode/1.5.3/qrcode.min.js"></script>

    <script>
        // Configuration
        const CONFIG = {
            examinationId: `{{ $examination->id }}`,
            amount: {{ $examination->serviceItem->price ?? 0 }},
            csrfToken: '{{ csrf_token() }}',
            apiEndpoints: { 
                qris: '/api/payment/qris/create',
                transfer: '/api/payment/transfer/create',
                cash: '/api/payment/cash/create'
            }
        };

        // Global variables
        let selectedPaymentMethod = null;
        let paymentData = null;
        let paymentTimer = null;

        // Initialize payment selection
        function selectPayment(method) {
            selectedPaymentMethod = method;

            // Remove active class from all payment methods
            document.querySelectorAll('.payment-method').forEach(el => {
                el.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');
            });

            // Add active class to selected method
            const selectedElement = document.querySelector(`[data-method="${method}"]`);
            selectedElement.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');

            // Show loading overlay
            showLoadingOverlay();

            // Fetch payment data based on method
            fetchPaymentData(method);
        }

        // Show loading overlay
        function showLoadingOverlay() {
            document.getElementById('loading-overlay').classList.remove('hidden');
        }

        // Hide loading overlay
        function hideLoadingOverlay() {
            document.getElementById('loading-overlay').classList.add('hidden');
        }

        // Fetch payment data from API
        async function fetchPaymentData(method) {
            try {
                const response = await fetch(CONFIG.apiEndpoints[method], {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        examination_id: CONFIG.examinationId,
                        amount: CONFIG.amount,
                        payment_method: method
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();

                if (result.success) {
                    paymentData = result.data;
                    displayPaymentDetails(method, result.data);
                } else {
                    throw new Error(result.message || 'Payment creation failed');
                }
            } catch (error) {
                console.error('Payment API Error:', error);
                showErrorMessage('Gagal memuat data pembayaran. Silakan coba lagi.');
            } finally {
                hideLoadingOverlay();
            }
        }

        // Display payment details based on method
        function displayPaymentDetails(method, data) {
            // Hide all payment details
            document.querySelectorAll('#payment-details > div').forEach(el => {
                el.classList.add('hidden');
            });

            // Show payment details container
            document.getElementById('payment-details').classList.remove('hidden');

            // Show specific payment details
            switch (method) {
                case 'qris':
                    displayQRISDetails(data);
                    break;
                case 'transfer':
                    displayTransferDetails(data);
                    break;
                case 'cash':
                    displayCashDetails(data);
                    break;
            }

            // Show payment status
            showPaymentStatus('pending');

            // Start monitoring payment status
            startPaymentMonitoring();
        }

        // Display QRIS payment details
        function displayQRISDetails(data) {
            const qrisDetails = document.getElementById('qris-details');
            qrisDetails.classList.remove('hidden');

            // Display QR Code
            const qrContainer = document.getElementById('qris-code-container');
            if (data.qr_code_url) {
                qrContainer.innerHTML =
                    `<img src="${data.qr_code_url}" alt="QR Code" class="w-full h-full object-contain">`;
            } else if (data.qr_string) {
                // If QR string is provided, generate QR code using qrcode.js library
                generateQRCode(data.qr_string, qrContainer);
            }

            // Set expiry timer if provided
            if (data.expiry_time) {
                startExpiryTimer('qris', data.expiry_time);
            }
        }

        // Display Transfer payment details
        function displayTransferDetails(data) {
            const transferDetails = document.getElementById('transfer-details');
            transferDetails.classList.remove('hidden');

            // Populate bank accounts
            const bankContainer = document.getElementById('bank-accounts-container');
            if (data.bank_accounts && data.bank_accounts.length > 0) {
                bankContainer.innerHTML = data.bank_accounts.map(account => `
            <div class="border border-gray-200 rounded-lg p-3">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-medium text-gray-900">${account.bank_name}</p>
                        <p class="text-sm text-gray-600">a.n. ${account.account_name}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-mono text-lg font-bold text-gray-900">${account.account_number}</p>
                        <button onclick="copyToClipboard('${account.account_number}')" class="text-xs text-blue-600 hover:text-blue-800">
                            Copy
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
            }

            // Update transfer reference
            if (data.reference_code) {
                document.getElementById('transfer-reference').textContent = data.reference_code;
            }

            // Set expiry timer if provided
            if (data.expiry_time) {
                startExpiryTimer('transfer', data.expiry_time);
            }
        }

        // Display Cash payment details
        function displayCashDetails(data) {
            const cashDetails = document.getElementById('cash-details');
            cashDetails.classList.remove('hidden');

            // Cash payment doesn't need additional API data usually
            // but you can update clinic information if provided in the response
            if (data.clinic_info) {
                updateClinicInfo(data.clinic_info);
            }
        }

        // Generate QR Code (requires qrcode.js library)
        function generateQRCode(text, container) {
            if (typeof QRCode !== 'undefined') {
                container.innerHTML = ''; // Clear previous content
                QRCode.toCanvas(container, text, {
                    width: 192,
                    height: 192,
                    margin: 2
                }, function(error) {
                    if (error) {
                        console.error('QR Code generation failed:', error);
                        container.innerHTML = '<span class="text-red-500">Failed to generate QR Code</span>';
                    }
                });
            } else {
                container.innerHTML = `<div class="w-48 h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                    <span class="text-gray-500">QR Code: ${text}</span>
                </div>`;
            }
        }

        // Start expiry timer
        function startExpiryTimer(method, expiryTime) {
            const timerElement = document.getElementById(`${method}-timer`);
            const expiryElement = document.getElementById(`${method}-expiry`);

            if (!timerElement || !expiryElement) return;

            expiryElement.classList.remove('hidden');

            const expiryDate = new Date(expiryTime);

            paymentTimer = setInterval(() => {
                const now = new Date();
                const timeDiff = expiryDate - now;

                if (timeDiff <= 0) {
                    clearInterval(paymentTimer);
                    timerElement.textContent = '00:00:00';
                    showExpiredMessage();
                    return;
                }
                const hours = Math.floor(timeDiff / (1000 * 60 * 60));
                const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);
                if (method === 'qris') {
                    timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                } else {
                    timerElement.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                }
            }, 1000);
        }

        // Show payment status
        function showPaymentStatus(status) {
            const statusContainer = document.getElementById('payment-status');
            statusContainer.classList.remove('hidden');
            // Hide all status elements
            document.querySelectorAll('#payment-status > div').forEach(el => {
                el.classList.add('hidden');
            });

            // Show specific status
            const statusElement = document.getElementById(`payment-${status}`);
            if (statusElement) {
                statusElement.classList.remove('hidden');
            }
        }

        // Start monitoring payment status
        function startPaymentMonitoring() {
            if (!paymentData || !paymentData.payment_id) return;

            const checkStatus = async () => {
                try {
                    const response = await fetch(`/api/payment/status/${paymentData.payment_id}`, {
                        headers: {
                            'X-CSRF-TOKEN': CONFIG.csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (result.success && result.data.status === 'paid') {
                        showPaymentStatus('success');
                        clearInterval(paymentTimer);
                        stopPaymentMonitoring();

                        // Redirect to success page after 3 seconds
                        setTimeout(() => {
                            window.location.href = `/pasien/examinations/${CONFIG.examinationId}?payment=success`;
                        }, 3000);
                    }
                } catch (error) {
                    console.error('Status check error:', error);
                }
            };

            // Check status every 5 seconds
            const statusMonitor = setInterval(checkStatus, 5000);

            // Stop monitoring after 30 minutes
            setTimeout(() => {
                clearInterval(statusMonitor);
            }, 30 * 60 * 1000);
        }

        // Stop payment monitoring
        function stopPaymentMonitoring() {
            if (paymentTimer) {
                clearInterval(paymentTimer);
                paymentTimer = null;
            }
        }

        // Copy to clipboard utility
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                showSuccessMessage('Nomor rekening berhasil disalin!');
            }).catch(err => {
                console.error('Copy failed:', err);
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                    showSuccessMessage('Nomor rekening berhasil disalin!');
                } catch (err) {
                    console.error('Fallback copy failed:', err);
                }
                document.body.removeChild(textArea);
            });
        }

        // Show success message
        function showSuccessMessage(message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            alertDiv.textContent = message;
            document.body.appendChild(alertDiv);

            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }

        // Show error message
        function showErrorMessage(message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            alertDiv.textContent = message;
            document.body.appendChild(alertDiv);

            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        // Show expired message
        function showExpiredMessage() {
            showErrorMessage('Waktu pembayaran telah habis. Silakan buat pembayaran baru.');

            // Reset payment selection
            setTimeout(() => {
                location.reload();
            }, 3000);
        }

        // Update clinic info for cash payment
        function updateClinicInfo(clinicInfo) {
            // Update clinic information if provided by API
            if (clinicInfo.name) {
                // Update clinic name in the cash details
            }
            if (clinicInfo.hours) {
                // Update operating hours
            }
            if (clinicInfo.address) {
                // Update address if displayed
            }
        }

        // Cleanup on page unload
        window.addEventListener('beforeunload', () => {
            stopPaymentMonitoring();
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Payment page initialized');

            // Add click handlers to payment methods if not already added inline
            document.querySelectorAll('.payment-method').forEach(method => {
                const methodType = method.getAttribute('data-method');
                if (!method.getAttribute('onclick')) {
                    method.addEventListener('click', () => selectPayment(methodType));
                }
            });
        });
    </script>
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode/1.5.3/qrcode.min.js"></script>
    @endpush
</x-patient-layout>