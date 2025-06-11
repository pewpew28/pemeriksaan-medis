<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Pemeriksaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Pembayaran Pemeriksaan</h1>
                        <p class="text-gray-600">Silakan pilih metode pembayaran yang sesuai untuk menyelesaikan transaksi Anda</p>
                    </div>

                    <!-- Detail Pemeriksaan -->
                    <div class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">Detail Pemeriksaan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-blue-700">ID Pemeriksaan</p>
                                <p class="font-bold text-blue-900">#12345</p>
                            </div>
                            <div>
                                <p class="text-sm text-blue-700">Jenis Pemeriksaan</p>
                                <p class="font-bold text-blue-900">Medical Check Up Komprehensif</p>
                            </div>
                            <div>
                                <p class="text-sm text-blue-700">Tanggal Pemeriksaan</p>
                                <p class="font-bold text-blue-900">15 Juni 2025</p>
                            </div>
                            <div>
                                <p class="text-sm text-blue-700">Total Biaya</p>
                                <p class="font-bold text-blue-900 text-xl">Rp 500.000</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Pembayaran -->
                    <div class="mb-8 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-amber-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-amber-800">Pembayaran Belum Lunas</h4>
                                <p class="text-amber-700 mt-1">Pemeriksaan Anda menunggu konfirmasi pembayaran. Silakan pilih metode pembayaran di bawah ini.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pilihan Metode Pembayaran -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6">Pilih Metode Pembayaran</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Pembayaran Online -->
                            <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-blue-500 transition-colors cursor-pointer" onclick="selectPaymentMethod('online')">
                                <div class="flex items-center mb-4">
                                    <div class="w-6 h-6 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center" id="radio-online">
                                        <div class="w-3 h-3 bg-blue-600 rounded-full hidden" id="radio-online-fill"></div>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900">Pembayaran Online</h4>
                                </div>
                                
                                <div class="mb-4">
                                    <div class="flex items-center mb-3">
                                        <svg class="w-8 h-8 mr-3" viewBox="0 0 24 24" fill="none">
                                            <rect x="2" y="6" width="20" height="12" rx="2" stroke="currentColor" stroke-width="2"/>
                                            <path d="M2 10h20" stroke="currentColor" stroke-width="2"/>
                                        </svg>
                                        <span class="text-sm text-gray-600">Transfer Bank / E-Wallet / QRIS</span>
                                    </div>
                                </div>
                                
                                <div class="text-sm text-gray-600">
                                    <p class="mb-2">✓ Pembayaran instan</p>
                                    <p class="mb-2">✓ Konfirmasi otomatis</p>
                                    <p>✓ Mendukung berbagai metode pembayaran</p>
                                </div>
                            </div>

                            <!-- Pembayaran Tunai -->
                            <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-green-500 transition-colors cursor-pointer" onclick="selectPaymentMethod('cash')">
                                <div class="flex items-center mb-4">
                                    <div class="w-6 h-6 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center" id="radio-cash">
                                        <div class="w-3 h-3 bg-green-600 rounded-full hidden" id="radio-cash-fill"></div>
                                    </div>
                                    <h4 class="text-lg font-semibold text-gray-900">Pembayaran Tunai</h4>
                                </div>
                                
                                <div class="mb-4">
                                    <div class="flex items-center mb-3">
                                        <svg class="w-8 h-8 mr-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm text-gray-600">Bayar di Klinik</span>
                                    </div>
                                </div>
                                
                                <div class="text-sm text-gray-600">
                                    <p class="mb-2">✓ Bayar langsung di kasir</p>
                                    <p class="mb-2">✓ Tidak ada biaya tambahan</p>
                                    <p>✓ Dapat membayar saat hari pemeriksaan</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pembayaran Tunai -->
                    <div id="cash-info" class="hidden mb-8 bg-green-50 border border-green-200 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-green-900 mb-4">Informasi Pembayaran Tunai</h4>
                        <div class="space-y-3 text-green-800">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <p><strong>Lokasi:</strong> Kasir Klinik - Lantai 1</p>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                <p><strong>Jam Operasional:</strong> Senin - Sabtu (08:00 - 17:00)</p>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <p><strong>Catatan:</strong> Harap bawa ID Pemeriksaan (#12345) saat melakukan pembayaran</p>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                <p><strong>Batas Waktu:</strong> Pembayaran dapat dilakukan hingga H-1 sebelum jadwal pemeriksaan atau pada hari pemeriksaan</p>
                            </div>
                        </div>
                    </div>

                    <!-- Butuh Bantuan -->
                    <div class="mb-8 bg-gray-50 border border-gray-200 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Butuh Bantuan?</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Telepon</p>
                                    <p class="text-sm text-gray-600">(021) 1234-5678</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Email</p>
                                    <p class="text-sm text-gray-600">cs@klinikmedis.com</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-500 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">WhatsApp</p>
                                    <p class="text-sm text-gray-600">+62 812-3456-7890</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex justify-between">
                        <button class="inline-flex items-center px-6 py-3 bg-gray-200 border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 uppercase tracking-wide hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Kembali ke Detail Pemeriksaan
                        </button>

                        <div class="flex space-x-4">
                            <!-- Tombol Konfirmasi Pembayaran Tunai -->
                            <button id="cash-confirm-btn" class="hidden inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="confirmCashPayment()">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Konfirmasi Pembayaran Tunai
                            </button>

                            <!-- Tombol Bayar Online -->
                            <button id="online-pay-btn" class="hidden inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="processOnlinePayment()">
                                Bayar Sekarang
                                <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedPaymentMethod = null;

        function selectPaymentMethod(method) {
            selectedPaymentMethod = method;
            
            // Reset semua radio button
            document.getElementById('radio-online').classList.remove('border-blue-500');
            document.getElementById('radio-cash').classList.remove('border-green-500');
            document.getElementById('radio-online-fill').classList.add('hidden');
            document.getElementById('radio-cash-fill').classList.add('hidden');
            
            // Reset semua border card
            document.querySelectorAll('.border-2').forEach(card => {
                card.classList.remove('border-blue-500', 'border-green-500');
                card.classList.add('border-gray-200');
            });
            
            // Hide semua tombol
            document.getElementById('cash-confirm-btn').classList.add('hidden');
            document.getElementById('online-pay-btn').classList.add('hidden');
            document.getElementById('cash-info').classList.add('hidden');
            
            if (method === 'online') {
                // Aktifkan radio button online
                document.getElementById('radio-online').classList.add('border-blue-500');
                document.getElementById('radio-online-fill').classList.remove('hidden');
                
                // Highlight card
                event.target.closest('.border-2').classList.remove('border-gray-200');
                event.target.closest('.border-2').classList.add('border-blue-500');
                
                // Show tombol bayar online
                document.getElementById('online-pay-btn').classList.remove('hidden');
                
            } else if (method === 'cash') {
                // Aktifkan radio button cash
                document.getElementById('radio-cash').classList.add('border-green-500');
                document.getElementById('radio-cash-fill').classList.remove('hidden');
                
                // Highlight card
                event.target.closest('.border-2').classList.remove('border-gray-200');
                event.target.closest('.border-2').classList.add('border-green-500');
                
                // Show informasi cash dan tombol konfirmasi
                document.getElementById('cash-info').classList.remove('hidden');
                document.getElementById('cash-confirm-btn').classList.remove('hidden');
            }
        }

        function confirmCashPayment() {
            if (confirm('Apakah Anda yakin akan melakukan pembayaran tunai di klinik?')) {
                alert('Terima kasih! Kami telah mencatat pilihan pembayaran tunai Anda.\n\nSilakan datang ke kasir klinik untuk menyelesaikan pembayaran sebelum jadwal pemeriksaan.\n\nJangan lupa membawa ID Pemeriksaan: #12345');
                
                // Di sini Anda bisa menambahkan AJAX call untuk update status di backend
                // Contoh:
                // updatePaymentMethod('cash');
            }
        }

        function processOnlinePayment() {
            alert('Mengarahkan ke halaman pembayaran online...');
            // Di sini redirect ke payment gateway atau form pembayaran online
            // window.location.href = '/payments/create/12345';
        }

        // Function untuk update payment method ke backend (contoh)
        function updatePaymentMethod(method) {
            fetch('/payments/update-method', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    examination_id: 12345,
                    payment_method: method
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Payment method updated:', data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>