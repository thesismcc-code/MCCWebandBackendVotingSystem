{{--
    Candidate Row Component
    Props:
        $candidate — array with keys: name, votes, percentage
--}}

<div class="grid grid-cols-[1fr_auto_auto] items-center gap-4 px-5 py-3">

    {{-- Name --}}
    <span class="text-sm text-gray-800 font-medium truncate">
        {{ $candidate['name'] }}
    </span>

    {{-- Votes --}}
    <span class="text-sm font-bold text-gray-900 w-16 text-center tabular-nums">
        {{ number_format($candidate['votes']) }}
    </span>

    {{-- Progress bar + percentage --}}
    <div class="flex items-center gap-2 w-40">
        <div class="flex-1 bg-gray-200 rounded-full h-2 overflow-hidden">
            <div class="h-2 rounded-full bg-gray-900 transition-all duration-500"
                 style="width: {{ min($candidate['percentage'], 100) }}%">
            </div>
        </div>
        <span class="text-xs font-semibold text-gray-700 tabular-nums w-9 text-right">
            {{ $candidate['percentage'] }}%
        </span>
    </div>

</div>
