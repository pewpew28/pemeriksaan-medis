<x-staff-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Pembayaran Tunai</h2>
                        <p class="text-gray-600 mt-1">Proses pembayaran tunai untuk pemeriksaan</p>
                    </div>

                    <!-- Informasi Examination -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Informasi Pemeriksaan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">ID Pemeriksaan</label>
                                <p class="text-gray-900 font-mono text-sm">{{ $examination->id }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Pasien</label>
                                <p class="text-gray-900">{{ $examination->patient->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Layanan</label>
                                <p class="text-gray-900">{{ $examination->service_item_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($examination->status === 'completed') bg-green-100 text-green-800
                                    @elseif($examination->status === 'in_progress') bg-blue-100 text-blue-800
                                    @elseif($examination->status === 'scheduled') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $examination->status_label }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Terjadwal</label>
                                <p class="text-gray-900">{{ $examination->scheduled_date->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status Pembayaran</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($examination->payment_status === 'paid') bg-green-100 text-green-800
                                    @elseif($examination->payment_status === 'failed') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ $examination->payment_status_label }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Pembayaran -->
                    <form action="{{ route('staff.examinations.payment.cash', $examination->id) }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Detail Pembayaran -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Pembayaran</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Harga -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Total Tagihan
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="text" 
                                               value="{{ number_format($examination->final_price, 0, ',', '.') }}" 
                                               class="block w-full pl-8 pr-12 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-900 font-semibold text-lg"
                                               readonly>
                                    </div>
                                </div>

                                <!-- Jumlah Bayar -->
                                <div>
                                    <label for="amount_received" class="block text-sm font-medium text-gray-700 mb-2">
                                        Jumlah Diterima <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" 
                                               id="amount_received" 
                                               name="amount_received" 
                                               step="1000"
                                               min="{{ $examination->final_price }}"
                                               value="{{ old('amount_received', $examination->final_price) }}"
                                               class="block w-full pl-8 pr-12 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-lg"
                                               required
                                               onchange="calculateChange()">
                                    </div>
                                    @error('amount_received')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Kembalian -->
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kembalian
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="text" 
                                           id="change_amount" 
                                           class="block w-full pl-8 pr-12 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-900 font-semibold text-lg"
                                           value="0" 
                                           readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Pembayaran -->
                        <div>
                            <label for="payment_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan Pembayaran
                            </label>
                            <textarea id="payment_notes" 
                                      name="payment_notes" 
                                      rows="3" 
                                      class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Masukkan catatan tambahan jika diperlukan...">{{ old('payment_notes') }}</textarea>
                            @error('payment_notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Konfirmasi -->
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Konfirmasi:</strong> Pastikan jumlah uang yang diterima sudah sesuai sebelum memproses pembayaran. 
                                        Setelah diproses, status pembayaran akan berubah menjadi "Sudah Dibayar".
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-4 border-t">
                            <a href="{{ route('staff.examinations.show', $examination->id) }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Kembali
                            </a>
                            
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Proses Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript untuk kalkulasi kembalian -->
    <script>
        function calculateChange() {
            const totalBill = {{ $examination->final_price }};
            const amountPaid = document.getElementById('amount_received').value;
            const changeAmount = document.getElementById('change_amount');
            
            if (amountPaid && amountPaid >= totalBill) {
                const change = amountPaid - totalBill;
                changeAmount.value = new Intl.NumberFormat('id-ID').format(change);
            } else {
                changeAmount.value = '0';
            }
        }

        // Format input number saat mengetik
        document.getElementById('amount_received').addEventListener('input', function(e) {
            calculateChange();
        });

        // Hitung kembalian saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            calculateChange();
        });
    </script>
</x-staff-layout>