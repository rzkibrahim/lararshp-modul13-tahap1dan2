<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle authenticated user - INI YANG PERLU DIPERBAIKI
     */
    protected function authenticated(Request $request, $user)
    {
        // Karena kita menggunakan tabel custom, kita perlu mengambil data user lagi
        $userData = DB::table('user')
            ->where('iduser', $user->iduser)
            ->first();

        if (!$userData) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Data user tidak ditemukan.');
        }

        // Ambil role user
        $roleUser = DB::table('role_user')
            ->join('role', 'role_user.idrole', '=', 'role.idrole')
            ->where('role_user.iduser', $userData->iduser)
            ->where('role_user.status', 1)
            ->first();

        if (!$roleUser) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'User tidak memiliki role yang valid.');
        }

        // Set session data
        session([
            'user_id' => $userData->iduser,
            'user_name' => $userData->nama,
            'user_email' => $userData->email,
            'user_role' => $roleUser->idrole,
            'user_role_name' => $roleUser->nama_role,
        ]);

        // Redirect berdasarkan role
        return $this->redirectBasedOnRole($roleUser->idrole);
    }

    /**
     * Redirect berdasarkan role
     */
    protected function redirectBasedOnRole($roleId)
    {
        switch ($roleId) {
            case 1: return redirect()->route('admin.dashboard');
            case 2: return redirect()->route('dokter.dashboard');
            case 3: return redirect()->route('perawat.dashboard');
            case 4: return redirect()->route('resepsionis.dashboard');
            case 5: return redirect()->route('pemilik.dashboard');
            default: return redirect('/');
        }
    }

    /**
     * Get the login username to be used by the controller.
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Validate the user login request.
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Get the needed authorization credentials from the request.
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}