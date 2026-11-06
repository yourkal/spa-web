<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            if ($role === 'admin') return redirect()->route('admin.dashboard');
            if ($role === 'bos')   return redirect()->route('bos.dashboard');
            return redirect()->route('customer.dashboard');
        }
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route(Auth::user()->isAdmin() ? 'admin.dashboard' : 'customer.dashboard');
        }
        return view('auth.register');
    }

    public function showAdminLoginForm()
    {
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'bos'])) {
            $route = Auth::user()->role === 'bos' ? 'bos.dashboard' : 'admin.dashboard';
            return redirect()->route($route);
        }
        return view('auth.admin-login');
    }

    public function login(LoginRequest $request)
    {
        $login = $request->input('login');
        $password = $request->input('password');

        $user = User::where(function ($q) use ($login) {
            $q->where('phone', $login)->orWhere('email', $login);
        })->where('role', 'customer')->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            return redirect()->route('customer.dashboard')
                ->with('success', __('Selamat datang kembali, :name!', ['name' => $user->name]));
        }

        return back()->withErrors(['login' => 'Nomor telepon atau password salah.'])->withInput();
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'phone'    => ['required', 'string', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'customer',
            'avatar'   => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=250',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('customer.dashboard')
            ->with('success', __('Selamat datang! Akun berhasil dibuat.'));
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where(function ($q) use ($request) {
            $q->where('phone', $request->login)
              ->orWhere('email', $request->login);
        })->whereIn('role', ['admin', 'bos'])->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::logout();
            Auth::login($user);
            $request->session()->regenerate();

            if ($user->role === 'bos') {
                return redirect()->route('bos.dashboard')
                    ->with('success', __('Selamat datang, Bos :name!', ['name' => $user->name]));
            }

            return redirect()->route('admin.dashboard')
                ->with('success', __('Selamat datang, Admin!'));
        }

        return back()->withErrors(['login' => 'Kredensial admin/bos tidak valid. Ditolak.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
