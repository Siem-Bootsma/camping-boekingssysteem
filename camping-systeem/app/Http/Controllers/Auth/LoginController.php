<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an incoming login request.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Only allow the configured admin email to stay logged in
            $adminEmail = env('ADMIN_EMAIL', 'admin@vuurvlieg.test');
            $user = Auth::user();

            if ($user && $user->email !== $adminEmail) {
                // logout non-admin users immediately and redirect to home
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => __('auth.failed', [], app()->getLocale()),
        ])->onlyInput('email');
    }
}
