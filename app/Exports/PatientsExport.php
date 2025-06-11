<?php

namespace App\Exports;

use App\Models\Patient;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping; // Opsional: untuk kustomisasi kolom

class PatientsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Ambil semua data pasien
        return Patient::all();

        // Jika Anda hanya ingin pasien tertentu, contoh:
        // return Patient::where('age', '>', 18)->get();
    }

    /**
     * @return array
     * Header kolom untuk file Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'User ID',
            'Nama Lengkap',
            'Email',
            'Nomor Telepon',
            'Usia',
            'Alamat',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Dibuat Pada',
            'Diperbarui Pada',
        ];
    }

    /**
     * @var Patient $patient
     * @return array
     * Map data dari koleksi ke baris Excel
     */
    public function map($patient): array
    {
        return [
            $patient->id,
            $patient->user_id,
            $patient->name,
            $patient->email,
            $patient->phone_number,
            $patient->age,
            $patient->address,
            $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('d-m-Y') : null,
            $patient->gender,
            $patient->created_at,
            $patient->updated_at,
        ];
    }
}