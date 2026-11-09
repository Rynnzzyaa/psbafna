<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Log out current user.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }

    /**
     * Fast login switch for testing demo roles (Admin or Referrer).
     */
    public function demoLogin(string $role): RedirectResponse
    {
        if ($role === 'admin') {
            $user = User::where('role', 'admin')->first();
            if ($user) {
                Auth::login($user);

                return redirect()->route('admin.monitoring')->with('success', 'Masuk sebagai Admin Monitoring.');
            }
        } elseif ($role === 'referrer') {
            $user = User::where('role', 'referrer')->first();
            if ($user) {
                Auth::login($user);

                return redirect()->route('referrer.dashboard')->with('success', 'Masuk sebagai Pembawa Pelanggan: '.$user->name);
            }
        }

        return redirect()->route('login');
    }
}
