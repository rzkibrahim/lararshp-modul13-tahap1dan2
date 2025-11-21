<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class isPemilik
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log request
        Log::info('isPemilik middleware called', [
            'url' => $request->url(),
            'method' => $request->method()
        ]);

        // Cek apakah user sudah login
        if (!Auth::check()) {
            Log::warning('User not authenticated in isPemilik middleware');
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Ambil role dari session
        $userRole = session('user_role');
        $userId = session('user_id');

        // Log session data
        Log::info('isPemilik - Session data', [
            'user_id' => $userId,
            'user_role' => $userRole,
            'user_name' => session('user_name'),
            'all_session' => session()->all()
        ]);

        // Debug: Uncomment baris ini untuk melihat session di browser
        // dd([
        //     'auth_check' => Auth::check(),
        //     'auth_user' => Auth::user(),
        //     'session_all' => session()->all(),
        //     'user_role' => $userRole
        // ]);

        // Cek apakah user adalah pemilik (role ID = 5)
        if ($userRole == 5) {
            Log::info('User is pemilik, allowing access');
            return $next($request);
        }

        // Log unauthorized access
        Log::warning('User not authorized as pemilik', [
            'user_id' => $userId,
            'user_role' => $userRole,
            'expected_role' => 5
        ]);

        // Jika bukan pemilik, redirect ke home dengan error
        return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
    }
}