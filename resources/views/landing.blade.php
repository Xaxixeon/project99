<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Xeon Digital Printing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- THEME SYSTEM --}}
    <style>
        :root {
            --xeon-font-main: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        /* ====== MODE BRAND (DARK) – default ====== */
        :root[data-theme="dark"] {
            --bg-page: #020617;
            --bg-section: #020617;
            --bg-section-alt: #020617;
            --bg-card: #020617;
            --bg-card-soft: #0b1224;
            --border-soft: #1e293b;
            --border-strong: #0f172a;

            --text-main: #e5e7eb;
            --text-muted: #9ca3af;
            --text-heading: #f9fafb;

            --accent: #0ea5e9;
            --accent-soft: rgba(56, 189, 248, 0.4);

            --shadow-soft: 0 24px 60px rgba(15, 23, 42, 0.95);
        }

        /* ====== LIGHT MODE ====== */
        :root[data-theme="light"] {
            --bg-page: #f6f7fb;
            --bg-section: #ffffff;
            --bg-section-alt: #f3f4f6;
            --bg-card: #ffffff;
            --bg-card-soft: #fafafa;
            --border-soft: #e5e7eb;
            --border-strong: #cbd5e1;

            --text-main: #0f172a;
            --text-muted: #6b7280;
            --text-heading: #0f172a;

            --accent: #0ea5e9;
            --accent-soft: rgba(14, 165, 233, 0.12);

            --shadow-soft: 0 18px 40px rgba(15, 23, 42, 0.10);
        }

        /* ====== GLOBAL LANDING CLASSES ====== */
        body.landing-body {
            font-family: var(--xeon-font-main);
            background: radial-gradient(circle at top, #0f172a, var(--bg-page));
            color: var(--text-main);
            line-height: 1.7;
        }

        .landing-nav {
            background-color: rgba(2, 6, 23, 0.85);
            border-bottom-color: var(--border-soft);
        }

        .landing-section {
            background-color: var(--bg-section);
            color: var(--text-main);
            border-top: 1px solid var(--border-soft);
        }

        .landing-section-alt {
            background: radial-gradient(circle at top, var(--bg-section-alt), var(--bg-section));
            color: var(--text-main);
            border-top: 1px solid var(--border-soft);
        }

        .landing-card {
            background-color: var(--bg-card);
            border-color: var(--border-soft);
            box-shadow: var(--shadow-soft);
        }

        .landing-card-soft {
            background-color: var(--bg-card-soft);
            border-color: var(--border-soft);
        }

        .landing-heading {
            color: var(--text-heading);
        }

        .landing-muted {
            color: var(--text-muted);
        }

        .landing-link {
            color: var(--accent);
        }

        .landing-hero-glow {
            background: radial-gradient(circle at top left, var(--accent-soft), transparent 60%),
                        radial-gradient(circle at bottom right, rgba(15, 23, 42, 0.9), transparent 65%);
        }

        .theme-toggle-btn {
            border-radius: 999px;
            border: 1px solid transparent;
            padding: 4px 9px;
            font-size: 11px;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            cursor: pointer;
            background-color: transparent;
            color: var(--text-muted);
        }
        .theme-toggle-btn.is-active {
            background-color: var(--accent-soft);
            border-color: var(--accent);
            color: var(--accent);
        }
    </style>
</head>

<body class="landing-body antialiased" data-theme="dark">
{{-- =========================================================
   NAVBAR
========================================================= --}}
<header class="landing-nav fixed top-0 inset-x-0 z-40 backdrop-blur border-b">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-sky-500 text-slate-950 font-bold shadow-lg">
                X
            </div>
            <div class="leading-tight">
                <div class="font-semibold text-sm md:text-base">Xeon Digital Printing</div>
                <div class="text-[11px] landing-muted hidden sm:block">
                    Digital Printing & Creative Studio
                </div>
            </div>
        </div>

        {{-- Desktop menu --}}
        <nav class="hidden md:flex items-center gap-6 text-sm">
            <a href="#beranda" class="hover:text-sky-400 transition">Beranda</a>
            <a href="{{ route('products.index') }}" class="hover:text-sky-400 transition">Katalog</a>
            <a href="#event" class="hover:text-sky-400 transition">Event</a>
            <a href="#testimoni" class="hover:text-sky-400 transition">Testimoni</a>
        </nav>

        {{-- Right actions + theme switcher --}}
        <div class="flex items-center gap-3">
            <div class="hidden md:flex items-center gap-1 text-[11px]">
                <button type="button" class="theme-toggle-btn is-active" data-theme-btn="dark">
                    🌙 <span class="hidden lg:inline">Dark</span>
                </button>
                <button type="button" class="theme-toggle-btn" data-theme-btn="light">
                    ☀️ <span class="hidden lg:inline">Light</span>
                </button>
            </div>

            @if(auth('customer')->check())
                <a href="{{ route('customer.dashboard') }}"
                   class="hidden md:inline-flex px-3 py-1.5 rounded-full bg-slate-900 border border-slate-700 text-xs font-semibold hover:border-sky-400 hover:text-sky-300 transition">
                    Dashboard Customer
                </a>
            @elseif(auth('staff')->check())
                <a href="{{ route('staff.dashboard') }}"
                   class="hidden md:inline-flex px-3 py-1.5 rounded-full bg-slate-900 border border-slate-700 text-xs font-semibold hover:border-sky-400 hover:text-sky-300 transition">
                    Panel Staff
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="hidden md:inline-flex px-3 py-1.5 rounded-full bg-slate-900 border border-slate-700 text-xs font-semibold hover:border-sky-400 hover:text-sky-300 transition">
                    Login
                </a>
            @endif

            {{-- Burger --}}
            <button type="button"
                    onclick="document.getElementById('landing-menu').classList.toggle('hidden')"
                    class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-full border border-slate-700 hover:border-sky-400 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- CTA kontak --}}
            <button type="button"
                    onclick="document.getElementById('contact-modal').classList.remove('hidden')"
                    class="hidden md:inline-flex px-3 py-1.5 rounded-full bg-sky-500 text-slate-950 text-xs font-semibold shadow-md hover:bg-sky-400 transition">
                Hubungi Admin
            </button>
        </div>
    </div>

    {{-- Mobile dropdown --}}
    <div id="landing-menu" class="md:hidden hidden border-t border-slate-800 bg-slate-950">
        <div class="max-w-6xl mx-auto px-4 py-3 flex flex-col gap-2 text-sm">
            <div class="flex items-center justify-between mb-1">
                <span class="text-[11px] landing-muted">Tema</span>
                <div class="flex gap-1">
                    <button type="button" class="theme-toggle-btn is-active" data-theme-btn="dark">🌙</button>
                    <button type="button" class="theme-toggle-btn" data-theme-btn="light">☀️</button>
                </div>
            </div>

            <a href="#beranda" class="hover:text-sky-400 transition">Beranda</a>
            <a href="{{ route('products.index') }}" class="hover:text-sky-400 transition">Katalog</a>
            <a href="#event" class="hover:text-sky-400 transition">Event</a>
            <a href="#testimoni" class="hover:text-sky-400 transition">Testimoni</a>
            <div class="h-px bg-slate-800 my-1"></div>
            @if(auth('customer')->check())
                <a href="{{ route('customer.dashboard') }}" class="hover:text-sky-400 transition">Dashboard Customer</a>
            @elseif(auth('staff')->check())
                <a href="{{ route('staff.dashboard') }}" class="hover:text-sky-400 transition">Panel Staff</a>
            @else
                <a href="{{ route('login') }}" class="hover:text-sky-400 transition">Login</a>
            @endif
            <button type="button"
                    onclick="document.getElementById('contact-modal').classList.remove('hidden')"
                    class="mt-2 inline-flex px-3 py-1.5 rounded-full bg-sky-500 text-slate-950 text-xs font-semibold shadow-md hover:bg-sky-400 transition">
                Hubungi Admin
            </button>
        </div>
    </div>
</header>

{{-- =========================================================
   MAIN
========================================================= --}}
<main class="pt-20 md:pt-24">
    {{-- HERO --}}
    <section id="beranda" class="bg-gradient-to-b from-slate-950 via-slate-950 to-slate-900">
        <div class="max-w-6xl mx-auto px-4 py-10 md:py-16">
            <div class="grid lg:grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)] gap-10 items-center">
                {{-- HERO TEXT --}}
                <div class="space-y-5 animate-fade-in">
                    <p class="text-xs md:text-sm font-semibold tracking-[0.25em] text-sky-400 uppercase">
                        DIGITAL PRINTING & CREATIVE STUDIO
                    </p>
                    <h1 class="text-3xl md:text-5xl font-extrabold leading-tight">
                        Semua kebutuhan cetak Anda,
                        <span class="text-sky-400">selesai dalam satu panel.</span>
                    </h1>
                    <p class="text-sm md:text-base text-slate-300 max-w-xl">
                        Dari brosur, poster, banner, kemasan, hingga branding lengkap.
                        Xeon Panel menghubungkan front office, desain, produksi, dan keuangan
                        sehingga setiap order bisa dipantau dan dikelola dengan rapi.
                    </p>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('products.index') }}"
                           class="px-5 py-2.5 rounded-full bg-sky-500 text-slate-950 text-sm font-semibold shadow-md hover:bg-sky-400 transition">
                            Lihat Katalog & Order
                        </a>

                        @guest('customer')
                            <a href="{{ route('customer.register') }}"
                               class="px-5 py-2.5 rounded-full border border-slate-700 text-sm font-semibold hover:border-sky-400 hover:text-sky-300 transition">
                                Daftar Customer
                            </a>
                        @endguest
                    </div>

                    <div class="flex flex-wrap gap-4 text-xs md:text-sm text-slate-400">
                        <span>- Estimasi harga instan</span>
                        <span>- Tracking status order (via login)</span>
                        <span>- Riwayat order & repeat order</span>
                    </div>
                </div>

                {{-- HERO VISUAL (TANPA DATA PRODUKSI) --}}
                <div class="relative animate-slide-up">
                    <div class="absolute -inset-6 bg-sky-500/15 blur-3xl rounded-[2.5rem]"></div>

                    <div class="relative bg-slate-900/80 rounded-[2rem] border border-slate-800/80 p-6 shadow-2xl space-y-4">
                        <div class="text-xs font-semibold text-sky-300">
                            Satu panel untuk seluruh alur kerja
                        </div>

                        <div class="grid grid-cols-1 gap-3 text-xs md:text-sm">
                            <div class="flex items-start gap-3">
                                <div class="mt-1 w-8 h-8 rounded-xl bg-sky-500/15 flex items-center justify-center text-sky-300">
                                    FO
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-100">Front Office & CS</div>
                                    <div class="text-slate-400">
                                        Form order rapi, catatan revisi jelas, dan histori customer tersimpan otomatis.
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="mt-1 w-8 h-8 rounded-xl bg-emerald-500/15 flex items-center justify-center text-emerald-300">
                                    DS
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-100">Desain & Produksi</div>
                                    <div class="text-slate-400">
                                        Antrian kerja, file design, dan approval terkoordinasi dalam satu sistem internal.
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <div class="mt-1 w-8 h-8 rounded-xl bg-amber-500/15 flex items-center justify-center text-amber-300">
                                    RP
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-100">Keuangan & Laporan</div>
                                    <div class="text-slate-400">
                                        Estimator harga, invoice, dan profit report membantu Anda memantau bisnis dengan tenang.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 border-t border-slate-800 text-[11px] md:text-xs text-slate-400">
                            Detail status order dan aktivitas produksi hanya dapat diakses setelah login
                            oleh tim internal dan customer yang berwenang.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- COMPANY PROFILE --}}
    <section id="profil" class="landing-section">
        <div class="max-w-6xl mx-auto px-4 py-10 md:py-14 grid md:grid-cols-3 gap-10 items-start">
            <div class="md:col-span-2 space-y-3 animate-fade-in">
                <h2 class="landing-heading text-xl md:text-2xl font-semibold">Sekilas Tentang Xeon</h2>
                <p class="text-base md:text-lg landing-muted">
                    Xeon Digital Printing adalah solusi cetak terpadu untuk bisnis, UMKM, dan personal.
                    Dengan sistem Xeon Panel, setiap order tercatat rapi, progress produksi jelas,
                    dan laporan keuangan bisa dipantau kapan saja.
                </p>
                <p class="text-base md:text-lg landing-muted">
                    Tim internal kami (Customer Service, Designer, Produksi, QC, Gudang, dan Keuangan)
                    bekerja di dalam satu sistem yang sama sehingga meminimalkan salah cetak dan
                    mempercepat waktu produksi.
                </p>
            </div>

            <div class="space-y-2 text-sm animate-slide-up">
                <div class="font-semibold landing-heading mb-1">Kontak & Lokasi</div>
                <div>- Alamat: (isi alamatmu di sini)</div>
                <div>- WhatsApp: 08xx-xxxx-xxxx</div>
                <div>- Email: halo@xeonprinting.com</div>
                <div class="mt-3 text-xs landing-muted">
                    Kami menerima pesanan online maupun datang langsung. Untuk kerja sama perusahaan
                    atau instansi, silakan hubungi admin untuk penawaran khusus.
                </div>
            </div>
        </div>
    </section>

    {{-- KATALOG UNGGULAN --}}
    <section id="katalog" class="landing-section">
        <div class="max-w-6xl mx-auto px-4 py-10 md:py-14 space-y-5">
            <div class="flex items-center justify-between">
                <h2 class="landing-heading text-xl md:text-2xl font-semibold">Produk Unggulan</h2>
                <a href="{{ route('products.index') }}"
                   class="text-xs md:text-sm landing-link hover:underline">
                    Lihat semua katalog →
                </a>
            </div>

            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-5">
                @forelse($featuredProducts as $product)
                    <div class="landing-card-soft border rounded-2xl p-4 flex flex-col justify-between hover:border-sky-500/60 hover:-translate-y-1 hover:shadow-xl hover:shadow-sky-500/10 transition duration-200">
                        <div>
                            <div class="text-sm font-semibold mb-1">
                                {{ $product->name }}
                            </div>
                            <div class="text-[11px] landing-muted mb-2">
                                SKU: {{ $product->sku }}
                            </div>
                            <div class="text-base landing-muted line-clamp-3 mb-3 min-h-[3rem]">
                                {{ $product->short_description ?? $product->description }}
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <div class="text-xs font-semibold landing-link">
                                {{ $product->display_price ?? 'Harga variatif' }}
                            </div>
                            <a href="{{ route('product.show', $product->sku) }}"
                               class="text-xs landing-link hover:underline">
                                Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-sm landing-muted col-span-full">
                        Belum ada produk yang ditandai sebagai unggulan.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- EVENT --}}
    <section id="event" class="landing-section">
        <div class="max-w-6xl mx-auto px-4 py-10 md:py-14 space-y-4">
            <h2 class="landing-heading text-xl md:text-2xl font-semibold">Event & Promo Mendatang</h2>

            <div class="grid md:grid-cols-2 gap-5 text-sm">
                <div class="landing-card-soft bg-gradient-to-r from-sky-600/20 via-sky-500/10 to-slate-900 border rounded-2xl p-4 md:p-5">
                    <div class="text-sky-300 text-xs font-semibold uppercase tracking-wide mb-1">
                        Promo contoh
                    </div>
                    <div class="text-sm md:text-base font-semibold mb-1 landing-heading">
                        Promo Akhir Tahun – Diskon 20% Banner & X-Banner
                    </div>
                    <div class="text-[11px] landing-muted mb-2">
                        1–31 Desember • Berlaku untuk semua order online & offline
                    </div>
                    <p class="text-base landing-muted">
                        Tulis detail promo atau event di sini. Nanti bisa kita hubungkan dengan modul
                        voucher atau kode promo khusus customer.
                    </p>
                </div>

                <div class="landing-card-soft border rounded-2xl p-4 md:p-5 text-base landing-muted">
                    <div class="font-semibold landing-heading mb-2">Kenapa pilih Xeon?</div>
                    <ul class="space-y-1">
                        <li>- Lead time jelas, status bisa dipantau.</li>
                        <li>- Histori order tersimpan, repeat order jadi mudah.</li>
                        <li>- Harga transparan dengan estimator otomatis.</li>
                        <li>- Workflow internal dari CS sampai gudang terintegrasi.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- TESTIMONI --}}
    <section id="testimoni" class="landing-section">
        <div class="max-w-6xl mx-auto px-4 py-10 md:py-14 space-y-5">
            <h2 class="landing-heading text-xl md:text-2xl font-semibold">Apa Kata Klien</h2>

            <div class="grid md:grid-cols-3 gap-5 text-sm">
                <div class="landing-card-soft border rounded-2xl p-4 flex flex-col gap-2">
                    <div class="font-semibold landing-heading">Toko A</div>
                    <p class="text-base landing-muted">
                        "Order banner dan brosur jadi lebih mudah. Tinggal repeat order dari riwayat sebelumnya."
                    </p>
                </div>
                <div class="landing-card-soft border rounded-2xl p-4 flex flex-col gap-2">
                    <div class="font-semibold landing-heading">Perusahaan B</div>
                    <p class="text-base landing-muted">
                        "Tracking status di Xeon Panel membantu memastikan semua materi promosi siap sebelum event."
                    </p>
                </div>
                <div class="landing-card-soft border rounded-2xl p-4 flex flex-col gap-2">
                    <div class="font-semibold landing-heading">Freelancer C</div>
                    <p class="text-base landing-muted">
                        "Desain, revisi, sampai cetak tercatat rapi. Semua tim internal kamu juga terasa lebih terorganisir."
                    </p>
                </div>
            </div>
        </div>
    </section>
</main>

{{-- FOOTER --}}
<footer class="border-t border-slate-800 bg-transparent py-4 text-[11px] landing-muted text-center">
    (c) {{ date('Y') }} Xeon Digital Printing. All rights reserved.
</footer>

{{-- POPUP KONTAK ADMIN --}}
<div id="contact-modal"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-950/75">
    <div class="landing-card-soft bg-slate-900 border border-slate-700 rounded-2xl p-6 w-full max-w-md shadow-2xl">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold landing-heading">Hubungi Admin</h3>
            <button type="button"
                    onclick="document.getElementById('contact-modal').classList.add('hidden')"
                    class="w-7 h-7 inline-flex items-center justify-center rounded-full bg-slate-800 hover:bg-slate-700">
                x
            </button>
        </div>
        <p class="text-xs landing-muted mb-3">
            Chat langsung admin untuk konsultasi desain, penawaran corporate, atau status pesanan.
        </p>
        <div class="space-y-2 text-sm">
            <a href="https://wa.me/62xxxxxxxxxx"
               class="block px-3 py-2 rounded-full bg-sky-500 text-slate-950 font-semibold text-center hover:bg-sky-400 transition">
                Chat via WhatsApp
            </a>
            <div class="text-xs landing-muted text-center">
                Atau email ke <span class="text-slate-200">halo@xeonprinting.com</span>
            </div>
        </div>
    </div>
</div>

{{-- SIMPLE ANIMATIONS + THEME SCRIPT --}}
<script>
    // Theme handling
    (function () {
        const THEMES = ['dark', 'light'];
        const STORAGE_KEY = 'xeon_theme';

        function applyTheme(theme) {
            if (!THEMES.includes(theme)) theme = 'dark';

            document.documentElement.setAttribute('data-theme', theme);
            document.body.setAttribute('data-theme', theme);

            try {
                localStorage.setItem(STORAGE_KEY, theme);
            } catch (e) {}

            document.querySelectorAll('[data-theme-btn]').forEach(btn => {
                btn.classList.toggle('is-active', btn.dataset.themeBtn === theme);
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const saved = (typeof localStorage !== 'undefined')
                ? localStorage.getItem(STORAGE_KEY)
                : null;
            applyTheme(saved || 'dark');

            document.querySelectorAll('[data-theme-btn]').forEach(btn => {
                btn.addEventListener('click', () => applyTheme(btn.dataset.themeBtn));
            });
        });
    })();

    // Simple fade-in / slide-up on scroll
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('opacity-100', 'translate-y-0');
        });
    }, {threshold: 0.1});

    document.querySelectorAll('.animate-fade-in, .animate-slide-up').forEach(el => {
        el.classList.add('opacity-0', 'translate-y-4', 'transition', 'duration-700');
        observer.observe(el);
    });
</script>

</body>
</html>
