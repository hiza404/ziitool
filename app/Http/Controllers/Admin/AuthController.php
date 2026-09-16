<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show admin login view.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Authenticate admin credentials.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if (! Auth::user()->is_admin) {
                Auth::logout();

                return back()->with('error', 'Tài khoản của bạn không có quyền truy cập quản trị.');
            }

            return redirect()->intended(route('admin.dashboard'))->with('success', 'Đăng nhập trang quản trị thành công!');
        }

        return back()->withInput($request->only('email'))->with('error', 'Email hoặc mật khẩu không chính xác.');
    }

    /**
     * Log out admin.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Bạn đã đăng xuất an toàn.');
    }
}
