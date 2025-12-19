@props(['steps' => []])

<div class="relative border-l-2 border-gray-300 pl-6">
    @foreach ($steps as $step)
        <div class="mb-6">
            <div
                class="absolute -left-3 top-1 w-5 h-5 rounded-full 
                        {{ $step['done'] ? 'bg-green-500' : 'bg-gray-400' }}">
            </div>

            <h3 class="font-semibold">{{ $step['title'] }}</h3>
            <p class="text-gray-600 text-sm">{{ $step['time'] ?? '' }}</p>
        </div>
    @endforeach
</div>
