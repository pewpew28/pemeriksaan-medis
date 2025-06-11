<?php

namespace App\Exports;

use App\Models\Examination;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison; // Opsional: untuk menangani nilai null

class ExaminationsExport implements FromCollection, WithHeadings, WithMapping, WithStrictNullComparison
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Ambil semua data pemeriksaan dengan relasi pasien
        return Examination::with('patient')->get();

        // Jika Anda ingin filter data, contoh:
        // return Examination::where('status', 'completed')->with('patient')->get();
    }

    /**
     * @return array
     * Header kolom untuk file Excel
     */
    public function headings(): array
    {
        return [
            'ID Pemeriksaan',
            'ID Pasien',
            'Nama Pasien', // Tambahan dari relasi
            'Tipe Pemeriksaan',
            'Tanggal Jadwal',
            'Waktu Jadwal',
            'Permintaan Penjemputan',
            'Alamat Penjemputan',
            'Link Lokasi Peta',
            'Waktu Penjemputan',
            'Status',
            'Catatan',
            'Hasil Tersedia',
            'Status Pembayaran',
            'Metode Pembayaran',
            'Dibuat Pada',
            'Diperbarui Pada',
        ];
    }

    /**
     * @var Examination $examination
     * @return array
     * Map data dari koleksi ke baris Excel
     */
    public function map($examination): array
    {
        return [
            $examination->id,
            $examination->patient_id,
            $examination->patient->name, // Ambil nama pasien dari relasi
            $examination->serviceItem->name,
            \Carbon\Carbon::parse($examination->scheduled_date)->format('d-m-Y'),
            \Carbon\Carbon::parse($examination->scheduled_time)->format('H:i'),
            $examination->pickup_requested ? 'Ya' : 'Tidak',
            $examination->pickup_address,
            $examination->pickup_location_map,
            $examination->pickup_time ? \Carbon\Carbon::parse($examination->pickup_time)->format('H:i') : null,
            $examination->status,
            $examination->notes,
            $examination->result_available ? 'Ya' : 'Tidak',
            $examination->payment_status,
            $examination->payment_method,
            $examination->created_at,
            $examination->updated_at,
        ];
    }
}