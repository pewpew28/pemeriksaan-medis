<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role; // Untuk manajemen role
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Maatwebsite\Excel\Facades\Excel; // Untuk export Excel
use App\Exports\PatientsExport; // Anda perlu membuat ini
use App\Exports\ExaminationsExport; // Anda perlu membuat ini

class AdminController extends Controller
{
    /**
     * Tampilkan dashboard untuk staf (admin, cs, perawat).
     */
    public function dashboard()
    {
        // Statistik umum untuk dashboard staf
        $totalPatients = Patient::count();
        $pendingExaminations = Examination::where('status', 'pending')->count();
        $completedExaminations = Examination::where('status', 'completed')->count();
        $totalUsers = User::count();

        return view('staff.dashboard', compact('totalPatients', 'pendingExaminations', 'completedExaminations', 'totalUsers'));
    }

    // --- Manajemen Data Pasien ---

    /**
     * Tampilkan daftar semua pasien.
     * Menggunakan Yajra Datatables (Anda perlu mengintegrasikan JS/HTML di view)
     */
    public function indexPatients(Request $request)
    {
        // Ini adalah contoh sederhana. Untuk Yajra Datatables, Anda biasanya akan membuat DataTables class.
        if ($request->ajax()) {
            $data = Patient::select('*');
            return datatables()->of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                           $btn = '<a href="'.route('staff.patients.show', $row->id).'" class="edit btn btn-info btn-sm">Lihat</a>';
                           $btn .= ' <a href="'.route('staff.patients.edit', $row->id).'" class="edit btn btn-primary btn-sm">Edit</a>';
                           $btn .= ' <button class="btn btn-danger btn-sm delete-patient" data-id="'.$row->id.'">Hapus</button>';
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('staff.patients.index');
    }

    /**
     * Tampilkan formulir untuk membuat pasien baru.
     */
    public function createPatient()
    {
        return view('staff.patients.create');
    }

    /**
     * Simpan data pasien baru.
     */
    public function storePatient(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:patients,email',
            'phone_number' => 'nullable|string|max:20',
            'age' => 'required|integer|min:1',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Laki-laki,Perempuan,Lainnya',
            // Jika ada user_id yang diisi dari form (misal: terhubung dengan user yang sudah ada)
            'user_id' => 'nullable|exists:users,id',
        ]);

        Patient::create($request->all());

        return redirect()->route('staff.patients.index')->with('success', 'Data pasien berhasil ditambahkan!');
    }

    /**
     * Tampilkan detail pasien.
     */
    public function showPatient(Patient $patient)
    {
        // Memuat relasi examinations untuk ditampilkan detail jadwal penjemputan
        $patient->load('examinations');
        return view('staff.patients.show', compact('patient'));
    }

    /**
     * Tampilkan formulir untuk mengedit data pasien.
     */
    public function editPatient(Patient $patient)
    {
        return view('staff.patients.edit', compact('patient'));
    }

    /**
     * Perbarui data pasien.
     */
    public function updatePatient(Request $request, Patient $patient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:patients,email,'.$patient->id,
            'phone_number' => 'nullable|string|max:20',
            'age' => 'required|integer|min:1',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Laki-laki,Perempuan,Lainnya',
        ]);

        $patient->update($request->all());

        return redirect()->route('staff.patients.index')->with('success', 'Data pasien berhasil diperbarui!');
    }

    /**
     * Hapus data pasien.
     */
    public function destroyPatient(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('staff.patients.index')->with('success', 'Data pasien berhasil dihapus!');
    }

    // --- Manajemen Pemeriksaan ---

    /**
     * Tampilkan daftar semua pemeriksaan.
     * Menggunakan Yajra Datatables.
     */
    public function indexExaminations(Request $request)
    {
        if ($request->ajax()) {
            $data = Examination::with('patient')->select('examinations.*'); // Load relasi patient
            return datatables()->of($data)
                    ->addIndexColumn()
                    ->addColumn('patient_name', function(Examination $examination) {
                        return $examination->patient->name;
                    })
                    ->addColumn('action', function($row){
                           $btn = '<a href="'.route('staff.examinations.show', $row->id).'" class="edit btn btn-info btn-sm">Lihat</a>';
                           $btn .= ' <a href="'.route('staff.examinations.upload_result.form', $row->id).'" class="edit btn btn-warning btn-sm">Upload Hasil</a>';
                           // Tambahkan tombol edit/hapus pemeriksaan jika diperlukan
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('staff.examinations.index');
    }

    /**
     * Tampilkan detail pemeriksaan.
     */
    public function showExaminationDetail(Examination $examination)
    {
        $examination->load('patient'); // Memuat relasi patient
        return view('staff.examinations.show', compact('examination'));
    }

    /**
     * Tampilkan formulir untuk mengunggah hasil pemeriksaan.
     */
    public function showUploadResultForm(Examination $examination)
    {
        return view('staff.examinations.upload_result_form', compact('examination'));
    }

    /**
     * Unggah file hasil pemeriksaan (PDF) menggunakan Spatie Media Library.
     */
    public function uploadResult(Request $request, Examination $examination)
    {
        $request->validate([
            'result_file' => 'required|mimes:pdf|max:10240', // Max 10MB
        ]);

        try {
            // Hapus hasil sebelumnya jika ada
            $examination->clearMediaCollection('results');

            // Tambahkan file baru ke koleksi 'results'
            $examination->addMediaFromRequest('result_file')
                        ->usingFileName(uniqid() . '.' . $request->file('result_file')->getClientOriginalExtension())
                        ->toMediaCollection('results');

            // Perbarui status pemeriksaan
            $examination->update([
                'result_available' => true,
                'status' => 'completed', // Otomatis menjadi completed setelah hasil diupload
            ]);

            return redirect()->route('staff.examinations.index')->with('success', 'Hasil pemeriksaan berhasil diunggah dan status diperbarui!');

        } catch (FileIsTooBig $e) {
            return redirect()->back()->with('error', 'Ukuran file terlalu besar. Maksimal 10MB.');
        } catch (FileDoesNotExist $e) {
            return redirect()->back()->with('error', 'File tidak ditemukan atau rusak.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunggah file: ' . $e->getMessage());
        }
    }

    /**
     * Menandai hasil pemeriksaan sudah tersedia (jika tidak otomatis setelah upload).
     */
    public function markResultAvailable(Examination $examination)
    {
        $examination->update(['result_available' => true]);
        return redirect()->back()->with('success', 'Hasil pemeriksaan berhasil ditandai sebagai tersedia.');
    }

    // --- Export Data (Maatwebsite Laravel Excel) ---

    /**
     * Export data pasien ke Excel.
     */
    public function exportPatients()
    {
        // Pastikan Anda sudah membuat App\Exports\PatientsExport.php
        return Excel::download(new PatientsExport, 'data_pasien.xlsx');
    }

    /**
     * Export data pemeriksaan ke Excel.
     */
    public function exportExaminations()
    {
        // Pastikan Anda sudah membuat App\Exports\ExaminationsExport.php
        return Excel::download(new ExaminationsExport, 'data_pemeriksaan.xlsx');
    }


    // --- Manajemen User & Role (Khusus Admin) ---

    /**
     * Tampilkan daftar semua user.
     */
    public function indexUsers()
    {
        $users = User::with('roles')->paginate(10); // Load relasi role
        return view('staff.users.index', compact('users'));
    }

    /**
     * Tampilkan formulir untuk mengedit peran user.
     */
    public function editUserRole(User $user)
    {
        $roles = Role::all(); // Ambil semua role yang tersedia
        return view('staff.users.edit_role', compact('user', 'roles'));
    }

    /**
     * Perbarui peran user.
     */
    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name', // Pastikan role yang dipilih ada di tabel roles
        ]);

        $user->syncRoles($request->input('roles')); // Sinkronkan peran user
        $user->update(['role' => $request->input('roles')[0]]); // Update kolom 'role' di tabel users

        return redirect()->route('staff.users.index')->with('success', 'Peran pengguna berhasil diperbarui!');
    }
}