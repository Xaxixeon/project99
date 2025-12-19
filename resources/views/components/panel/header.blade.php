<div class="bg-white border-b p-4 flex justify-between items-center">
    <div class="text-xl font-semibold">{{ $title }}</div>
    <div class="flex items-center space-x-4 text-sm text-gray-600">
        <div>{{ auth()->user()->name ?? 'Guest' }}</div>
        <div class="text-xs px-2 py-1 bg-gray-100 rounded">{{ auth()->user()->roles()->first()->name ?? '' }}</div>
    </div>
</div>
