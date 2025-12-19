<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Dashboard' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-gray-200">
            <div class="p-4 text-xl font-bold">Panel</div>

            <nav class="space-y-2 p-4">
                {{ $sidebar ?? '' }}
            </nav>
        </aside>

        <!-- Main -->
        <main class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-4">{{ $header ?? '' }}</h1>
            {{ $slot }}
        </main>

    </div>
</body>
</html>
