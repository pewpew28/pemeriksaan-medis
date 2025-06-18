<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\Patient;
use App\Models\User;
use App\Models\ServiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PatientsExport;
use App\Exports\ExaminationsExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    // ===== DASHBOARD =====

    public function dashboard()
    {
        $totalPatients = Patient::count();
        $pendingExaminations = Examination::whereIn('status', ['pending_payment', 'pending_cash_payment'])->count();
        $completedExaminations = Examination::whereIn('status', ['completed', 'paid'])->count();
        $totalUsers = User::count();
        $totalServiceItems = ServiceItem::count();
        $activeServiceItems = ServiceItem::where('is_active', true)->count();

        return view('staff.dashboard', compact(
            'totalPatients',
            'pendingExaminations',
            'completedExaminations',
            'totalUsers',
            'totalServiceItems',
            'activeServiceItems'
        ));
    }

    // ===== PATIENT MANAGEMENT =====

    public function indexPatients(Request $request)
    {
        Log::info('indexPatients called', [
            'method' => $request->method(),
            'search' => $request->get('search'),
            'page' => $request->get('page')
        ]);

        $query = Patient::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%');
            });
        }

        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        $allowedSorts = ['name', 'age', 'date_of_birth', 'gender', 'created_at'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'name';
        }

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $query->orderBy($sortBy, $sortOrder);
        $patients = $query->paginate($request->get('per_page', 10));

        $statistics = [
            'totalPatients' => Patient::count(),
            'registeredPatients' => Patient::whereNotNull('user_id')->count(),
            'newPatientsThisMonth' => Patient::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count()
        ];

        return view('staff.patients.index', array_merge(compact('patients'), $statistics));
    }

    public function createPatient()
    {
        return view('staff.patients.create');
    }

    public function storePatient(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:patients,email',
            'phone_number' => 'nullable|string|max:20',
            'age' => 'required|integer|min:1',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Laki-laki,Perempuan,Lainnya',
            'user_id' => 'nullable|exists:users,id',
        ]);

        Patient::create($validated);

        return redirect()->route('staff.patients.index')
            ->with('success', 'Data pasien berhasil ditambahkan!');
    }

    public function showPatient(Patient $patient)
    {
        $patient->load('examinations');
        return view('staff.patients.show', compact('patient'));
    }

    public function editPatient(Patient $patient)
    {
        return view('staff.patients.edit', compact('patient'));
    }

    public function updatePatient(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:patients,email,' . $patient->id,
            'phone_number' => 'nullable|string|max:20',
            'age' => 'required|integer|min:1',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Laki-laki,Perempuan,Lainnya',
        ]);

        $patient->update($validated);

        return redirect()->route('staff.patients.index')
            ->with('success', 'Data pasien berhasil diperbarui!');
    }

    public function destroyPatient(Patient $patient)
    {
        $patient->delete();

        return redirect()->route('staff.patients.index')
            ->with('success', 'Data pasien berhasil dihapus!');
    }

    // ===== SERVICE ITEM MANAGEMENT =====

    public function indexServiceItems(Request $request)
    {
        $query = ServiceItem::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $status = $request->status === 'active' ? true : false;
            $query->where('is_active', $status);
        }

        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        $allowedSorts = ['name', 'price', 'is_active', 'created_at'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'name';
        }

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $query->orderBy($sortBy, $sortOrder);
        $serviceItems = $query->paginate($request->get('per_page', 10));

        $statistics = [
            'totalServiceItems' => ServiceItem::count(),
            'activeServiceItems' => ServiceItem::where('is_active', true)->count(),
            'inactiveServiceItems' => ServiceItem::where('is_active', false)->count(),
            'averagePrice' => ServiceItem::where('is_active', true)->avg('price') ?? 0,
        ];

        return view('staff.service.index', array_merge(compact('serviceItems'), $statistics));
    }

    public function createServiceItem()
    {
        return view('staff.service.create');
    }

    public function storeServiceItem(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        // Set default value for is_active if not provided
        $validated['is_active'] = $request->boolean('is_active', true);

        ServiceItem::create($validated);

        return redirect()->route('staff.service.index')
            ->with('success', 'Layanan berhasil ditambahkan!');
    }

    public function showServiceItem(ServiceItem $serviceItem)
    {
        $serviceItem->load('examinations.patient');
        
        $statistics = [
            'totalExaminations' => $serviceItem->examinations->count(),
            'completedExaminations' => $serviceItem->examinations->where('status', 'completed')->count(),
            'totalRevenue' => $serviceItem->examinations->where('payment_status', 'paid')->sum('final_price'),
            'averagePrice' => $serviceItem->examinations->where('payment_status', 'paid')->avg('final_price') ?? 0,
        ];

        return view('staff.service.show', compact('serviceItem', 'statistics'));
    }

    public function editServiceItem(ServiceItem $serviceItem)
    {
        return view('staff.service.edit', compact('serviceItem'));
    }

    public function updateServiceItem(Request $request, ServiceItem $serviceItem)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $serviceItem->update($validated);

        return redirect()->route('staff.service.index')
            ->with('success', 'Layanan berhasil diperbarui!');
    }

    public function destroyServiceItem(ServiceItem $serviceItem)
    {
        // Check if service item has examinations
        if ($serviceItem->examinations()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Layanan tidak dapat dihapus karena masih memiliki data pemeriksaan terkait.');
        }

        $serviceItem->delete();

        return redirect()->route('staff.service.index')
            ->with('success', 'Layanan berhasil dihapus!');
    }

    public function toggleServiceItemStatus(ServiceItem $serviceItem)
    {
        $serviceItem->update(['is_active' => !$serviceItem->is_active]);

        $status = $serviceItem->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->back()
            ->with('success', "Layanan berhasil {$status}!");
    }

    // ===== EXAMINATION MANAGEMENT =====

    public function indexExaminations(Request $request)
    {
        $query = Examination::with(['patient', 'serviceItem']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($patientQuery) use ($search) {
                    $patientQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('phone_number', 'like', '%' . $search . '%');
                })->orWhere('id', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('payment_method', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('scheduled_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('scheduled_date', '<=', $request->date_to);
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedSorts = ['created_at', 'scheduled_date', 'status', 'payment_status', 'final_price'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        $query->orderBy($sortBy, $sortOrder);
        $examinations = $query->paginate($request->get('per_page', 10));

        $statistics = [
            'totalExaminations' => Examination::count(),
            'pendingPayment' => Examination::whereIn('status', ['pending_payment', 'pending_cash_payment'])->count(),
            'completed' => Examination::where('status', 'completed')->count(),
            'scheduled' => Examination::where('status', 'scheduled')->count(),
            'totalRevenue' => Examination::where('payment_status', 'paid')->sum('final_price'),
            'thisMonthExaminations' => Examination::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count()
        ];

        $statusOptions = [
            'created',
            'pending_payment',
            'pending_cash_payment',
            'paid',
            'expired_payment',
            'scheduled',
            'in_progress',
            'completed',
            'cancelled'
        ];

        $paymentStatusOptions = ['pending', 'paid', 'failed'];

        return view('staff.examinations.index', array_merge(
            compact('examinations', 'statusOptions', 'paymentStatusOptions'),
            $statistics
        ));
    }

    public function showExaminationDetail(Examination $examination)
    {
        $examination->load(['patient', 'serviceItem']);
        return view('staff.examinations.show', compact('examination'));
    }

    public function showUploadResultForm(Examination $examination)
    {
        return view('staff.examinations.upload_result_form', compact('examination'));
    }

    public function uploadResult(Request $request, Examination $examination)
    {
        $request->validate([
            'result_file' => 'required|file|mimes:pdf|max:10240', // tambahkan 'file' rule
        ]);

        try {
            // Hapus media yang sudah ada di collection 'results'
            $examination->clearMediaCollection('results');

            // Upload file baru
            $mediaItem = $examination
                ->addMediaFromRequest('result_file')
                ->usingFileName(uniqid() . '_result.' . $request->file('result_file')->getClientOriginalExtension())
                ->usingName('Hasil Pemeriksaan') // tambahkan nama yang lebih deskriptif
                ->toMediaCollection('results');

            // Update status examination
            $examination->update([
                'result_available' => true,
                'status' => 'completed',
            ]);

            // Log aktivitas (opsional)
            Log::info('Result uploaded successfully', [
                'examination_id' => $examination->id,
                'media_id' => $mediaItem->id,
                'uploaded_by' => auth()->id()
            ]);

            return redirect()->route('staff.examinations.index')
                ->with('success', 'Hasil pemeriksaan berhasil diunggah dan status diperbarui!');
        } catch (\Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ukuran file terlalu besar. Maksimal 10MB.');
        } catch (\Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'File tidak ditemukan atau rusak.');
        } catch (\Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'File tidak dapat ditambahkan: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Upload result failed', [
                'examination_id' => $examination->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengunggah file. Silakan coba lagi.');
        }
    }

    public function markResultAvailable(Examination $examination)
    {
        try {
            // Validasi apakah ada file hasil yang sudah diupload
            if (!$examination->hasMedia('results')) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menandai hasil tersedia karena belum ada file yang diunggah.');
            }

            $examination->update(['result_available' => true]);

            return redirect()->back()
                ->with('success', 'Hasil pemeriksaan berhasil ditandai sebagai tersedia.');
        } catch (\Exception $e) {
            Log::error('Mark result available failed', [
                'examination_id' => $examination->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui status.');
        }
    }

    // ===== DATA EXPORT =====

    public function exportPatients()
    {
        return Excel::download(new PatientsExport, 'data_pasien.xlsx');
    }

    public function exportExaminations()
    {
        return Excel::download(new ExaminationsExport, 'data_pemeriksaan.xlsx');
    }

    // ===== USER & ROLE MANAGEMENT =====

    public function indexUsers()
    {
        $users = User::with('roles')->paginate(10);
        return view('staff.users.index', compact('users'));
    }

    public function editUserRole(User $user)
    {
        $roles = Role::all();
        return view('staff.users.edit_role', compact('user', 'roles'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user->syncRoles($validated['roles']);
        $user->update(['role' => $validated['roles'][0]]);

        return redirect()->route('staff.users.index')
            ->with('success', 'Peran pengguna berhasil diperbarui!');
    }
}