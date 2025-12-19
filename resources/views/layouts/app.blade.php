<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>{{ config('app.name', 'Xeon Print') }}</title>

    <!-- Tailwind via Vite (recommended) -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Quick fallback (Tailwind CDN for fast preview) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {}
                }
            }
        </script>
        <script src="https://unpkg.com/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
    @endif

</head>

<body class="bg-gray-50 text-gray-800 antialiased">

    <!-- Header -->
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center space-x-3">
                <div class="bg-blue-600 text-white rounded-md p-2 font-bold">XP</div>
                <div>
                    <div class="font-semibold text-lg">{{ config('app.name', 'Xeon Print') }}</div>
                    <div class="text-sm text-gray-500">Digital Printing & Solutions</div>
                </div>
            </a>

            <nav class="space-x-6 text-sm">

                {{-- Selalu tampil untuk semua user --}}
                <a href="{{ route('home') }}" class="hover:text-blue-600">Home</a>
                <a href="{{ url('/products') }}" class="hover:text-blue-600">Produk</a>

                @auth
                    {{-- Khusus Customer membuat pesanan --}}
                    @if (auth()->user()->hasRole('customer'))
                        <a href="{{ route('order.create') }}" class="hover:text-blue-600">Buat Pesanan</a>
                    @endif

                    {{-- Role-based dashboard --}}
                    @if (auth()->user()->hasRole('admin'))
                        <a href="{{ url('/dashboard/admin') }}" class="hover:text-blue-600">Admin</a>
                    @endif

                    @if (auth()->user()->hasRole('customer_service'))
                        <a href="{{ url('/dashboard/customer-service') }}" class="hover:text-blue-600">CS</a>
                    @endif

                    @if (auth()->user()->hasRole('designer'))
                        <a href="{{ url('/dashboard/designer') }}" class="hover:text-blue-600">Designer</a>
                    @endif

                    @if (auth()->user()->hasRole('production'))
                        <a href="{{ url('/dashboard/production') }}" class="hover:text-blue-600">Produksi</a>
                    @endif

                    @if (auth()->user()->hasRole('warehouse'))
                        <a href="{{ url('/dashboard/warehouse') }}" class="hover:text-blue-600">Gudang</a>
                    @endif

                    @if (auth()->user()->hasRole('cashier'))
                        <a href="{{ url('/dashboard/cashier') }}" class="hover:text-blue-600">Kasir</a>
                    @endif

                    @if (auth()->user()->hasRole('marketing'))
                        <a href="{{ url('/dashboard/marketing') }}" class="hover:text-blue-600">Marketing</a>
                    @endif

                    @if (auth()->user()->hasRole('manager'))
                        <a href="{{ url('/dashboard/manager') }}" class="hover:text-blue-600">Manager</a>
                    @endif

                    {{-- Logout --}}
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="hover:text-red-600">Logout</a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                @else
                    {{-- Guest --}}
                    <a href="{{ route('login') }}" class="hover:text-blue-600">Login</a>
                    <a href="{{ route('register') }}" class="hover:text-blue-600">Register</a>
                @endauth

            </nav>

        </div>
    </header>

    <!-- Main -->
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-10">
        <div class="container mx-auto px-4 py-6 text-sm text-gray-600">
            © {{ date('Y') }} {{ config('app.name', 'Xeon Print') }} — All rights reserved.
        </div>
    </footer>

</body>

</html>
