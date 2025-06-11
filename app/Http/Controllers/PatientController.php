<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\Patient;
use App\Models\ServiceItem;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // Untuk download file
use Illuminate\Validation\Rule;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    /**
     * Tampilkan dashboard untuk pasien.
     */
    public function dashboard()
    {
        $user = Auth::user();
        // Ambil data pasien yang terkait dengan user ini
        $patient = $user->patient;

        // Ambil pemeriksaan terbaru atau statistik untuk dashboard pasien
        $recentExaminations = null;
        if ($patient) {
            $recentExaminations = $patient->examinations()->with('serviceItem')->latest()->take(5)->get();
        }

        // dd($recentExaminations);
        return view('patient.dashboard', compact('user', 'patient', 'recentExaminations'));
    }

    public function profile()
    {
        $user = Auth::user();
        $patient = $user->patient; // Ambil data pasien yang terkait dengan user

        // Jika pasien belum memiliki data detail di tabel 'patients',
        // Anda bisa membuat instance Patient baru atau mengarahkan ke form pendaftaran data pasien awal.
        // Untuk saat ini, kita asumsikan patient sudah ada atau akan dibuat di sini.
        if (!$patient) {
            $patient = new Patient([
                'user_id' => $user->id,
                'name' => $user->name, // Ambil nama dari user utama
                'email' => $user->email, // Ambil email dari user utama
            ]);
            // Jika Anda ingin membuat record Patient kosong jika belum ada:
            // $patient->save();
        }

        return view('patient.profile', compact('patient'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
                Rule::unique('patients', 'email')->ignore($user->patient?->id)
            ],
            'phone_number' => [
                'required',
                'string',
                'regex:/^(08[0-9]{8,11}|62[0-9]{9,12})$/',
                'max:15'
            ],
            'date_of_birth' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'address' => ['required', 'string', 'min:10', 'max:500'],
            'gender' => ['required', 'in:Laki-laki,Perempuan,Lainnya'], // Sesuai dengan enum di database
        ];

        // Custom error messages
        $messages = [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'phone_number.regex' => 'Format nomor telepon tidak valid. Gunakan format 08xxxxxxxxxx atau 62xxxxxxxxxx.',
            'address.required' => 'Alamat wajib diisi.',
            'address.min' => 'Alamat minimal 10 karakter.',
            'address.max' => 'Alamat maksimal 500 karakter.',
            'date_of_birth.required' => 'Tanggal lahir wajib diisi.',
            'date_of_birth.date' => 'Format tanggal lahir tidak valid.',
            'date_of_birth.before' => 'Tanggal lahir harus sebelum hari ini.',
            'date_of_birth.after' => 'Tanggal lahir tidak valid.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Pilih jenis kelamin yang valid (Laki-laki, Perempuan, atau Lainnya).',
        ];

        $validatedData = $request->validate($rules, $messages);

        try {
            DB::beginTransaction();

            // Format phone number
            $phoneNumber = $this->formatPhoneNumber($validatedData['phone_number']);

            // Calculate age from date of birth
            $birthDate = Carbon::parse($validatedData['date_of_birth']);
            $calculatedAge = $birthDate->age;

            // Tidak perlu normalisasi gender karena sudah sesuai format database
            // $gender = $this->normalizeGender($validatedData['gender']);

            // Update User table
            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
            ]);

            // Prepare patient data
            $patientData = [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone_number' => $phoneNumber,
                'age' => $calculatedAge,
                'address' => $validatedData['address'],
                'date_of_birth' => $validatedData['date_of_birth'],
                'gender' => $validatedData['gender'], // Langsung gunakan nilai dari form
            ];

            // Update or Create Patient record using updateOrCreate
            $user->patient()->updateOrCreate(
                ['user_id' => $user->id],
                $patientData
            );

            DB::commit();

            return redirect()->route('pasien.profile')
                ->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();

            // Log the error for debugging
            Log::error('Profile update failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'email' => $validatedData['email'] ?? null,
                'data' => $validatedData,
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->with('error', 'Terjadi kesalahan saat memperbarui profil. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Tampilkan formulir pendaftaran pemeriksaan baru.
     */
    public function showRegistrationForm()
    {
        $user = Auth::user();
        // Ambil data pasien yang terkait dengan user
        $patient = $user->patient;
        $serviceItems = ServiceItem::where('is_active', true)->get();

        // Jika pasien belum memiliki data detail, mungkin perlu form pengisian data pasien dulu
        // atau pastikan data pasien terisi saat registrasi user.
        return view('patient.examination.register', compact('user', 'patient', 'serviceItems'));
    }

    /**
     * Simpan data pendaftaran pemeriksaan baru.
     */
    public function storeRegistration(Request $request)
    {
        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            return redirect()->back()->with('error', 'Lengkapi profil pasien Anda terlebih dahulu sebelum mendaftar pemeriksaan.');
        }

        $rules = [
            'service_item_id' => 'required|exists:service_items,id', // Validasi service_item_id
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time' => 'required|date_format:H:i',
            'pickup_requested' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ];

        if ($request->boolean('pickup_requested')) {
            $rules['pickup_address'] = 'required|string|max:255';
            $rules['pickup_location_map'] = 'nullable|url|max:255';
            $rules['pickup_time'] = 'required|date_format:H:i';
        } else {
            $rules['pickup_address'] = 'nullable';
            $rules['pickup_location_map'] = 'nullable';
            $rules['pickup_time'] = 'nullable';
        }

        $validatedData = $request->validate($rules);

        $serviceItem = ServiceItem::findOrFail($validatedData['service_item_id']);

        // Create examination with UUID
        $examination = new Examination($validatedData);
        $examination->id = (string) Str::uuid(); // Generate UUID for examination
        $examination->patient_id = $patient->id;
        $examination->status = 'created';
        $examination->result_available = false;
        $examination->payment_status = 'pending';
        $examination->final_price = $serviceItem->price; // Simpan harga dari service item
        // Jika ada biaya penjemputan, bisa ditambahkan di sini
        // $examination->final_price += $request->boolean('pickup_requested') ? 50000 : 0;
        $examination->save();

        return redirect()->route('pasien.examinations.index')->with('success', 'Pendaftaran pemeriksaan berhasil diajukan!');
    }

    /**
     * Tampilkan daftar pemeriksaan yang dimiliki pasien.
     */
    public function myExaminations()
    {
        $user = Auth::user();
        $patient = $user->patient;

        if (!$patient) {
            // Jika user tidak memiliki record patient, buat paginator kosong
            $examinations = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(), // empty collection
                0, // total items
                10, // per page
                1, // current page
                [
                    'path' => request()->url(),
                    'query' => request()->query(),
                ]
            );
        } else {
            // Ambil semua pemeriksaan terkait dengan pasien ini dengan pagination
            $examinations = $patient->examinations()
                ->latest('created_at')
                ->paginate(10);
        }

        return view('patient.examination.index', compact('examinations'));
    }

    /**
     * Tampilkan detail pemeriksaan tertentu.
     */
    public function showExaminationDetail(Examination $examination)
    {
        // Pastikan pemeriksaan ini milik user yang sedang login
        if ($examination->patient->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Pemeriksaan ini bukan milik Anda.');
        }

        return view('patient.examination.show', compact('examination'));
    }

    /**
     * Unduh hasil pemeriksaan (PDF).
     */
    public function downloadResult(Examination $examination)
    {
        // Pastikan pemeriksaan ini milik user yang sedang login
        if ($examination->patient->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Pemeriksaan ini bukan milik Anda.');
        }

        // Pastikan hasil sudah tersedia
        if (!$examination->result_available) {
            return redirect()->back()->with('error', 'Hasil pemeriksaan belum tersedia untuk diunduh.');
        }

        // Ambil media collection 'results'
        $mediaItem = $examination->getFirstMedia('results');

        if (!$mediaItem) {
            return redirect()->back()->with('error', 'File hasil tidak ditemukan.');
        }

        // Unduh file
        return response()->download($mediaItem->getPath(), $mediaItem->file_name);
    }

    /**
     * Tampilkan detail pembayaran dan instruksi QRIS/Cash.
     */
    public function showPaymentDetails(Examination $examination)
    {
        // Pastikan pemeriksaan ini milik user yang sedang login
        if ($examination->patient->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Pemeriksaan ini bukan milik Anda.');
        }

        // $examination sudah di-resolve oleh Route Model Binding
        // Anda bisa mengambil data payment terkait jika perlu
        $payment = $examination->payments()->latest()->first();

        return view('patient.examination.payment', compact('examination', 'payment'));
    }

    private function formatPhoneNumber($phoneNumber)
    {
        // Remove all non-numeric characters
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

        // Convert international format to local format
        if (substr($phoneNumber, 0, 2) === '62') {
            $phoneNumber = '0' . substr($phoneNumber, 2);
        }

        // Ensure it starts with 08
        if (substr($phoneNumber, 0, 2) !== '08') {
            throw new \InvalidArgumentException('Nomor telepon harus dimulai dengan 08');
        }

        return $phoneNumber;
    }
}