{{--
    Per Year Level Turnout Card
    Props:
        $yearLevels — array of:
            year_level      string  e.g. '1st Year'
            enroll_year     int
            total_students  int
            voted           int
            not_yet_voted   int
            turnout_percent float
--}}

<div class="bg-white rounded-2xl p-6 shadow-lg">

    <h4 class="text-xs font-extrabold text-gray-900 uppercase tracking-widest mb-5">
        Per Year Level Turnout
    </h4>

    @if(empty($yearLevels))
        <p class="text-gray-400 italic text-sm">No data available.</p>
    @else
        <div class="flex flex-col gap-4">
            @foreach($yearLevels as $row)

                <div>
                    {{-- Label + percentage --}}
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-sm font-semibold text-gray-800">
                            {{ $row['year_level'] }}
                        </span>
                        <span class="text-sm font-bold text-gray-900 tabular-nums">
                            {{ $row['turnout_percent'] }}%
                        </span>
                    </div>

                    {{-- Progress bar --}}
                    <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                        <div class="h-2 rounded-full bg-gray-900 transition-all duration-500"
                             style="width: {{ min($row['turnout_percent'], 100) }}%">
                        </div>
                    </div>

                    {{-- Voted / Total sub-label --}}
                    <div class="flex justify-between mt-1">
                        <span class="text-[10px] text-gray-400">
                            {{ number_format($row['voted']) }} voted
                        </span>
                        <span class="text-[10px] text-gray-400">
                            of {{ number_format($row['total_students']) }}
                        </span>
                    </div>
                </div>

            @endforeach
        </div>
    @endif

</div>
