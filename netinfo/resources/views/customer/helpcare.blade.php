@extends('layouts.app')

@section('title', 'Helpcare')
@section('page_title', 'Helpcare — Layanan Pengaduan')
@section('page_subtitle', 'Buat laporan gangguan & pantau progres penanganan tiket Anda')

@section('content')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-bold text-slate-900">Formulir Laporan Baru</h2>
                <div class="mt-2 inline-flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-1.5 ring-1 ring-inset ring-slate-200">
                    <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/></svg>
                    <span class="text-xs text-slate-500">Kode otomatis:</span>
                    <span class="font-mono text-xs font-bold text-indigo-600">TICK-{{ now()->format('Ymd') }}-XXXX</span>
                </div>
            </div>
            <form method="POST" action="{{ route('customer.helpcare.store') }}" class="space-y-5 px-6 py-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700">Judul Kendala *</label>
                    <input type="text" name="issue_title" required value="{{ old('issue_title') }}" placeholder="cth: Internet lambat sejak sore / LOS merah"
                        class="mt-1.5 block w-full rounded-lg border {{ $errors->has('issue_title') ? 'border-red-400' : 'border-slate-300' }} px-3.5 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    @error('issue_title')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Tingkat Prioritas *</label>
                    @php $selectedPriority = old('priority', 'medium'); @endphp
                    <div id="priority-group" class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        @foreach ([['low', 'Low', 'bg-emerald-500', '&lt; 24 jam'], ['medium', 'Medium', 'bg-amber-500', '&lt; 8 jam'], ['high', 'High', 'bg-red-500', '&lt; 4 jam']] as [$val, $lbl, $dot, $sla])
                            <label data-priority="{{ $val }}" class="cursor-pointer rounded-xl border-2 bg-white p-3 transition hover:border-slate-400 {{ $selectedPriority === $val ? 'border-indigo-500 bg-indigo-50/60 ring-2 ring-indigo-200' : 'border-slate-300' }}">
                                <input type="radio" name="priority" value="{{ $val }}" {{ $selectedPriority === $val ? 'checked' : '' }} class="sr-only">
                                <span class="flex items-center gap-2 text-sm font-bold text-slate-700"><span class="h-2.5 w-2.5 rounded-full {{ $dot }}"></span> {{ $lbl }}</span>
                                <span class="mt-1 block text-xs leading-snug text-slate-500">{!! $val === 'low' ? 'Gangguan ringan.' : ($val === 'medium' ? 'Mengganggu aktivitas.' : 'Putus total.') !!}<br>Respons {{ $sla }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('priority')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Deskripsi Kronologi Kendala *</label>
                    <textarea name="description" rows="5" required minlength="10" placeholder="Ceritakan kronologi: sejak kapan terjadi, lampu indikator ONT, perangkat yang bermasalah..."
                        class="mt-1.5 block w-full resize-none rounded-lg border {{ $errors->has('description') ? 'border-red-400' : 'border-slate-300' }} px-3.5 py-2.5 text-sm leading-relaxed focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">{{ old('description') }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="w-full rounded-lg bg-red-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-red-600/30 transition hover:bg-red-500">Kirim Laporan ke NOC</button>
                <p class="text-center text-[11px] leading-relaxed text-slate-400">Laporan langsung muncul di antrean Admin & panel Teknisi secara real-time.</p>
            </form>
        </div>

        <div class="lg:col-span-3">
            @php
                $statPill = ['open' => 'bg-sky-50 text-sky-700 ring-sky-600/20', 'in_progress' => 'bg-amber-50 text-amber-700 ring-amber-600/20', 'resolved' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20', 'closed' => 'bg-slate-100 text-slate-600 ring-slate-500/20'];
                $statLabel = ['open' => 'Open', 'in_progress' => 'Dikerjakan', 'resolved' => 'Selesai', 'closed' => 'Ditutup'];
            @endphp

            @if ($featured)
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white px-6 py-5">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-mono text-xs font-bold text-indigo-600">{{ $featured->ticket_code }}</span>
                                <span class="inline-flex rounded-md px-2 py-0.5 text-[11px] font-bold uppercase ring-1 ring-inset {{ $featured->priority === 'high' ? 'bg-red-50 text-red-700 ring-red-600/20' : ($featured->priority === 'medium' ? 'bg-amber-50 text-amber-700 ring-amber-600/20' : 'bg-slate-100 text-slate-600 ring-slate-500/20') }}">{{ $featured->priority }}</span>
                                <span class="inline-flex items-center gap-1.5 rounded-md px-2 py-0.5 text-[11px] font-semibold ring-1 ring-inset {{ $statPill[$featured->status] }}">{{ in_array($featured->status, ['open', 'in_progress']) ? '<span class="animate-pulse h-1.5 w-1.5 rounded-full bg-current"></span>' : '' }}{{ $statLabel[$featured->status] }}</span>
                            </div>
                            <h3 class="mt-1.5 text-lg font-extrabold tracking-tight text-slate-900">{{ $featured->issue_title }}</h3>
                            <p class="mt-0.5 max-w-xl text-sm text-slate-500">{{ $featured->description }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Teknisi</p>
                            <p class="mt-1 text-sm font-bold {{ $featured->technician ? 'text-slate-800' : 'italic text-slate-400' }}">{{ $featured->technician?->name ?? 'Menunggu penugasan admin' }}</p>
                            <p class="font-mono text-[11px] text-slate-400">Lapor {{ $featured->created_at->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="px-6 py-6 sm:px-10">
                        <ol class="relative mx-auto space-y-7 border-l-2 border-slate-200 pl-8 sm:pl-10">
                            @forelse ($featured->histories as $h)
                                <li>
                                    <span class="absolute -left-[13px] flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white ring-4 ring-emerald-100">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    </span>
                                    <div class="flex flex-wrap items-baseline justify-between gap-2">
                                        <p class="flex flex-wrap items-center gap-2 text-sm font-bold text-slate-900 capitalize">
                                            {{ str_replace('_', ' ', $h->action_type) }}
                                            @if (in_array($h->status_to, ['resolved', 'closed']))
                                                <span class="inline-flex rounded-md border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[11px] font-bold uppercase no-underline text-emerald-700">Resolved / Selesai</span>
                                            @elseif ($h->status_to === 'in_progress')
                                                <span class="inline-flex rounded-md border border-amber-200 bg-amber-50 px-2 py-0.5 text-[11px] font-bold uppercase no-underline text-amber-700">Dikerjakan</span>
                                            @endif
                                        </p>
                                        <p class="text-xs font-medium text-slate-400">{{ $h->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                                    </div>
                                    @if ($h->note)<p class="mt-1 text-sm text-slate-500">{{ $h->note }} <span class="text-slate-400">— {{ $h->user?->name }} ({{ ucfirst($h->user?->role ?? '') }})</span></p>@endif
                                </li>
                            @empty
                                <li class="text-sm italic text-slate-400">Belum ada riwayat.</li>
                            @endforelse

                            @if (!in_array($featured->status, ['resolved', 'closed']))
                                <li>
                                    <span class="absolute -left-[13px] flex h-6 w-6 animate-pulse items-center justify-center rounded-full border-2 border-dashed border-slate-300 bg-white text-slate-300 ring-4 ring-slate-50">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    </span>
                                    <p class="text-sm font-semibold text-slate-400">Ditangani teknisi</p>
                                    <p class="mt-0.5 text-sm text-slate-400">Status akan diperbarui otomatis setelah teknisi bekerja.</p>
                                </li>
                                <li>
                                    <span class="absolute -left-[13px] flex h-6 w-6 items-center justify-center rounded-full border-2 border-dashed border-slate-300 bg-white text-slate-300 ring-4 ring-slate-50">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    </span>
                                    <p class="text-sm font-semibold text-slate-400">Selesai &amp; Ditutup</p>
                                </li>
                            @endif
                        </ol>
                    </div>
                </div>
            @endif

            <div class="{{ $featured ? 'mt-6' : '' }} overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h3 class="text-base font-bold text-slate-900">Semua Tiket Saya ({{ $tickets->count() }})</h3>
                </div>
                <div class="w-full overflow-x-auto">
                    <table class="min-w-[700px] divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Kode</th>
                                <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Judul</th>
                                <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Prioritas</th>
                                <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Tanggal</th>
                                <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($tickets as $t)
                                <tr class="transition hover:bg-slate-50/70">
                                    <td class="whitespace-nowrap px-5 py-3.5 font-mono text-xs font-semibold text-indigo-600">{{ $t->ticket_code }}</td>
                                    <td class="max-w-[240px] truncate px-5 py-3.5 text-sm font-medium text-slate-700">{{ $t->issue_title }}</td>
                                    <td class="whitespace-nowrap px-5 py-3.5 text-xs font-semibold uppercase text-slate-500">{{ $t->priority }}</td>
                                    <td class="whitespace-nowrap px-5 py-3.5 text-sm text-slate-500">{{ $t->created_at->format('d M Y') }}</td>
                                    <td class="whitespace-nowrap px-5 py-3.5"><span class="inline-flex rounded-md px-2 py-1 text-[11px] font-semibold ring-1 ring-inset {{ $statPill[$t->status] }}">{{ $statLabel[$t->status] }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var group = document.getElementById('priority-group');
            if (!group) return;
            function syncPriority() {
                var checked = group.querySelector('input[name="priority"]:checked');
                group.querySelectorAll('label[data-priority]').forEach(function (label) {
                    var active = checked && label.dataset.priority === checked.value;
                    label.classList.toggle('border-indigo-500', active);
                    label.classList.toggle('bg-indigo-50/60', active);
                    label.classList.toggle('ring-2', active);
                    label.classList.toggle('ring-indigo-200', active);
                    label.classList.toggle('border-slate-300', !active);
                });
            }
            group.querySelectorAll('input[name="priority"]').forEach(function (radio) {
                radio.addEventListener('change', syncPriority);
            });
            syncPriority();
        })();
    </script>
@endsection
