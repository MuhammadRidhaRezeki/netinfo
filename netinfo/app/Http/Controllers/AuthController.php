<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Package;
use App\Models\User;
use App\Support\Codes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'Email atau kata sandi tidak sesuai.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return $this->redirectByRole($request->user());
    }

    public function showRegister(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.register', [
            'packages' => Package::where('is_active', true)->orderBy('speed_mbps')->get(),
            'selectedPackage' => (int) $request->query('package'),
        ]);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'phone' => ['required', 'string', 'regex:/^[0-9]+$/', 'min:10', 'max:15'],
            'address' => ['required', 'string', 'max:500'],
            'package_id' => ['required', Rule::exists('packages', 'id')->where('is_active', true)],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar. Silakan masuk atau gunakan email lain.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'phone.required' => 'Nomor WhatsApp/telepon wajib diisi.',
            'phone.regex' => 'Nomor WhatsApp/telepon hanya boleh berisi angka.',
            'phone.min' => 'Nomor WhatsApp/telepon minimal 10 digit.',
            'phone.max' => 'Nomor WhatsApp/telepon maksimal 15 digit.',
            'address.required' => 'Alamat pemasangan wajib diisi.',
            'package_id.required' => 'Silakan pilih paket layanan.',
        ]);

        $customer = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 'customer',
            ]);

            return Customer::create([
                'user_id' => $user->id,
                'package_id' => $data['package_id'],
                'customer_code' => Codes::forCustomer(),
                'address' => $data['address'],
                'phone' => $data['phone'],
                'installation_date' => now()->toDateString(),
                'status' => 'inactive',
            ]);
        });

        Auth::login($customer->user);
        $request->session()->regenerate();

        return redirect()
            ->route('customer.dashboard')
            ->with('success', "Pendaftaran berhasil! Akun Anda ({$customer->customer_code}) sedang menunggu verifikasi dan penjadwalan pemasangan oleh tim NetInfo.");
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah keluar dari sistem.');
    }

    public function profile(Request $request)
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'current_password' => ['nullable', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (! empty($data['password'])) {
            if (empty($data['current_password'])) {
                return back()->withErrors(['current_password' => 'Kata sandi saat ini wajib diisi untuk mengganti kata sandi.']);
            }

            $user->password = $data['password'];
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    private function redirectByRole($user)
    {
        return match ($user->role) {
            'admin' => redirect()->intended(route('admin.dashboard')),
            'technician' => redirect()->intended(route('technician.dashboard')),
            default => redirect()->intended(route('customer.dashboard')),
        };
    }
}
