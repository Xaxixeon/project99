<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login Customer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4 text-center">Login Customer</h1>

        @if ($errors->any())
            <div class="mb-4 text-red-600 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('customer.login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Email / No HP</label>
                <input type="text" name="login" value="{{ old('login') }}"
                    class="w-full border rounded px-3 py-2">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2">
            </div>
            <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Masuk
            </button>
        </form>

        <p class="mt-4 text-center text-sm">
            Belum punya akun?
            <a href="{{ route('customer.register') }}" class="text-blue-600 hover:underline">
                Daftar di sini
            </a>
        </p>
    </div>
</body>

</html>
