<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Placeholder Tahap C: hanya menampilkan halaman login.
 * Belum menyentuh database/Auth sama sekali, jadi aman dijalankan tanpa migrasi.
 */
class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'max:100'],
        ], [
            'login.required' => __('Email atau username wajib diisi.'),
            'password.required' => __('Kata sandi wajib diisi.'),
        ]);

        // TODO (Tahap C1): ganti dengan Auth::attempt + throttle + redirect per peran.
        return back()
            ->withInput($request->only('login'))
            ->with('info', __('Fitur masuk belum aktif. Akun peserta akan dibuka dalam waktu dekat.'));
    }
}