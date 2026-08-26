@extends('layouts.auth')

@section('title', 'Daftar')

@php
    $preselect = old('package_id', $selectedPackage);
@endphp

@section('content')
    <div class="mb-6 text-center lg:hidden">
        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-600 shadow-lg shadow-indigo-600/30">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.288 16.038a5.25 5.25 0 017.433 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z"/></svg>
        </span>
        <p class="mt-3 text-xl font-extrabold tracking-tight text-slate-900">NetInfo</p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/60 sm:p-8">
        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Daftar Akun Pelanggan</h2>
        <p class="mt-1 text-sm text-slate-500">Isi data di bawah untuk mendaftarkan layanan internet NetInfo.</p>

        @if ($errors->any())
            <div class="mt-4 flex items-start gap-2.5 rounded-lg bg-red-50 px-4 py-3 text-sm font-medium text-red-700 ring-1 ring-inset ring-red-300" role="alert">
                <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.attempt') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autocomplete="name"
                    class="mt-1.5 block w-full rounded-lg border {{ $errors->has('name') ? 'border-red-400' : 'border-slate-300' }} px-3 py-2.5 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Alamat Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                    class="mt-1.5 block w-full rounded-lg border {{ $errors->has('email') ? 'border-red-400' : 'border-slate-300' }} px-3 py-2.5 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">Kata Sandi</label>
                    <div class="relative mt-1.5">
                        <input id="password" name="password" type="password" required minlength="8" autocomplete="new-password"
                            class="block w-full rounded-lg border {{ $errors->has('password') ? 'border-red-400' : 'border-slate-300' }} py-2.5 pl-3 pr-10 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <button type="button" onclick="togglePasswordVisibility(this, 'password')" title="Lihat / sembunyikan password"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 transition hover:text-indigo-600">
                            <svg data-eye-open class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg data-eye-closed class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                    @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Ulangi Kata Sandi</label>
                    <div class="relative mt-1.5">
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                            class="block w-full rounded-lg border border-slate-300 py-2.5 pl-3 pr-10 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <button type="button" onclick="togglePasswordVisibility(this, 'password_confirmation')" title="Lihat / sembunyikan password"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 transition hover:text-indigo-600">
                            <svg data-eye-open class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg data-eye-closed class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-700">Nomor WhatsApp / Telepon</label>
                <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required autocomplete="tel" minlength="10" maxlength="15" pattern="[0-9]+" placeholder="08xxxxxxxxxx"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    class="mt-1.5 block w-full rounded-lg border {{ $errors->has('phone') ? 'border-red-400' : 'border-slate-300' }} px-3 py-2.5 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                <p class="mt-1 text-[11px] text-slate-400">Hanya angka, panjang 10 - 15 digit.</p>
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-slate-700">Alamat Pemasangan</label>
                <textarea id="address" name="address" rows="2" required
                    class="mt-1.5 block w-full rounded-lg border {{ $errors->has('address') ? 'border-red-400' : 'border-slate-300' }} px-3 py-2.5 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">{{ old('address') }}</textarea>
            </div>
            <div>
                <label for="package_id" class="block text-sm font-medium text-slate-700">Pilih Paket Layanan</label>
                <select id="package_id" name="package_id" required
                    class="mt-1.5 block w-full rounded-lg border {{ $errors->has('package_id') ? 'border-red-400' : 'border-slate-300' }} bg-white px-3 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    <option value="" disabled {{ $preselect ? '' : 'selected' }}>— Pilih paket —</option>
                    @foreach ($packages as $p)
                        <option value="{{ $p->id }}" {{ (string) $preselect === (string) $p->id ? 'selected' : '' }}>
                            {{ $p->name }} — Rp {{ number_format((float) $p->price, 0, ',', '.') }}/bln
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-start gap-2.5 rounded-lg bg-indigo-50 px-4 py-3 text-xs leading-relaxed text-indigo-700 ring-1 ring-inset ring-indigo-200">
                <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                Setelah mendaftar, akun Anda berstatus menunggu verifikasi. Titik jaringan (node ODP) dan jadwal pemasangan akan ditetapkan oleh tim teknisi kami, lalu layanan diaktifkan.
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2">
                Daftar Sekarang
            </button>
        </form>

        <p class="mt-5 text-center text-sm text-slate-500">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 transition hover:text-indigo-500">Masuk di sini</a>
        </p>
        <p class="mt-2 text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-400 transition hover:text-indigo-600">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Kembali ke Beranda
            </a>
        </p>
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
