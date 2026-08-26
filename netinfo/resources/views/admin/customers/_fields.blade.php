@php
    $oldData = old();
@endphp

<div>
    <label class="block text-sm font-medium text-slate-700">Nama Lengkap *</label>
    <input type="text" name="name" required value="{{ $oldData['name'] ?? ($editMode ? '' : '') }}" placeholder="cth: Joko Susilo"
        class="mt-1.5 block w-full rounded-lg border {{ $errors->has('name') ? 'border-red-400' : 'border-slate-300' }} px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
    @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">Email (akun login) *</label>
    <input type="email" name="email" required value="{{ $oldData['email'] ?? '' }}" placeholder="nama@email.com"
        class="mt-1.5 block w-full rounded-lg border {{ $errors->has('email') ? 'border-red-400' : 'border-slate-300' }} px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
    @if (! $editMode)
        <p class="mt-1 text-[11px] text-slate-400">Password awal otomatis: <code class="rounded bg-slate-100 px-1 font-mono">password</code></p>
    @endif
    @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <label class="block text-sm font-medium text-slate-700">Paket Layanan *</label>
        <select name="package_id" required class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
            @foreach ($packages as $p)
                <option value="{{ $p->id }}">{{ $p->name }} — Rp {{ number_format((float) $p->price, 0, ',', '.') }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700">Node ODP Terhubung *</label>
        <select name="node_id" required class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 font-mono text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
            @foreach ($nodes as $n)
                <option value="{{ $n->id }}">{{ $n->name }} — {{ $n->location_area }}</option>
            @endforeach
        </select>
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-slate-700">Alamat Pemasangan *</label>
    <textarea name="address" rows="2" required placeholder="Alamat lengkap instalasi..."
        class="mt-1.5 block w-full resize-none rounded-lg border {{ $errors->has('address') ? 'border-red-400' : 'border-slate-300' }} px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">{{ $oldData['address'] ?? '' }}</textarea>
    @error('address')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
</div>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
    <div>
        <label class="block text-sm font-medium text-slate-700">No. WhatsApp *</label>
        <input type="tel" name="phone" minlength="10" maxlength="15" pattern="[0-9]+" required value="{{ $oldData['phone'] ?? '' }}" placeholder="08xxxxxxxxxx"
            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
            class="mt-1.5 block w-full rounded-lg border {{ $errors->has('phone') ? 'border-red-400' : 'border-slate-300' }} px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
        <p class="mt-1 text-[11px] text-slate-400">Hanya angka, panjang 10 - 15 digit.</p>
        @error('phone')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700">Tgl Instalasi *</label>
        <input type="date" name="installation_date" required value="{{ $oldData['installation_date'] ?? now()->toDateString() }}"
            class="mt-1.5 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-600 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700">Status</label>
        <select name="status" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm capitalize focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
            @foreach (['active', 'isolated', 'inactive'] as $st)
                <option value="{{ $st }}">{{ ucfirst($st) }}</option>
            @endforeach
        </select>
    </div>
</div>
