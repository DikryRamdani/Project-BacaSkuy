@php
    // Use passed user or fallback to auth user
    $displayUser = $user ?? auth()->user();
@endphp

@if ($displayUser)
    @if ($displayUser->profile_image)
        <img src="{{ asset('storage/' . $displayUser->profile_image) }}" alt="{{ $displayUser->name }}"
            class="w-9 h-9 rounded-full object-cover shrink-0">
    @else
        @php
            // Generate unique color based on user name
            $colors = [
                'bg-red-500',
                'bg-orange-500',
                'bg-amber-500',
                'bg-yellow-500',
                'bg-lime-500',
                'bg-green-500',
                'bg-emerald-500',
                'bg-teal-500',
                'bg-cyan-500',
                'bg-sky-500',
                'bg-blue-500',
                'bg-indigo-500',
                'bg-violet-500',
                'bg-purple-500',
                'bg-fuchsia-500',
                'bg-pink-500',
                'bg-rose-500',
            ];
            $colorIndex = abs(crc32($displayUser->name)) % count($colors);
            $bgColor = $colors[$colorIndex];
            $initial = strtoupper(substr($displayUser->name, 0, 1));
        @endphp
        <div class="w-9 h-9 rounded-full {{ $bgColor }} flex items-center justify-center shrink-0">
            <span class="text-sm font-semibold text-white">{{ $initial }}</span>
        </div>
    @endif
@else
    {{-- Guest Avatar --}}
    <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5.121 17.804A9 9 0 1119.88 6.196 9 9 0 015.12 17.804z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
    </div>
@endif
