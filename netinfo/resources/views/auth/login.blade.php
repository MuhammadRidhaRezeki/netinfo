@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
    <div class="mb-8 flex justify-center lg:hidden">
        <a href="{{ route('home') }}" class="inline-flex">
            <x-brand-logo size="lg" :withText="true" textColor="dark" subtext="Network Operation System" />
        </a>
    </div>

    <div class="rounded-xl border border-slate-200/90 bg-white p-7 shadow-[0_1px_2px_rgba(0,0,0,0.04)] sm:p-9">
        <div>
            <h2 class="text-2xl font-semibold tracking-tight text-slate-900">Selamat datang kembali</h2>
            <p class="mt-1 text-sm leading-relaxed text-slate-500">Masuk untuk mengakses portal operasional Anda.</p>
        </div>

        @if (session('success'))
            <div class="mt-5 flex items-start gap-2.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3.5 py-3 text-sm text-emerald-800">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-5 flex items-start gap-2.5 rounded-lg border border-rose-200 bg-rose-50 px-3.5 py-3 text-sm font-medium text-rose-700" role="alert">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-rose-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username"
                    class="mt-1.5 block w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition-all">
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                </div>
                <div class="relative mt-1.5">
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                        class="block w-full rounded-lg border border-slate-200 bg-white py-2 pl-3.5 pr-10 text-sm text-slate-900 placeholder:text-slate-400 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 transition-all">
                    <button type="button" onclick="togglePasswordVisibility(this, 'password')" title="Lihat / sembunyikan password"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-700">
                        <svg data-eye-open class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <svg data-eye-closed class="hidden h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-xs text-slate-600">
                    <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-900/10"> Ingat sesi ini
                </label>
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-slate-800 active:scale-[0.99] transition-all">
                Masuk
            </button>
        </form>

        <div class="mt-6 flex flex-col items-center gap-2 border-t border-slate-100 pt-5 text-center text-xs text-slate-500">
            <p>
                Belum memiliki akun?
                <a href="{{ route('register') }}" class="font-medium text-slate-900 underline underline-offset-4 hover:text-slate-700">Daftar pelanggan</a>
            </p>
            <p>
                <a href="{{ route('home') }}" class="text-slate-400 hover:text-slate-600">&larr; Kembali ke beranda</a>
            </p>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(btn, targetId) {
            const input = document.getElementById(targetId);
            const showing = input.type === 'text';
            input.type = showing ? 'password' : 'text';
            btn.querySelector('[data-eye-open]').classList.toggle('hidden', !showing);
            btn.querySelector('[data-eye-closed]').classList.toggle('hidden', showing);
        }
    </script>
@endsection
