<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-50 flex items-center justify-center px-4">
    <div class="w-full max-w-3xl bg-slate-900/80 border border-slate-800 rounded-2xl shadow-2xl p-6 md:p-8">
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-sky-500 text-slate-900 font-bold shadow-lg">X</div>
            <h1 class="mt-3 text-2xl font-bold">Xeon Panel – Login</h1>
            <p class="text-sm text-slate-400">Pilih tipe akun yang ingin masuk.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <a href="{{ route('customer.login') }}"
               class="group block p-5 rounded-xl border border-slate-800 bg-slate-900 hover:border-sky-500/70 hover:-translate-y-1 transition shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-sky-500/20 text-sky-300 flex items-center justify-center">
                        👤
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-50">Login Customer</h2>
                        <p class="text-xs text-slate-400">Akses dashboard customer: pesanan, riwayat, pembayaran.</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('staff.login') }}"
               class="group block p-5 rounded-xl border border-slate-800 bg-slate-900 hover:border-emerald-500/70 hover:-translate-y-1 transition shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-500/20 text-emerald-300 flex items-center justify-center">
                        🧑‍💼
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-50">Login Staff / Admin</h2>
                        <p class="text-xs text-slate-400">Akses panel internal: admin, CS, produksi, kasir, warehouse.</p>
                    </div>
                </div>
            </a>
        </div>

        <p class="mt-6 text-center text-xs text-slate-500">Jika belum punya akun, silakan hubungi admin.</p>
    </div>
</body>
</html>
