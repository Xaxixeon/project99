<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap');
        body { font-family: 'Space Grotesk', system-ui, -apple-system, sans-serif; }
        .glass { backdrop-filter: blur(18px); background: rgba(255,255,255,0.05); }
    </style>
</head>
<body class="h-full bg-gradient-to-br from-slate-900 via-slate-950 to-black text-slate-100">
    <div class="min-h-screen">
        <header class="sticky top-0 z-10 bg-slate-900/80 border-b border-white/10 backdrop-blur">
            <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-indigo-500/20 border border-indigo-400/30 flex items-center justify-center text-indigo-200 font-bold">XE</div>
                    <div>
                        <div class="text-lg font-semibold">Xeon Customer Portal</div>
                        <div class="text-xs text-slate-400">Orders, invoices, tracking in one place</div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <div class="text-sm font-semibold">{{ auth('customer')->user()->name ?? 'Customer' }}</div>
                        @if(auth('customer')->user()?->memberType)
                            <div class="text-xs text-emerald-300">Tier: {{ auth('customer')->user()->memberType->name }}</div>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('customer.logout') }}">
                        @csrf
                        <button class="px-3 py-2 rounded-md text-sm bg-red-500/10 border border-red-400/40 text-red-200 hover:bg-red-500/20">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="max-w-6xl mx-auto px-6 py-8 space-y-6">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
