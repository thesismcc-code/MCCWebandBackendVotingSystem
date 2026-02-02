<tr class="hover:bg-gray-50 transition-colors group">
    <td class="px-8 py-5">
        <div class="flex items-center">
            <div class="bg-blue-600 p-2 rounded-full text-white mr-3 shadow-md">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg>
            </div>
            <span class="text-xs font-bold text-gray-800 tracking-tight">{{ $name }}</span>
        </div>
    </td>
    <td class="px-6 py-5 text-xs text-gray-500 font-medium">{{ $email }}</td>
    <td class="px-6 py-5 text-center">
        @php
            $roleClass = $role == 'Comelec' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800';
        @endphp
        <span class="{{ $roleClass }} text-[9px] font-extrabold px-3 py-1 rounded-full uppercase tracking-tighter">
            {{ $role }}
        </span>
    </td>
    <td class="px-6 py-5 text-center">
        <span class="bg-green-500 text-white text-[9px] font-bold px-3 py-1 rounded-full uppercase">
            {{ $status }}
        </span>
    </td>
    <td class="px-6 py-5 text-xs text-gray-500 font-medium">{{ $date }}</td>
    <td class="px-8 py-5 text-right">
        <button class="bg-red-600 p-2 rounded-lg text-white shadow-md hover:bg-red-700 transition-colors opacity-0 group-hover:opacity-100 transition-opacity">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </button>
    </td>
</tr>
