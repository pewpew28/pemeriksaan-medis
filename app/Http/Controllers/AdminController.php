<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use App\Models\Patient;
use App\Models\User;
use App\Models\ServiceItem;
use App\Models\ServiceCategory; // Tambah model baru
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

    // ===== SERVICE CATEGORY MANAGEMENT =====

    public function indexServiceCategories(Request $request)
    {
        $query = ServiceCategory::withCount('serviceItems');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $status = $request->status === 'active' ? true : false;
            $query->where('is_active', $status);
        }

        $sortBy = $request->get('sort_by', 'sort_order');
        $sortOrder = $request->get('sort_order', 'asc');
        $allowedSorts = ['name', 'code', 'sort_order', 'is_active', 'created_at'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'sort_order';
        }

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $query->orderBy($sortBy, $sortOrder);
        $categories = $query->paginate($request->get('per_page', 10));

        $statistics = [
            'totalCategories' => ServiceCategory::count(),
            'activeCategories' => ServiceCategory::where('is_active', true)->count(),
            'inactiveCategories' => ServiceCategory::where('is_active', false)->count(),
        ];

        return view('staff.service.categories.index', array_merge(compact('categories'), $statistics));
    }

    public function createServiceCategory()
    {
        return view('staff.service.categories.create');
    }

    public function storeServiceCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:service_categories,name',
            'code' => 'nullable|string|max:10|unique:service_categories,code',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        ServiceCategory::create($validated);

        return redirect()->route('staff.service.categories.index')
            ->with('success', 'Kategori layanan berhasil ditambahkan!');
    }

    public function showServiceCategory(ServiceCategory $category)
    {
        $category->load('serviceItems');

        $statistics = [
            'totalServiceItems' => $category->serviceItems->count(),
            'activeServiceItems' => $category->serviceItems->where('is_active', true)->count(),
            'totalRevenue' => $category->serviceItems->sum(function ($item) {
                return $item->examinations->where('payment_status', 'paid')->sum('final_price');
            }),
        ];
        // dd($statistics);

        return view('staff.service.categories.show', compact('category', 'statistics'));
    }

    public function editServiceCategory(ServiceCategory $category)
    {
        return view('staff.service.categories.edit', compact('category'));
    }

    public function updateServiceCategory(Request $request, ServiceCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:service_categories,name,' . $category->id,
            'code' => 'nullable|string|max:10|unique:service_categories,code,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $category->update($validated);

        return redirect()->route('staff.service.categories.index')
            ->with('success', 'Kategori layanan berhasil diperbarui!');
    }

    public function destroyServiceCategory(ServiceCategory $category)
    {
        // Check if category has service items
        if ($category->serviceItems()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki layanan terkait.');
        }

        $category->delete();

        return redirect()->route('staff.service.categories.index')
            ->with('success', 'Kategori layanan berhasil dihapus!');
    }

    // ===== SERVICE ITEM MANAGEMENT (UPDATED) =====

    public function indexServiceItems(Request $request)
    {
        $query = ServiceItem::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $status = $request->status === 'active' ? true : false;
            $query->where('is_active', $status);
        }

        $sortBy = $request->get('sort_by', 'sort_order');
        $sortOrder = $request->get('sort_order', 'asc');
        $allowedSorts = ['name', 'code', 'price', 'sort_order', 'is_active', 'created_at'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'sort_order';
        }

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'asc';
        }

        $query->orderBy($sortBy, $sortOrder);
        $serviceItems = $query->paginate($request->get('per_page', 10));

        $categories = ServiceCategory::where('is_active', true)->get();

        $statistics = [
            'totalServiceItems' => ServiceItem::count(),
            'activeServiceItems' => ServiceItem::where('is_active', true)->count(),
            'inactiveServiceItems' => ServiceItem::where('is_active', false)->count(),
            'averagePrice' => ServiceItem::where('is_active', true)->avg('price') ?? 0,
        ];

        return view('staff.service.index', array_merge(
            compact('serviceItems', 'categories'),
            $statistics
        ));
    }

    public function createServiceItem()
    {
        $categories = ServiceCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('staff.service.create', compact('categories'));
    }

    public function storeServiceItem(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:service_categories,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'normal_range' => 'nullable|string|max:255',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        ServiceItem::create($validated);

        return redirect()->route('staff.service.index')
            ->with('success', 'Layanan berhasil ditambahkan!');
    }

    public function showServiceItem(ServiceItem $serviceItem)
    {
        $serviceItem->load(['category', 'examinations.patient']);

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
        $categories = ServiceCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('staff.service.edit', compact('serviceItem', 'categories'));
    }

    public function updateServiceItem(Request $request, ServiceItem $serviceItem)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:service_categories,id',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'normal_range' => 'nullable|string|max:255',
            'sort_order' => 'integer|min:0',
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
        $query = Examination::with(['patient', 'serviceItem.category']);

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

        if (Auth::user()->role === "perawat") {
            $query->whereDate('scheduled_date', now()->toDateString());
            $query->whereIn('status', ['scheduled', 'in_progress', 'completed']);
        }

        if (Auth::user()->role === "cs") {
            $query->whereIn('status', ['created', 'pending_cash_payment', 'scheduled']);
        }

        $examinations = $query->paginate($request->get('per_page', 10));
        // dd($examinations); // Comment out dd() for production

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

    public function showPaymentCashForm($examinationId)
    {

        $examination = Examination::find($examinationId);
        return view('staff.examinations.payment', compact('examination'));
    }

    public function updateStatus($examinationId, Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'status' => 'required|string|in:scheduled,in_progress,completed,cancelled',
                'notes' => 'nullable|string|max:500'
            ]);

            // Cari examination berdasarkan ID
            $examination = Examination::find($examinationId);

            if (!$examination) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pemeriksaan tidak ditemukan!'
                ], 404);
            }

            // Validasi status transition (opsional - sesuaikan dengan business logic)
            $validTransitions = $this->getValidStatusTransitions($examination->status);

            if (!in_array($request->status, $validTransitions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status tidak dapat diubah dari ' . $examination->status . ' ke ' . $request->status
                ], 400);
            }

            // Simpan status lama untuk log
            $oldStatus = $examination->status;

            // Update examination
            $examination->update([
                'status' => $request->status,
                'notes' => $request->notes ?? $examination->notes,
                'updated_at' => now()
            ]);

            // Log activity (opsional)
            Log::info('Examination status updated', [
                'examination_id' => $examinationId,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'updated_by' => auth()->user()->id,
                'notes' => $request->notes
            ]);

            // Optional: Trigger notifications atau events
            // event(new ExaminationStatusUpdated($examination, $oldStatus));

            return response()->json([
                'success' => true,
                'message' => 'Status pemeriksaan berhasil diupdate!',
                'data' => [
                    'examination_id' => $examination->id,
                    'old_status' => $oldStatus,
                    'new_status' => $examination->status,
                    'patient_name' => $examination->patient->name ?? 'Unknown'
                ]
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid!',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Log error
            Log::error('Error updating examination status', [
                'examination_id' => $examinationId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate status pemeriksaan!'
            ], 500);
        }
    }

    /**
     * Get valid status transitions based on current status
     */
    private function getValidStatusTransitions($currentStatus)
    {
        $transitions = [
            'created' => ['pending_payment', 'pending_cash_payment', 'cancelled'],
            'pending_payment' => ['paid', 'expired_payment', 'cancelled'],
            'pending_cash_payment' => ['paid', 'cancelled'],
            'paid' => ['scheduled', 'cancelled'],
            'scheduled' => ['in_progress', 'cancelled'],
            'in_progress' => ['completed', 'cancelled'],
            'completed' => [], // Final status
            'cancelled' => [], // Final status
            'expired_payment' => ['pending_payment', 'cancelled']
        ];

        return $transitions[$currentStatus] ?? [];
    }

    public function showExaminationDetail(Examination $examination)
    {
        $examination->load(['patient', 'serviceItem.category']);
        return view('staff.examinations.show', compact('examination'));
    }

    public function showUploadResultForm(Examination $examination)
    {
        return view('staff.examinations.upload_result_form', compact('examination'));
    }

    public function uploadResult(Request $request, Examination $examination)
    {
        $request->validate([
            'result_file' => 'required|file|mimes:pdf|max:10240',
        ]);

        try {
            $examination->clearMediaCollection('results');

            $mediaItem = $examination
                ->addMediaFromRequest('result_file')
                ->usingFileName(uniqid() . '_result.' . $request->file('result_file')->getClientOriginalExtension())
                ->usingName('Hasil Pemeriksaan')
                ->toMediaCollection('results');

            $examination->update([
                'result_available' => true,
                'status' => 'completed',
            ]);

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
