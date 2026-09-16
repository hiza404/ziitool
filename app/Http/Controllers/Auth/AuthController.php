<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show client login form.
     */
    public function showLoginForm(Request $request): View
    {
        return view('auth.login', [
            'redirect' => $request->query('redirect'),
        ]);
    }

    /**
     * Handle client login request.
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

            $user = Auth::user();
            if ($user && $user->isPro()) {
                session(['is_pro_member' => true]);
            }

            $redirectTo = $request->input('redirect');
            if ($redirectTo && str_starts_with($redirectTo, '/')) {
                return redirect($redirectTo)->with('success', 'Đăng nhập thành công! Chào mừng bạn trở lại, '.$user->name.'.');
            }

            return redirect()->intended(route('home'))->with('success', 'Đăng nhập thành công! Chào mừng bạn trở lại, '.$user->name.'.');
        }

        return back()->withErrors([
            'email' => 'Địa chỉ email hoặc mật khẩu không chính xác.',
        ])->withInput($request->only('email', 'redirect'));
    }

    /**
     * Show client registration form.
     */
    public function showRegisterForm(Request $request): View
    {
        return view('auth.register', [
            'redirect' => $request->query('redirect'),
        ]);
    }

    /**
     * Handle client registration request.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => false,
            'is_pro' => false,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        $redirectTo = $request->input('redirect');
        if ($redirectTo && str_starts_with($redirectTo, '/')) {
            return redirect($redirectTo)->with('success', 'Tạo tài khoản thành công! Bạn có thể tiếp tục thao tác.');
        }

        return redirect()->route('home')->with('success', 'Chào mừng bạn đến với ZiiTool! Tài khoản của bạn đã sẵn sàng.');
    }

    /**
     * Handle client logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'Bạn đã đăng xuất tài khoản thành công.');
    }
}
