@props(['title' => null])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 text-gray-900">
    <div class="flex">
        @include('panel.partials.sidebar')

        <main class="flex-1 p-6">
            <header class="mb-6">
                <h1 class="text-2xl font-semibold">{{ $title ?? '' }}</h1>
            </header>

            <section>
                {{ $slot }}
            </section>
        </main>
    </div>
</body>
</html>
