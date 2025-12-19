<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Staff / Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-50 flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-slate-900/80 border border-slate-800 rounded-2xl shadow-2xl p-6 md:p-8">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-sky-500 text-slate-900 font-bold shadow-lg">X</div>
            <h1 class="mt-3 text-xl font-bold">Login Staff / Admin</h1>
            <p class="text-sm text-slate-400">Masuk ke panel internal (admin, CS, produksi, kasir, warehouse).</p>
        </div>

        <form method="POST" action="{{ route('staff.login.post') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-200 mb-1" for="email">Email</label>
                <input id="email" type="email" name="email" required autofocus
                       class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-sky-500"
                       value="{{ old('email') }}">
                @error('email')
                <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-200 mb-1" for="password">Password</label>
                <input id="password" type="password" name="password" required
                       class="w-full rounded-lg border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-100 focus:outline-none focus:ring-2 focus:ring-sky-500">
                @error('password')
                <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <label class="inline-flex items-center gap-2 text-sm text-slate-300">
                <input type="checkbox" name="remember" class="rounded border-slate-700 bg-slate-900">
                Ingat saya
            </label>

            <button class="w-full px-4 py-2 rounded-lg bg-sky-500 hover:bg-sky-400 text-slate-900 font-semibold text-sm transition">
                Masuk
            </button>
        </form>

        <p class="mt-6 text-center text-xs text-slate-500">
            Bukan staff? <a href="{{ route('customer.login') }}" class="text-sky-400 hover:underline">Login customer</a> atau
            <a href="{{ route('auth.portal') }}" class="text-sky-400 hover:underline">pilih portal</a>.
        </p>
    </div>
</body>
</html>
