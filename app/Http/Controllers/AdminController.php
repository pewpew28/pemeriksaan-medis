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
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $patient->load(['examinations' => function ($query) {
            $query->latest('created_at');
        }]);

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
            'pendingPayment' => Examination::whereIn('status', ['pending_payment', 'pending_cash_payment', 'created'])->count(),
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

    public function createExamination()
    {
        $patient = Patient::get();
        $serviceCategories = ServiceCategory::with('serviceItems')->where('is_active', true)->get();
        return view('staff.examinations.create', compact('patient', 'serviceCategories'));
    }

    public function storeExamination(Request $request)
    {
        // Log incoming request data
        Log::info('StoreExamination - Request received', [
            'request_data' => $request->all(),
            'user_id' => auth()->id(),
            'timestamp' => now()
        ]);

        try {
            // Validate request data
            Log::info('StoreExamination - Starting validation');

            $validated = $request->validate([
                'patient_id' => 'required|exists:patients,id',
                'service_item_id' => 'required|exists:service_items,id',
                'scheduled_date' => 'required|date|after_or_equal:today',
                'scheduled_time' => 'required|date_format:H:i',
                'pickup_requested' => 'sometimes|boolean',
                'pickup_address' => 'nullable|string|max:500',
                'pickup_location_map' => 'nullable|string|max:1000',
                'pickup_time' => 'nullable|date_format:H:i',
                'notes' => 'nullable|string|max:1000',
                'final_price' => 'required|numeric|min:0',
                'payment_method' => 'required|in:cash,online',
            ]);

            Log::info('StoreExamination - Validation successful', [
                'validated_data' => $validated
            ]);

            DB::beginTransaction();
            Log::info('StoreExamination - Database transaction started');

            // Generate custom examination ID
            Log::info('StoreExamination - Generating examination ID');
            $examinationId = $this->generateExaminationId(
                $validated['patient_id'],
                $validated['service_item_id']
            );

            Log::info('StoreExamination - Generated examination ID', [
                'examination_id' => $examinationId
            ]);

            // Get service item details
            Log::info('StoreExamination - Fetching service item details', [
                'service_item_id' => $validated['service_item_id']
            ]);

            $serviceItem = ServiceItem::findOrFail($validated['service_item_id']);

            Log::info('StoreExamination - Service item retrieved', [
                'service_item' => [
                    'id' => $serviceItem->id,
                    'name' => $serviceItem->name
                ]
            ]);

            // Combine scheduled date and time
            Log::info('StoreExamination - Processing scheduled datetime', [
                'scheduled_date' => $validated['scheduled_date'],
                'scheduled_time' => $validated['scheduled_time']
            ]);

            $scheduledDateTime = Carbon::createFromFormat(
                'Y-m-d H:i',
                $validated['scheduled_date'] . ' ' . $validated['scheduled_time']
            );

            Log::info('StoreExamination - Scheduled datetime created', [
                'scheduled_datetime' => $scheduledDateTime->toDateTimeString()
            ]);

            // Handle pickup request and time
            $pickupRequested = isset($validated['pickup_requested']) ? (bool)$validated['pickup_requested'] : false;
            $pickupDateTime = null;

            Log::info('StoreExamination - Processing pickup request', [
                'pickup_requested_raw' => $validated['pickup_requested'] ?? 'not_set',
                'pickup_requested_bool' => $pickupRequested,
                'pickup_time' => $validated['pickup_time'] ?? 'not_set'
            ]);

            if ($pickupRequested && !empty($validated['pickup_time'])) {
                Log::info('StoreExamination - Processing pickup datetime', [
                    'pickup_time' => $validated['pickup_time']
                ]);

                try {
                    $pickupDateTime = Carbon::createFromFormat(
                        'Y-m-d H:i',
                        $validated['scheduled_date'] . ' ' . $validated['pickup_time']
                    );

                    Log::info('StoreExamination - Pickup datetime created', [
                        'pickup_datetime' => $pickupDateTime->toDateTimeString()
                    ]);
                } catch (\Exception $e) {
                    Log::error('StoreExamination - Failed to create pickup datetime', [
                        'error' => $e->getMessage(),
                        'pickup_time' => $validated['pickup_time'],
                        'scheduled_date' => $validated['scheduled_date']
                    ]);
                    throw new \Exception('Format waktu pickup tidak valid: ' . $e->getMessage());
                }
            }

            // Prepare examination data
            $examinationData = [
                'id' => $examinationId,
                'patient_id' => $validated['patient_id'],
                'service_item_id' => $validated['service_item_id'],
                'scheduled_date' => $validated['scheduled_date'],
                'scheduled_time' => $scheduledDateTime,
                'pickup_requested' => $pickupRequested,
                'pickup_address' => $validated['pickup_address'] ?? null,
                'pickup_location_map' => $validated['pickup_location_map'] ?? null,
                'pickup_time' => $pickupDateTime,
                'status' => 'created',
                'notes' => $validated['notes'] ?? null,
                'result_available' => false,
                'payment_status' => Examination::PAYMENT_PENDING,
                'payment_method' => $validated['payment_method'],
                'final_price' => $validated['final_price'],
            ];

            Log::info('StoreExamination - Creating examination record', [
                'examination_data' => $examinationData
            ]);

            // Create examination
            $examination = Examination::create($examinationData);

            Log::info('StoreExamination - Examination created successfully', [
                'examination_id' => $examination->id,
                'patient_id' => $examination->patient_id
            ]);

            DB::commit();
            Log::info('StoreExamination - Database transaction committed');

            // Determine redirect route based on payment method
            $successMessage = 'Pemeriksaan berhasil dibuat dengan ID: ' . $examinationId;

            if ($validated['payment_method'] === 'cash') {
                Log::info('StoreExamination - Redirecting to cash payment form', [
                    'examination_id' => $examinationId
                ]);

                return redirect()
                    ->route('staff.examinations.payment.form', ['examinationId' => $examination->id])
                    ->with('success', $successMessage . '. Silakan lakukan pembayaran tunai.');
            } else {
                Log::info('StoreExamination - Redirecting to online payment', [
                    'examination_id' => $examinationId
                ]);

                return redirect()
                    ->route('payments.form', ['examination' => $examination])
                    ->with('success', $successMessage . '. Silakan lakukan pembayaran online.');
            }
        } catch (ValidationException $e) {
            Log::error('StoreExamination - Validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Data yang dimasukkan tidak valid. Silakan periksa kembali.');
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('StoreExamination - Exception occurred', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat pemeriksaan: ' . $e->getMessage());
        }
    }

    private function generateExaminationId($userId, $serviceItemId)
    {
        // Batasi user_id dan service_item_id untuk menghemat digit
        $userIdPart = str_pad($userId % 999, 2, '0', STR_PAD_LEFT); // 2 digit (01-99)
        $serviceItemPart = str_pad($serviceItemId % 99, 2, '0', STR_PAD_LEFT); // 2 digit (01-99)

        // Tanggal dalam format YYMM (4 digit) atau YMD (3-4 digit)
        $datePart = date('ymd'); // 6 digit: 250618 untuk 18 Juni 2025

        // Ambil 4 digit terakhir dari tanggal (MMDD)
        $shortDatePart = substr($datePart, 2, 4); // 0618

        // Buat base ID (6 digit)
        $baseId = $userIdPart . $serviceItemPart . $shortDatePart;

        // Cek apakah ID sudah ada, jika ya tambahkan sequence number
        $finalId = $baseId;
        $sequence = 1;

        while (Examination::where('id', $finalId)->exists()) {
            if (strlen($baseId . $sequence) <= 9) {
                $finalId = $baseId . $sequence;
            } else {
                // Jika melebihi 9 digit, gunakan format yang lebih pendek
                $shortBaseId = $userIdPart . $serviceItemPart . substr($shortDatePart, 2, 2); // 4 digit
                $finalId = $shortBaseId . str_pad($sequence, 2, '0', STR_PAD_LEFT);
            }
            $sequence++;

            // Failsafe: jika sequence terlalu tinggi, gunakan timestamp
            if ($sequence > 999) {
                $finalId = $userIdPart . $serviceItemPart . substr(time(), -3);
                break;
            }
        }

        return $finalId;
    }

    public function showPaymentCashForm($examinationId)
    {

        $examination = Examination::with('serviceItem')->find($examinationId);
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

            // Kirim notifikasi email jika pasien memiliki email
            $this->sendResultNotificationEmail($examination);

            Log::info('Result uploaded successfully', [
                'examination_id' => $examination->id,
                'media_id' => $mediaItem->id,
                'uploaded_by' => auth()->id()
            ]);

            return redirect()->route('staff.examinations.index')
                ->with('success', 'Hasil pemeriksaan berhasil diunggah, status diperbarui, dan notifikasi email telah dikirim!');
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

    /**
     * Send email notification when examination result is available
     */
    private function sendResultNotificationEmail(Examination $examination)
    {
        try {
            // Load relasi yang diperlukan
            $examination->load(['patient', 'serviceItem.category']);

            $patient = $examination->patient;

            // Cek apakah pasien memiliki email
            if (!$patient || !$patient->email) {
                Log::info('Email notification not sent - patient email not available', [
                    'examination_id' => $examination->id,
                    'patient_id' => $patient->id ?? null
                ]);
                return;
            }

            // Kirim email
            \Illuminate\Support\Facades\Mail::to($patient->email)
                ->send(new \App\Mail\ExaminationResultAvailable($examination));

            Log::info('Result notification email sent successfully', [
                'examination_id' => $examination->id,
                'patient_id' => $patient->id,
                'patient_email' => $patient->email,
                'sent_at' => now()
            ]);
        } catch (\Exception $e) {
            // Jangan throw exception agar proses upload tetap berhasil
            // Hanya log error saja
            Log::error('Failed to send result notification email', [
                'examination_id' => $examination->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
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

            // Kirim notifikasi email
            $this->sendResultNotificationEmail($examination);

            return redirect()->back()
                ->with('success', 'Hasil pemeriksaan berhasil ditandai sebagai tersedia dan notifikasi email telah dikirim.');
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
        $totalAdmin = User::where('role', 'admin')->count();
        $totalStaff = User::whereIn('role', ['perawat', 'cs'])->count();
        $totalPasien = User::where('role', 'pasien')->count();
        return view('staff.user.index', compact('users', 'totalAdmin', 'totalStaff', 'totalPasien'));
    }

    public function editUserRole(User $user)
    {
        $roles = Role::all();
        return view('staff.user.edit_role', compact('user', 'roles'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        // dd($request->all());
        $validated = $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user->syncRoles($validated['role']);
        $user->update(['role' => $validated['role']]);

        return redirect()->route('staff.users.index')
            ->with('success', 'Peran pengguna berhasil diperbarui!');
    }
}
