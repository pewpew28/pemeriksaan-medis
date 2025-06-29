<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pemeriksaan Tersedia</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        
        .email-container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #4CAF50;
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        
        .content {
            margin: 20px 0;
        }
        
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #4CAF50;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        
        .info-label {
            font-weight: bold;
            width: 150px;
            color: #555;
        }
        
        .info-value {
            color: #333;
        }
        
        .highlight {
            background-color: #e8f5e8;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin: 20px 0;
        }
        
        .highlight h3 {
            color: #4CAF50;
            margin: 0 0 10px 0;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #4CAF50;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 10px 0;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #45a049;
        }
        
        .footer {
            border-top: 1px solid #eee;
            padding-top: 20px;
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        
        .important-note {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        
        .important-note h4 {
            color: #856404;
            margin: 0 0 10px 0;
        }
        
        .important-note p {
            color: #856404;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🔬 Hasil Pemeriksaan Tersedia</h1>
            <p>Laboratorium Klinik</p>
        </div>
        
        <div class="content">
            <p>Yth. <strong>{{ $patient->name }}</strong>,</p>
            
            <p>Kami dengan senang hati memberitahukan bahwa hasil pemeriksaan laboratorium Anda telah selesai dan siap untuk diunduh.</p>
            
            <div class="info-box">
                <h3 style="margin-top: 0; color: #4CAF50;">📋 Detail Pemeriksaan</h3>
                <div class="info-row">
                    <span class="info-label">ID Pemeriksaan:</span>
                    <span class="info-value">#{{ $examination->id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jenis Pemeriksaan:</span>
                    <span class="info-value">{{ $serviceItem->name }}</span>
                </div>
                @if($serviceItem->category)
                <div class="info-row">
                    <span class="info-label">Kategori:</span>
                    <span class="info-value">{{ $serviceItem->category->name }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Tanggal Pemeriksaan:</span>
                    <span class="info-value">{{ $examination->scheduled_date ? \Carbon\Carbon::parse($examination->scheduled_date)->format('d F Y') : 'Belum dijadwalkan' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value" style="color: #4CAF50; font-weight: bold;">✅ Selesai</span>
                </div>
            </div>
            
            <div class="highlight">
                <h3>🎉 Hasil Pemeriksaan Sudah Tersedia!</h3>
                <p>Anda dapat mengunduh hasil pemeriksaan melalui akun Anda di website kami.</p>
                
                @if(config('app.url'))
                <a href="{{ config('app.url') }}/login" class="btn">Masuk ke Akun Saya</a>
                @endif
            </div>
            
            <div class="important-note">
                <h4>⚠️ Penting untuk Diperhatikan:</h4>
                <p>• Hasil pemeriksaan bersifat rahasia dan hanya dapat diakses oleh Anda<br>
                • Jika ada pertanyaan mengenai hasil, silakan konsultasi dengan dokter<br>
                • Simpan hasil pemeriksaan dengan baik untuk referensi medis selanjutnya<br>
                • Jika mengalami kesulitan mengakses hasil, hubungi customer service kami</p>
            </div>
            
            <p>Terima kasih telah mempercayakan pemeriksaan laboratorium Anda kepada kami. Jika ada pertanyaan atau memerlukan bantuan, jangan ragu untuk menghubungi kami.</p>
        </div>
        
        <div class="footer">
            <p><strong>Tim Laboratorium Klinik</strong></p>
            <p>Email ini dikirim secara otomatis. Mohon untuk tidak membalas email ini.</p>
            <p>© {{ date('Y') }} Laboratorium Klinik. Semua hak dilindungi.</p>
        </div>
    </div>
</body>
</html>