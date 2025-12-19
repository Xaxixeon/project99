@props(['title' => null])
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex bg-gray-50 text-gray-800">

    <aside class="w-64 p-5 bg-slate-900 text-white min-h-screen">
        <a href="{{ url('/') }}" class="block text-lg font-bold mb-6">{{ config('app.name') }}</a>
        <div class="mb-4">
            <div class="text-sm">Signed as</div>
            <div class="font-semibold">{{ Auth::user()->name ?? 'Guest' }}</div>
            <div class="text-xs text-slate-400">{{ ucfirst(Auth::user()->role ?? 'guest') }}</div>
        </div>
        <nav class="space-y-1">
            @include('panel.layouts._menu')
        </nav>
    </aside>

    <div class="flex-1 p-6">
        <header class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold">{{ $title ?? '' }}</h1>
            <div>
                <form action="{{ route('logout') }}" method="POST">@csrf<button
                        class="px-3 py-1 rounded bg-red-600 text-white">Logout</button></form>
            </div>
        </header>

        <main>
            {{ $slot }}
        </main>
    </div>

</body>

</html>
