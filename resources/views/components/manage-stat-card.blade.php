<div class="bg-white rounded-[2rem] p-6 flex items-center shadow-lg transition-transform hover:translate-y-[-4px]">
    <div class="{{ $color }} p-4 rounded-2xl mr-5 text-white">
        @if($icon == 'user')
            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg>
        @else
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        @endif
    </div>
    <div>
        <div class="text-4xl font-extrabold text-gray-900 leading-none">{{ $count }}</div>
        <div class="text-[11px] text-gray-500 font-bold uppercase tracking-wide mt-1">{{ $label }}</div>
    </div>
</div>
