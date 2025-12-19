<x-panel-layout :title="$title ?? 'Revisions'">
    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-bold">Your uploaded files</h3>
        <ul>
            @foreach($files as $f)
                <li><a href="{{ asset('storage/'.$f->path) }}" target="_blank">{{ $f->filename }}</a> (order #{{ $f->order_id }})</li>
            @endforeach
        </ul>
    </div>
</x-panel-layout>
