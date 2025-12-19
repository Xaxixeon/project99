<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <!-- NAVBAR -->
    <nav class="bg-white border-b shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-700">
                Dashboard – {{ ucfirst(Auth::user()->roles->first()->name) }}
            </h1>

            <div class="flex items-center gap-4">
                <span class="text-gray-600">{{ Auth::user()->name }}</span>
                <form action="/logout" method="POST">
                    @csrf
                    <button class="text-red-600 hover:underline text-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-6">
        {{ $slot }}
    </main>

</body>

</html>
