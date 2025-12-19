<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} — XEON</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-gray-100 flex">

    {{-- SIDEBAR --}}
    @include('layouts.sidebar')

    <div class="flex-1 flex flex-col min-h-screen">

        {{-- HEADER --}}
        <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold">{{ $title ?? 'Dashboard' }}</h1>

            <div class="flex items-center space-x-4">
                <span class="text-gray-600">{{ auth()->user()->name }}</span>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="px-3 py-1 bg-red-500 text-white rounded">Logout</button>
                </form>
            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="p-6 flex-1">
            @yield('content')
        </main>

    </div>

</body>

</html>
