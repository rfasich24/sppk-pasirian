<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman login atau memproses post request login sekaligus
    public function login(Request $request)
    {
        if ($request->isMethod('get')) {
            if (session()->has('admin_authenticated')) {
                return redirect()->route('admin.sekolah.index');
            }
            return view('pages.admin.login');
        }

        // Proses Post-Authentication
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $admin = Admin::where('username', $request->username)->first();

        // Validasi kecocokan password via Bcrypt Hash
        if ($admin && Hash::check($request->password, $admin->password)) {
            session([
                'admin_authenticated' => true,
                'admin_username' => $admin->username,
                'admin_name' => 'Administrator SPPK'
            ]);
            return redirect()->route('admin.sekolah.index');
        }

        return redirect()->back()->withInput()->with('error', 'Kombinasi username dan kata sandi salah. Silakan coba kembali.');
    }

    // Menghancurkan session token admin
    public function logout()
    {
        session()->forget(['admin_authenticated', 'admin_username', 'admin_name']);
        return redirect()->route('smart.rekomendasi')->with('success', 'Sesi admin berhasil ditutup.');
    }
}
