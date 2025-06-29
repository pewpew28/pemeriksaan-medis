<?php

use App\Http\Controllers\ProfileController; // Dari Laravel Breeze
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController; // Contoh jika butuh custom register
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AdminController; // Bisa menangani admin, cs, perawat
use App\Http\Controllers\DashboardController; // Untuk dashboard umum atau redirect
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// --- 1. Rute Umum & Landing Page ---
// Ini adalah halaman utama yang bisa diakses siapa saja
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rute Dashboard setelah login (Laravel Breeze default)
Route::get('/dashboard', [DashboardController::class, 'index']) // Buat DashboardController ini
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/receipt/{examinationId}', [PaymentController::class, 'getCashPaymentReceipt'])->name('receipt');
Route::get('payments/form/{examination}', [PaymentController::class, 'paymentOnlineForm'])->name('payments.form');
// Rute Profil (dari Laravel Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Otomatis mencakup rute login, register, password reset dari Breeze
require __DIR__ . '/auth.php';


// --- 2. Rute Khusus PASIEN ---
// Middleware: 'auth' (harus login) dan 'role:pasien' (harus punya role 'pasien')
Route::middleware(['auth', 'role:pasien'])->prefix('pasien')->name('pasien.')->group(function () {
    // Dashboard Pasien
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');

    // Profil Pasien
    Route::get('/profil', [PatientController::class, 'profile'])->name('profile');
    Route::put('/profil', [PatientController::class, 'updateProfile'])->name('profile.update');

    // Pendaftaran Pemeriksaan Baru
    Route::get('/pendaftaran-pemeriksaan', [PatientController::class, 'showRegistrationForm'])->name('examination.register.form');
    Route::post('/pendaftaran-pemeriksaan', [PatientController::class, 'storeRegistration'])->name('examination.register.store');

    // Melihat Daftar Pemeriksaan & Hasil
    Route::get('/pemeriksaan-saya', [PatientController::class, 'myExaminations'])->name('examinations.index');
    Route::get('/pemeriksaan-saya/{examination}', [PatientController::class, 'showExaminationDetail'])->name('examinations.show');

    // Mengunduh Hasil Pemeriksaan
    // Pastikan {examination} adalah model binding untuk Examination
    Route::get('/hasil-pemeriksaan/{examination}/unduh', [PatientController::class, 'downloadResult'])
        ->name('result.download');

    // Pembayaran (sederhana)
    Route::get('/pembayaran/{examination}', [PatientController::class, 'showPaymentDetails'])->name('payment.show');

    Route::get('/pembayaran/{examination}/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/pembayaran/{examination}/failed', [PaymentController::class, 'paymentFailed'])->name('payment.failed');
    Route::post('/payments/update-method', [PaymentController::class, 'updatePaymentMethod'])->name('payments.updateMethod');
    Route::post('/payments/confirm-cash/{examinationId}', [PaymentController::class, 'confirmCashPayment'])->name('payments.confirmCash');
    // Mungkin ada POST route untuk update status pembayaran (jika manual oleh pasien)
});


// --- 3. Rute Khusus PETUGAS (Admin, CS, Perawat) ---
// Middleware: 'auth' (harus login) dan 'role' dengan berbagai kombinasi
// Menggunakan 'staff' sebagai prefix umum untuk semua peran petugas
Route::middleware(['auth'])->prefix('staff')->name('staff.')->group(function () {

    // Dashboard Petugas Umum (bisa diakses admin, cs, perawat)
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard')
        ->middleware('role:admin|cs|perawat');

    // Manajemen Data Pasien
    // Hanya bisa diakses oleh Admin atau CS (Customer Service)
    Route::middleware('role:admin|cs|perawat')->group(function () {
        Route::get('/patients', [AdminController::class, 'indexPatients'])->name('patients.index');
        Route::get('/patients/create', [AdminController::class, 'createPatient'])->name('patients.create');
        Route::post('/patients', [AdminController::class, 'storePatient'])->name('patients.store');
        Route::get('/patients/{patient}', [AdminController::class, 'showPatient'])->name('patients.show');
        Route::get('/patients/{patient}/edit', [AdminController::class, 'editPatient'])->name('patients.edit');
        Route::put('/patients/{patient}', [AdminController::class, 'updatePatient'])->name('patients.update');
        Route::delete('/patients/{patient}', [AdminController::class, 'destroyPatient'])->name('patients.destroy');

        // Melihat daftar pemeriksaan pasien (untuk CS juga)
        Route::get('/examinations', [AdminController::class, 'indexExaminations'])->name('examinations.index');
        Route::get('/examinations/create', [AdminController::class, 'createExamination'])->name('examinations.create');
        Route::post('/examinations/store', [AdminController::class, 'storeExamination'])->name('examinations.store');
        Route::get('/examinations/payment/{examinationId}', [AdminController::class, 'showPaymentCashForm'])->name('examinations.payment.form');
        Route::get('/pembayaran/{examination}/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
        Route::get('/pembayaran/{examination}/failed', [PaymentController::class, 'paymentFailed'])->name('payment.failed');
        Route::post('/examinations/{examination}/payment/cash', [PaymentController::class, 'processCashPayment'])
            ->name('examinations.payment.cash');
        Route::get('/staff/payments/{examination}/receipt', [PaymentController::class, 'getCashPaymentReceipt'])
            ->name('payments.receipt');
        Route::patch('/examinations/{examinationId}/status', [AdminController::class, 'updateStatus'])->name('examination.status');
        Route::get('/examinations/{examination}', [AdminController::class, 'showExaminationDetail'])->name('examinations.show');

        // Service Categories Routes
        Route::get('service-categories', [AdminController::class, 'indexServiceCategories'])->name('service.categories.index');
        Route::get('service-categories/create', [AdminController::class, 'createServiceCategory'])->name('service.categories.create');
        Route::post('service-categories', [AdminController::class, 'storeServiceCategory'])->name('service.categories.store');
        Route::get('service-categories/{category}', [AdminController::class, 'showServiceCategory'])->name('service.categories.show');
        Route::get('service-categories/{category}/edit', [AdminController::class, 'editServiceCategory'])->name('service.categories.edit');
        Route::put('service-categories/{category}', [AdminController::class, 'updateServiceCategory'])->name('service.categories.update');
        Route::delete('service-categories/{category}', [AdminController::class, 'destroyServiceCategory'])->name('service.categories.destroy');

        // Service Items Routes (existing)
        Route::get('service-items', [AdminController::class, 'indexServiceItems'])->name('service.index');
        Route::get('service-items/create', [AdminController::class, 'createServiceItem'])->name('service.create');
        Route::post('service-items', [AdminController::class, 'storeServiceItem'])->name('service.store');
        Route::get('service-items/{serviceItem}', [AdminController::class, 'showServiceItem'])->name('service.show');
        Route::get('service-items/{serviceItem}/edit', [AdminController::class, 'editServiceItem'])->name('service.edit');
        Route::put('service-items/{serviceItem}', [AdminController::class, 'updateServiceItem'])->name('service.update');
        Route::delete('service-items/{serviceItem}', [AdminController::class, 'destroyServiceItem'])->name('service.destroy');
        Route::patch('service-items/{serviceItem}/toggle-status', [AdminController::class, 'toggleServiceItemStatus'])->name('service.toggle_status');
    });

    // Input dan Upload Hasil Pemeriksaan
    // Hanya bisa diakses oleh Admin atau Perawat
    Route::middleware('role:admin|perawat')->group(function () {
        Route::get('/examinations/{examination}/upload-result', [AdminController::class, 'showUploadResultForm'])->name('examinations.upload_result.form');
        Route::post('/examinations/{examination}/upload-result', [AdminController::class, 'uploadResult'])->name('examinations.upload_result.store');
        // Opsi: Menandai hasil sudah tersedia (jika tidak otomatis setelah upload)
        Route::post('/examinations/{examination}/mark-available', [AdminController::class, 'markResultAvailable'])->name('examinations.mark_available');
    });

    // Export Data (Laravel Excel)
    // Biasanya hanya Admin yang punya akses ini
    Route::middleware('role:admin')->group(function () {
        Route::get('/export/patients', [AdminController::class, 'exportPatients'])->name('export.patients');
        Route::get('/export/examinations', [AdminController::class, 'exportExaminations'])->name('export.examinations');
    });

    // Manajemen User/Role (khusus Admin)
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [AdminController::class, 'indexUsers'])->name('users.index');
        Route::get('/users/{user}/edit-role', [AdminController::class, 'editUserRole'])->name('users.edit_role');
        Route::put('/users/{user}/update-role', [AdminController::class, 'updateUserRole'])->name('users.update_role');
        // Tambahkan rute CRUD user jika dibutuhkan
    });
});
