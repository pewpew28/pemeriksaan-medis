<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $receiptData['reference_code'] }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .receipt-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 40px);
        }

        .receipt-container {
            max-width: 420px;
            width: 100%;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            position: relative;
        }

        .receipt-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
        }

        .receipt-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 25px 20px;
            text-align: center;
            position: relative;
        }

        .clinic-logo {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .clinic-name {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }

        .clinic-subtitle {
            font-size: 13px;
            opacity: 0.9;
            line-height: 1.4;
        }

        .receipt-body {
            padding: 25px;
        }

        .receipt-title {
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px dashed #e0e0e0;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .status-paid {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(46, 204, 113, 0.3);
        }

        .info-section {
            margin-bottom: 25px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #555;
            font-size: 13px;
            min-width: 35%;
        }

        .info-value {
            font-size: 13px;
            color: #2c3e50;
            text-align: right;
            font-weight: 500;
            max-width: 60%;
            word-wrap: break-word;
        }

        .divider {
            border-top: 2px dashed #e0e0e0;
            margin: 20px 0;
            position: relative;
        }

        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: -8px;
            width: 16px;
            height: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
        }

        .divider::before {
            left: -33px;
        }

        .divider::after {
            right: -33px;
        }

        .amount-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            border: 1px solid #e0e0e0;
        }

        .amount-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .amount-row:last-child {
            margin-bottom: 0;
        }

        .amount-label {
            font-weight: 600;
            color: #555;
            font-size: 14px;
        }

        .amount-value {
            font-weight: 700;
            color: #2c3e50;
            font-size: 14px;
        }

        .total-amount {
            font-size: 16px;
            color: #2ecc71;
            border-top: 2px solid #ddd;
            padding-top: 12px;
            margin-top: 12px;
        }

        .change-amount {
            color: #e74c3c !important;
        }

        .notes-section {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }

        .notes-title {
            font-weight: 700;
            color: #856404;
            font-size: 13px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .notes-text {
            font-size: 12px;
            color: #856404;
            line-height: 1.5;
            font-style: italic;
        }

        .receipt-footer {
            text-align: center;
            padding: 25px 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-top: 1px solid #eee;
        }

        .reference-code {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
            padding: 12px;
            background: white;
            border: 2px dashed #667eea;
            border-radius: 8px;
            letter-spacing: 1px;
        }

        .footer-text {
            font-size: 12px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-print {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-back {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            color: white;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(149, 165, 166, 0.3);
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .receipt-wrapper {
                min-height: auto;
            }
            
            .receipt-container {
                box-shadow: none;
                max-width: none;
                border-radius: 0;
            }
            
            .action-buttons,
            .btn {
                display: none !important;
            }

            .receipt-container::before {
                display: none;
            }

            .divider::before,
            .divider::after {
                display: none;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .receipt-container {
                border-radius: 10px;
            }
            
            .receipt-body {
                padding: 20px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        .highlight {
            background: linear-gradient(120deg, transparent 0%, rgba(102, 126, 234, 0.1) 50%, transparent 100%);
            padding: 2px 4px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="receipt-wrapper">
        <div class="receipt-container">
            <div class="receipt-header">
                {{-- <div class="clinic-logo">🏥</div> --}}
                <div class="clinic-name">{{ env('APP_NAME') }}</div>
                <div class="clinic-subtitle">
                    Jl. Veteran No.4, Tebing Tinggi Lama, Kec. Tebing Tinggi Kota<br>
                    Kota Tebing Tinggi, Sumatera Utara 20616<br>
                    Telp: (061) 22423
                </div>
            </div>

            <div class="receipt-body">
                <div class="receipt-title">
                    STRUK PEMBAYARAN
                    <span class="status-paid">✓ LUNAS</span>
                </div>

                <div class="info-section">
                    <div class="info-row">
                        <span class="info-label">🆔 ID Pemeriksaan:</span>
                        <span class="info-value highlight">{{ $receiptData['examination_id'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">👤 Nama Pasien:</span>
                        <span class="info-value">{{ $receiptData['patient_name'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">📞 No. Telepon:</span>
                        <span class="info-value">{{ $receiptData['patient_phone'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">🏥 Layanan:</span>
                        <span class="info-value">{{ $receiptData['service_name'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">📋 Kategori:</span>
                        <span class="info-value">{{ $receiptData['service_category'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">📅 Tanggal Bayar:</span>
                        <span class="info-value">{{ $receiptData['paid_at'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">👨‍⚕️ Diproses oleh:</span>
                        <span class="info-value">{{ $receiptData['processed_by_name'] }}</span>
                    </div>
                </div>

                <div class="divider"></div>

                <div class="amount-section">
                    <div class="amount-row">
                        <span class="amount-label">💰 Total Biaya:</span>
                        <span class="amount-value">Rp {{ number_format($receiptData['amount'], 0, ',', '.') }}</span>
                    </div>
                    <div class="amount-row">
                        <span class="amount-label">💵 Jumlah Diterima:</span>
                        <span class="amount-value">Rp {{ number_format($receiptData['amount_received'], 0, ',', '.') }}</span>
                    </div>
                    <div class="amount-row total-amount">
                        <span class="amount-label">🔄 Kembalian:</span>
                        <span class="amount-value change-amount">Rp {{ number_format($receiptData['change'], 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($receiptData['notes'] && trim($receiptData['notes']) !== '')
                <div class="notes-section">
                    <div class="notes-title">📝 Catatan:</div>
                    <div class="notes-text">{{ $receiptData['notes'] }}</div>
                </div>
                @endif
            </div>

            <div class="receipt-footer">
                <div class="reference-code">{{ $receiptData['reference_code'] }}</div>
                <div class="footer-text">
                    <strong>🙏 Terima kasih atas kunjungan Anda</strong><br>
                    Semoga lekas sembuh dan sehat selalu<br>
                    <em>Simpan struk ini sebagai bukti pembayaran yang sah</em>
                </div>
                
                <div class="action-buttons">
                    <button class="btn btn-print" onclick="window.print()">
                        🖨️ Cetak Struk
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-back">
                        ← Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto print setelah 2 detik jika parameter print=1
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === '1') {
            setTimeout(() => {
                window.print();
            }, 2000);
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+P untuk print
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
            
            // ESC untuk kembali
            if (e.key === 'Escape') {
                window.history.back();
            }
        });

        // Print event handlers
        window.addEventListener('beforeprint', function() {
            document.title = 'Struk Pembayaran - {{ $receiptData["reference_code"] }}';
        });

        window.addEventListener('afterprint', function() {
            // Optional: redirect or close after printing
            // window.close();
        });
    </script>
</body>
</html>