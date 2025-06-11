<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Penting: import Auth

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard sesuai dengan peran pengguna yang login.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin') || $user->hasRole('cs') || $user->hasRole('perawat')) {
            // Jika user adalah admin, CS, atau perawat, arahkan ke dashboard staf
            return redirect()->route('staff.dashboard');
        } elseif ($user->hasRole('pasien')) {
            // Jika user adalah pasien, arahkan ke dashboard pasien
            return redirect()->route('pasien.dashboard');
        } else {
            // Fallback jika peran tidak dikenali (jarang terjadi jika peran dikelola dengan baik)
            // Bisa menampilkan halaman dashboard umum atau error
            return view('dashboard'); // Sesuaikan dengan view dashboard umum Anda
        }
    }
}