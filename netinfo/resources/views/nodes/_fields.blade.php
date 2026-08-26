<div>
    <label class="block text-sm font-medium text-slate-700">Nama Node *</label>
    <input type="text" name="name" required placeholder="cth: ODP-BNA-02"
        class="mt-1.5 block w-full rounded-lg border border-slate-300 px-3 py-2 font-mono text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700">Lokasi Wilayah *</label>
    <input type="text" name="location_area" required placeholder="Jalan / RT / wilayah..."
        class="mt-1.5 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700">IP Manajemen <span class="font-normal text-slate-400">(opsional)</span></label>
    <input type="text" name="ip_address" placeholder="10.10.x.x"
        class="mt-1.5 block w-full rounded-lg border border-slate-300 px-3 py-2 font-mono text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
</div>
<div>
    <label class="block text-sm font-medium text-slate-700">Status Operasional *</label>
    <select name="status" class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm capitalize focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
        @foreach (['active', 'maintenance', 'down'] as $st)
            <option value="{{ $st }}">{{ ucfirst($st) }}</option>
        @endforeach
    </select>
</div>

@error('name')<p class="-mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
@error('location_area')<p class="-mt-2 text-xs text-red-500">{{ $message }}</p>@enderror
