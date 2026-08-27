@props([
    'size' => 'md', // 'sm' (h-7 w-7), 'md' (h-9 w-9), 'lg' (h-11 w-11), 'xl' (h-14 w-14)
    'withText' => false,
    'textColor' => 'white', // 'white', 'dark'
    'subtext' => 'Network Operation System',
])

@php
    $sizeClasses = [
        'sm' => 'h-7 w-7 p-1 rounded-md',
        'md' => 'h-9 w-9 p-1.5 rounded-lg',
        'lg' => 'h-11 w-11 p-2 rounded-xl',
        'xl' => 'h-14 w-14 p-2.5 rounded-2xl',
    ][$size] ?? 'h-9 w-9 p-1.5 rounded-lg';

    $iconSizes = [
        'sm' => 'h-5 w-5',
        'md' => 'h-6 w-6',
        'lg' => 'h-7 w-7',
        'xl' => 'h-9 w-9',
    ][$size] ?? 'h-6 w-6';

    $uid = substr(md5(uniqid()), 0, 6);
    $gradId = 'c4NetGrad_' . $uid;
    $pulseId = 'c4PulseGrad_' . $uid;
@endphp

<div class="inline-flex items-center gap-3">
    <span class="flex {{ $sizeClasses }} items-center justify-center bg-slate-950 text-slate-100 ring-1 ring-slate-800 shadow-sm shrink-0">
        <svg class="{{ $iconSizes }}" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="{{ $gradId }}" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#00f0ff" />
                    <stop offset="35%" stop-color="#38bdf8" />
                    <stop offset="70%" stop-color="#a855f7" />
                    <stop offset="100%" stop-color="#ec4899" />
                </linearGradient>
                <linearGradient id="{{ $pulseId }}" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#00f0ff" />
                    <stop offset="50%" stop-color="#ffffff" />
                    <stop offset="100%" stop-color="#ec4899" />
                </linearGradient>
            </defs>
            
            <!-- Left Wing of N -->
            <path d="M12 36V14C12 9.5 16 8.5 19 11.5L25.5 19" stroke="url(#{{ $gradId }})" stroke-width="3.6" stroke-linecap="round" stroke-linejoin="round" />
            
            <!-- Right Wing of N -->
            <path d="M22.5 29L29 36.5C32 39.5 36 38.5 36 34V12" stroke="url(#{{ $gradId }})" stroke-width="3.6" stroke-linecap="round" stroke-linejoin="round" />
            
            <!-- Inner Signal Pulse Line -->
            <path d="M15 24H19.5L21.5 18L24.5 30L26.5 24H33" stroke="url(#{{ $pulseId }})" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
            
            <!-- Accent Nodes -->
            <circle cx="12" cy="36" r="1.8" fill="#00f0ff" />
            <circle cx="36" cy="12" r="1.8" fill="#ec4899" />
            <circle cx="23" cy="24" r="1.4" fill="#ffffff" />
        </svg>
    </span>

    @if ($withText)
        <div class="min-w-0">
            <p class="text-base font-bold leading-tight {{ $textColor === 'dark' ? 'text-slate-900' : 'text-white' }} tracking-tight">NetInfo</p>
            @if ($subtext)
                <p class="text-[11px] font-medium {{ $textColor === 'dark' ? 'text-slate-500' : 'text-slate-400' }} truncate">{{ $subtext }}</p>
            @endif
        </div>
    @endif
</div>
