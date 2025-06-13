<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Pemeriksaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100">
    {{-- {{ dd($examination) }} --}}
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
                                <p class="font-bold text-blue-900" id="examination-id">#{{ $examination->id }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-blue-700">Jenis Pemeriksaan</p>
                                <p class="font-bold text-blue-900">{{ $examination->serviceItem->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-blue-700">Tanggal Pemeriksaan</p>
                                <p class="font-bold text-blue-900" id="scheduled-date">
                                    {{ \Carbon\Carbon::parse($examination->scheduled_date)->isoFormat('dddd, D MMMM YYYY') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-blue-700">Total Biaya</p>
                                <p class="font-bold text-blue-900 text-xl" id="total-amount">Rp
                                    {{ number_format($examination->serviceItem->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Payment Status Section (Dynamic) --}}
                    <div id="payment-status-section" class="mb-8 p-4 rounded-lg
                        {{ ($examination->payment_status == 'paid' || $examination->status == 'completed') ? 'bg-green-50 border border-green-200' : (($examination->payment_status == 'pending_cash_payment') ? 'bg-yellow-50 border border-yellow-200' : 'bg-amber-50 border border-amber-200') }}
                    ">
                        <div class="flex items-center">
                            @if ($examination->payment_status == 'paid' || $examination->status == 'completed')
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-green-800" id="payment-status-title">Pembayaran
                                        Lunas</h4>
                                    <p class="text-green-700 mt-1" id="payment-status-message">Pemeriksaan Anda telah
                                        terbayar lunas. Terima kasih!</p>
                                </div>
                            @elseif ($examination->payment_status == 'pending_cash_payment')
                                <svg class="w-5 h-5 text-yellow-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-yellow-800" id="payment-status-title">Menunggu
                                        Pembayaran Tunai</h4>
                                    <p class="text-yellow-700 mt-1" id="payment-status-message">Silakan lakukan
                                        pembayaran di kasir klinik pada jam operasional.</p>
                                </div>
                            @else {{-- Default: pending payment (online or not yet selected) --}}
                                <svg class="w-5 h-5 text-amber-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-amber-800" id="payment-status-title">Pembayaran
                                        Belum Lunas</h4>
                                    <p class="text-amber-700 mt-1" id="payment-status-message">Pemeriksaan Anda
                                        menunggu konfirmasi pembayaran. Silakan pilih metode pembayaran di bawah ini.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Payment Options Section --}}
                    @if ($examination->payment_status != 'paid' && $examination->status != 'completed' && $examination->payment_status != 'pending_cash_payment')
                        <div id="payment-options-section" class="mb-8">
                            <h3 class="text-xl font-semibold text-gray-900 mb-6">Pilih Metode Pembayaran</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-blue-500 transition-colors cursor-pointer"
                                    onclick="selectPaymentMethod('online', this)">
                                    <div class="flex items-center mb-4">
                                        <div class="w-6 h-6 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center"
                                            id="radio-online">
                                            <div class="w-3 h-3 bg-blue-600 rounded-full hidden"
                                                id="radio-online-fill">
                                            </div>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Pembayaran Online</h4>
                                    </div>

                                    <div class="mb-4">
                                        <div class="flex items-center mb-3">
                                            <svg class="w-8 h-8 mr-3" viewBox="0 0 24 24" fill="none">
                                                <rect x="2" y="6" width="20" height="12" rx="2"
                                                    stroke="currentColor" stroke-width="2" />
                                                <path d="M2 10h20" stroke="currentColor" stroke-width="2" />
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

                                <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-green-500 transition-colors cursor-pointer"
                                    onclick="selectPaymentMethod('cash', this)">
                                    <div class="flex items-center mb-4">
                                        <div class="w-6 h-6 border-2 border-gray-300 rounded-full mr-3 flex items-center justify-center"
                                            id="radio-cash">
                                            <div class="w-3 h-3 bg-green-600 rounded-full hidden"
                                                id="radio-cash-fill">
                                            </div>
                                        </div>
                                        <h4 class="text-lg font-semibold text-gray-900">Pembayaran Tunai</h4>
                                    </div>

                                    <div class="mb-4">
                                        <div class="flex items-center mb-3">
                                            <svg class="w-8 h-8 mr-3 text-green-600" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                                                <path fill-rule="evenodd"
                                                    d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1z"
                                                    clip-rule="evenodd" />
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
                    @endif

                    {{-- Cash Info Section (Dynamic) --}}
                    @if ($examination->payment_status == 'pending_cash_payment')
                        <div id="cash-info" class="mb-8 bg-green-50 border border-green-200 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-green-900 mb-4">Informasi Pembayaran Tunai</h4>
                            <div class="space-y-3 text-green-800">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <p><strong>Lokasi:</strong> Kasir Klinik - Lantai 1</p>
                                </div>
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <p><strong>Jam Operasional:</strong> Senin - Sabtu (08:00 - 17:00)</p>
                                </div>
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <p><strong>Catatan:</strong> Harap bawa ID Pemeriksaan (<span
                                            id="cash-info-examination-id">#{{ $examination->id }}</span>) saat
                                        melakukan pembayaran</p>
                                </div>
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <p><strong>Batas Waktu:</strong> Pembayaran dapat dilakukan hingga H-1 sebelum
                                        jadwal pemeriksaan atau pada hari pemeriksaan</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div id="cash-info" class="hidden mb-8 bg-green-50 border border-green-200 rounded-lg p-6">
                            {{-- This section will only be visible if cash is selected dynamically --}}
                            <h4 class="text-lg font-semibold text-green-900 mb-4">Informasi Pembayaran Tunai</h4>
                            <div class="space-y-3 text-green-800">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <p><strong>Lokasi:</strong> Kasir Klinik - Lantai 1</p>
                                </div>
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <p><strong>Jam Operasional:</strong> Senin - Sabtu (08:00 - 17:00)</p>
                                </div>
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <p><strong>Catatan:</strong> Harap bawa ID Pemeriksaan (<span
                                            id="cash-info-examination-id">#{{ $examination->id }}</span>) saat
                                        melakukan pembayaran</p>
                                </div>
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-600 mr-3 mt-0.5" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <p><strong>Batas Waktu:</strong> Pembayaran dapat dilakukan hingga H-1 sebelum
                                        jadwal pemeriksaan atau pada hari pemeriksaan</p>
                                </div>
                            </div>
                        </div>
                    @endif


                    <div id="action-buttons" class="flex justify-between">
                        <button onclick="window.location.href='/pasien/dashboard'"
                            class="inline-flex items-center px-6 py-3 bg-gray-200 border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 uppercase tracking-wide hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Kembali ke Dashboard
                        </button>

                        @if ($examination->payment_status != 'paid' && $examination->status != 'completed')
                            <div class="flex space-x-4">
                                <button id="cash-confirm-btn"
                                    class="hidden inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    onclick="submitCashPaymentForm()">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Konfirmasi Pembayaran Tunai
                                </button>

                                <button id="online-pay-btn"
                                    class="hidden inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    onclick="submitOnlinePaymentForm()">
                                    Bayar Sekarang
                                    <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="cash-payment-form" action="{{ route('pasien.payments.confirmCash', $examination->id) }}" method="POST"
        style="display: none;">
        @csrf
        <input type="hidden" name="examination_id" value="{{ $examination->id }}">
    </form>

    <form id="online-payment-form" action="{{ route('payments.create', $examination->id) }}" method="POST"
        style="display: none;">
        @csrf
        <input type="hidden" name="examination_id" value="{{ $examination->id }}">
        <input type="hidden" name="amount" value="{{ $examination->serviceItem->price }}">
    </form>

    <script>
        let selectedPaymentMethod = null;
        const examinationId = "{{ $examination->id }}";
        const examinationRawAmount = parseFloat("{{ $examination->serviceItem->price }}");
        const examinationFormattedAmount = "Rp " + examinationRawAmount.toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });

        function selectPaymentMethod(method, element) {
            selectedPaymentMethod = method;

            // Reset all radio button fills and card borders
            document.getElementById('radio-online-fill').classList.add('hidden');
            document.getElementById('radio-cash-fill').classList.add('hidden');

            document.querySelectorAll('.border-2').forEach(card => {
                card.classList.remove('border-blue-500', 'border-green-500');
                card.classList.add('border-gray-200');
            });

            // Hide all action buttons and cash info initially
            document.getElementById('cash-confirm-btn').classList.add('hidden');
            document.getElementById('online-pay-btn').classList.add('hidden');
            document.getElementById('cash-info').classList.add('hidden');

            if (method === 'online') {
                // Activate online radio button and highlight card
                document.getElementById('radio-online-fill').classList.remove('hidden');
                element.classList.remove('border-gray-200');
                element.classList.add('border-blue-500');

                // Show online payment button
                document.getElementById('online-pay-btn').classList.remove('hidden');

            } else if (method === 'cash') {
                // Activate cash radio button and highlight card
                document.getElementById('radio-cash-fill').classList.remove('hidden');
                element.classList.remove('border-gray-200');
                element.classList.add('border-green-500');

                // Show cash info and cash confirmation button
                document.getElementById('cash-info').classList.remove('hidden'); // This is for dynamic selection
                document.getElementById('cash-confirm-btn').classList.remove('hidden');
            }
        }

        function submitCashPaymentForm() {
            if (confirm('Apakah Anda yakin akan mengkonfirmasi pembayaran tunai di klinik?')) {
                document.getElementById('cash-payment-form').submit();
            }
        }

        function submitOnlinePaymentForm() {
            console.log('Initiating online payment for examination ID:', examinationId, 'amount:', examinationRawAmount);
            document.getElementById('online-payment-form').submit();
        }

        
    </script>
</body>

</html>