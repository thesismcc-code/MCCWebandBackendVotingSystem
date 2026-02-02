@php
    $fontSize = ($mini ?? false) ? 'text-[11px]' : 'text-xs';
@endphp

<div class="flex items-center justify-between py-1">
    <div class="w-1/3 {{ $fontSize }} font-medium text-gray-700 truncate pr-2">{{ $name }}</div>
    <div class="w-1/4 text-center {{ $fontSize }} text-gray-600">{{ $votes }}</div>
    <div class="w-1/3 flex items-center space-x-3">
        <div class="flex-1 bg-gray-200 h-2 rounded-full overflow-hidden">
            <div class="bg-gray-800 h-full" style="width: {{ $percentage }}%"></div>
        </div>
        <span class="text-[10px] font-bold w-6 text-right">{{ $percentage }}%</span>
    </div>
</div>
