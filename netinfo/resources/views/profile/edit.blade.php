@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')
@section('page_subtitle', 'Kelola identitas akun login Anda')

@section('content')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-6 text-center shadow-sm">
            <span class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 text-2xl font-extrabold text-white shadow-lg shadow-indigo-500/30">{{ $user->initials() }}</span>
            <h2 class="mt-4 text-lg font-extrabold tracking-tight text-slate-900">{{ $user->name }}</h2>
            <p class="truncate text-sm text-slate-500">{{ $user->email }}</p>
            <span class="mt-3 inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold ring-1 ring-inset {{ $user->isAdmin() ? 'bg-indigo-50 text-indigo-700 ring-indigo-600/20' : ($user->isTechnician() ? 'bg-amber-50 text-amber-700 ring-amber-600/20' : 'bg-emerald-50 text-emerald-700 ring-emerald-600/20') }}">
                {{ $user->roleLabel() }}
            </span>
            <dl class="mt-5 space-y-2 border-t border-slate-100 pt-4 text-left text-xs">
                <div class="flex justify-between"><dt class="text-slate-400">ID User</dt><dd class="font-mono font-semibold text-slate-600">#{{ $user->id }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Terdaftar</dt><dd class="font-semibold text-slate-600">{{ $user->created_at->translatedFormat('d M Y') }}</dd></div>
            </dl>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5 rounded-xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
            @csrf
            @method('PUT')

            @if (session('success'))
                <div class="flex items-start gap-2.5 rounded-lg bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 ring-1 ring-inset ring-emerald-300">
                    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div>
                <h3 class="text-sm font-bold uppercase tracking-wide text-indigo-500">Informasi Akun</h3>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Nama Lengkap *</label>
                    <input type="text" name="name" required value="{{ old('name', $user->name) }}"
                        class="mt-1.5 block w-full rounded-lg border {{ $errors->has('name') ? 'border-red-400' : 'border-slate-300' }} px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Email Login *</label>
                    <input type="email" name="email" required value="{{ old('email', $user->email) }}"
                        class="mt-1.5 block w-full rounded-lg border {{ $errors->has('email') ? 'border-red-400' : 'border-slate-300' }} px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div id="pengaturan" class="scroll-mt-28 border-t border-slate-100 pt-5">
                <h3 class="text-sm font-bold uppercase tracking-wide text-indigo-500">Ganti Kata Sandi</h3>
                <p class="mt-1 text-xs text-slate-400">Kosongkan bila tidak ingin mengganti kata sandi.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Sandi Saat Ini</label>
                    <div class="relative mt-1.5">
                        <input type="password" id="current_password" name="current_password" autocomplete="current-password"
                            class="block w-full rounded-lg border {{ $errors->has('current_password') ? 'border-red-400' : 'border-slate-300' }} py-2 pl-3 pr-9 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                        <button type="button" onclick="togglePasswordVisibility(this, 'current_password')" title="Lihat / sembunyikan password"
                            class="absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400 transition hover:text-indigo-600">
                            <svg data-eye-open class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg data-eye-closed class="hidden h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                    @error('current_password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Sandi Baru</label>
                    <div class="relative mt-1.5">
                        <input type="password" id="password" name="password" autocomplete="new-password" placeholder="min. 8 karakter"
                            class="block w-full rounded-lg border {{ $errors->has('password') ? 'border-red-400' : 'border-slate-300' }} py-2 pl-3 pr-9 text-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                        <button type="button" onclick="togglePasswordVisibility(this, 'password')" title="Lihat / sembunyikan password"
                            class="absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400 transition hover:text-indigo-600">
                            <svg data-eye-open class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg data-eye-closed class="hidden h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Konfirmasi Sandi Baru</label>
                    <div class="relative mt-1.5">
                        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                            class="block w-full rounded-lg border border-slate-300 py-2 pl-3 pr-9 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                        <button type="button" onclick="togglePasswordVisibility(this, 'password_confirmation')" title="Lihat / sembunyikan password"
                            class="absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400 transition hover:text-indigo-600">
                            <svg data-eye-open class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg data-eye-closed class="hidden h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                    @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center justify-end border-t border-slate-200 pt-4">
                <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-600/30 transition hover:bg-indigo-500">Simpan Perubahan</button>
            </div>
        </form>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-300 bg-white px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                    Keluar dari Sesi
                </button>
            </form>
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
