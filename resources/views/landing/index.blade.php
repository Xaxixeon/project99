<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Xeon Digital Printing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-950 text-slate-50 antialiased">

    {{-- =========================================================
    NAVBAR
========================================================= --}}
    <header class="fixed top-0 inset-x-0 z-40 bg-slate-950/80 backdrop-blur border-b border-slate-800">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div
                    class="flex items-center justify-center w-9 h-9 rounded-xl bg-sky-500 text-slate-950 font-bold shadow-lg">
                    X
                </div>
                <div class="leading-tight">
                    <div class="font-semibold text-sm md:text-base">Xeon Digital Printing</div>
                    <div class="text-[11px] text-slate-400 hidden sm:block">
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

            {{-- Right actions --}}
            <div class="flex items-center gap-2">
                @if (auth('customer')->check())
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
                    <a href="{{ route('customer.login') }}"
                        class="hidden md:inline-flex px-3 py-1.5 rounded-full bg-slate-900 border border-slate-700 text-xs font-semibold hover:border-sky-400 hover:text-sky-300 transition">
                        Login
                    </a>
                @endif

                {{-- Burger --}}
                <button type="button" onclick="document.getElementById('landing-menu').classList.toggle('hidden')"
                    class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-full border border-slate-700 hover:border-sky-400 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                {{-- CTA kontak --}}
                <button type="button" onclick="document.getElementById('contact-modal').classList.remove('hidden')"
                    class="hidden md:inline-flex px-3 py-1.5 rounded-full bg-sky-500 text-slate-950 text-xs font-semibold shadow-md hover:bg-sky-400 transition">
                    Hubungi Admin
                </button>
            </div>
        </div>

        {{-- Mobile dropdown --}}
        <div id="landing-menu" class="md:hidden hidden border-t border-slate-800 bg-slate-950">
            <div class="max-w-6xl mx-auto px-4 py-3 flex flex-col gap-2 text-sm">
                <a href="#beranda" class="hover:text-sky-400 transition">Beranda</a>
                <a href="{{ route('products.index') }}" class="hover:text-sky-400 transition">Katalog</a>
                <a href="#event" class="hover:text-sky-400 transition">Event</a>
                <a href="#testimoni" class="hover:text-sky-400 transition">Testimoni</a>
                <div class="h-px bg-slate-800 my-1"></div>
                @if (auth('customer')->check())
                    <a href="{{ route('customer.dashboard') }}" class="hover:text-sky-400 transition">Dashboard
                        Customer</a>
                @elseif(auth('staff')->check())
                    <a href="{{ route('staff.dashboard') }}" class="hover:text-sky-400 transition">Panel Staff</a>
                @else
                    <a href="{{ route('customer.login') }}" class="hover:text-sky-400 transition">Login Customer</a>
                    <a href="{{ route('staff.login') }}" class="hover:text-sky-400 transition">Login Staff</a>
                @endif
                <button type="button" onclick="document.getElementById('contact-modal').classList.remove('hidden')"
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
        <section id="beranda" class="bg-gradient-to-b from-slate-950 via-slate-950 to-slate-900">
            <div class="max-w-6xl mx-auto px-4 py-10 md:py-16 grid md:grid-cols-2 gap-10 items-center">
                {{-- HERO TEXT --}}
                <div class="space-y-5 animate-fade-in">
                    <p class="text-xs font-semibold tracking-[0.25em] text-sky-400 uppercase">
                        DIGITAL PRINTING & CREATIVE STUDIO
                    </p>
                    <h1 class="text-3xl md:text-5xl font-extrabold leading-tight">
                        Semua kebutuhan cetak Anda,
                        <span class="text-sky-400">selesai dalam satu panel.</span>
                    </h1>
                    <p class="text-sm md:text-base text-slate-300 max-w-xl">
                        Dari brosur, poster, banner, kemasan, hingga branding lengkap.
                        Xeon Panel menghubungkan front office, desain, produksi, dan keuangan
                        sehingga setiap order bisa dipantau real-time.
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

                    <div class="flex flex-wrap gap-4 text-[11px] text-slate-400">
                        <span>• Estimasi harga instan</span>
                        <span>• Tracking status order</span>
                        <span>• Riwayat order & repeat order</span>
                    </div>
                </div>

                {{-- HERO VISUAL --}}
                <div class="relative animate-slide-up">
                    <div class="absolute -inset-6 bg-sky-500/15 blur-3xl rounded-[2.5rem]"></div>

                    <div class="relative bg-slate-900/80 rounded-[2rem] border border-slate-800/80 p-5 shadow-2xl">
                        <div class="flex items-center justify-between text-xs text-slate-300 mb-4">
                            <span>Dashboard Produksi</span>
                            <span class="flex items-center gap-1 text-[11px] text-sky-300">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                Realtime
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-xs">
                            <div class="bg-slate-800/70 rounded-xl p-3">
                                <div class="text-slate-400 mb-1">Order Hari Ini</div>
                                <div class="text-2xl font-bold text-sky-400 counter" data-target="0">0</div>
                            </div>
                            <div class="bg-slate-800/70 rounded-xl p-3">
                                <div class="text-slate-400 mb-1">Sedang Proses</div>
                                <div class="text-2xl font-bold text-amber-300 counter" data-target="0">0</div>
                            </div>
                            <div class="bg-slate-800/70 rounded-xl p-3">
                                <div class="text-slate-400 mb-1">Selesai Bulan Ini</div>
                                <div class="text-2xl font-bold text-emerald-300 counter" data-target="0">0</div>
                            </div>
                            <div class="bg-slate-800/70 rounded-xl p-3">
                                <div class="text-slate-400 mb-1">Repeat Customer</div>
                                <div class="text-2xl font-bold text-fuchsia-300 counter" data-target="0">0</div>
                            </div>
                        </div>

                        <div class="mt-4 text-[11px] text-slate-400">
                            Tampilan ini mengambil data langsung dari Xeon Panel:
                            designer, produksi, kasir, hingga gudang terhubung di satu workflow.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- COMPANY PROFILE --}}
        <section id="profil" class="border-t border-slate-800 bg-slate-950">
            <div class="max-w-6xl mx-auto px-4 py-10 md:py-14 grid md:grid-cols-3 gap-10 items-start">
                <div class="md:col-span-2 space-y-3 animate-fade-in">
                    <h2 class="text-xl md:text-2xl font-semibold">Sekilas Tentang Xeon</h2>
                    <p class="text-sm md:text-base text-slate-300">
                        Xeon Digital Printing adalah solusi cetak terpadu untuk bisnis, UMKM, dan personal.
                        Dengan sistem Xeon Panel, setiap order tercatat rapi, progress produksi jelas,
                        dan laporan keuangan bisa dipantau kapan saja.
                    </p>
                    <p class="text-sm md:text-base text-slate-300">
                        Tim internal kami (Customer Service, Designer, Produksi, QC, Gudang, dan Keuangan)
                        bekerja di dalam satu sistem yang sama sehingga meminimalkan salah cetak dan
                        mempercepat waktu produksi.
                    </p>
                </div>

                <div class="space-y-2 text-sm text-slate-300 animate-slide-up">
                    <div class="font-semibold text-slate-100 mb-1">Kontak & Lokasi</div>
                    <div>📍 Alamat: (isi alamatmu di sini)</div>
                    <div>📞 WhatsApp: 08xx-xxxx-xxxx</div>
                    <div>✉ Email: halo@xeonprinting.com</div>
                    <div class="mt-3 text-xs text-slate-400">
                        Kami menerima pesanan online maupun datang langsung. Untuk kerja sama perusahaan
                        / instansi, silakan hubungi admin untuk penawaran khusus.
                    </div>
                </div>
            </div>
        </section>

        {{-- KATALOG UNGGULAN --}}
        <section id="katalog" class="border-t border-slate-800 bg-slate-950">
            <div class="max-w-6xl mx-auto px-4 py-10 md:py-14 space-y-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl md:text-2xl font-semibold">Produk Unggulan</h2>
                    <a href="{{ route('products.index') }}" class="text-xs md:text-sm text-sky-400 hover:underline">
                        Lihat semua katalog →
                    </a>
                </div>

                <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-5">
                    @forelse($featuredProducts as $product)
                        <div
                            class="bg-slate-900/90 border border-slate-800 rounded-2xl p-4 flex flex-col justify-between hover:border-sky-500/60 hover:-translate-y-1 hover:shadow-xl hover:shadow-sky-500/10 transition duration-200">
                            <div>
                                <div class="text-sm font-semibold mb-1">
                                    {{ $product->name }}
                                </div>
                                <div class="text-[11px] text-slate-400 mb-2">
                                    SKU: {{ $product->sku }}
                                </div>
                                <div class="text-xs text-slate-300 line-clamp-3 mb-3 min-h-[3rem]">
                                    {{ $product->short_description ?? $product->description }}
                                </div>
                            </div>
                            <div class="flex items-center justify-between mt-1">
                                <div class="text-xs text-sky-300 font-semibold">
                                    {{ $product->display_price ?? 'Harga variatif' }}
                                </div>
                                <a href="{{ route('product.show', $product->sku) }}"
                                    class="text-xs text-sky-300 hover:text-sky-200">
                                    Detail
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-slate-400 col-span-full">
                            Belum ada produk yang ditandai sebagai unggulan.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        {{-- EVENT --}}
        <section id="event" class="border-t border-slate-800 bg-slate-950">
            <div class="max-w-6xl mx-auto px-4 py-10 md:py-14 space-y-4">
                <h2 class="text-xl md:text-2xl font-semibold">Event & Promo Mendatang</h2>

                <div class="grid md:grid-cols-2 gap-5 text-sm">
                    <div
                        class="bg-gradient-to-r from-sky-600/20 via-sky-500/10 to-slate-900 border border-sky-500/40 rounded-2xl p-4 md:p-5">
                        <div class="text-sky-300 text-xs font-semibold uppercase tracking-wide mb-1">
                            Promo contoh
                        </div>
                        <div class="text-sm md:text-base font-semibold mb-1 text-slate-50">
                            Promo Akhir Tahun – Diskon 20% Banner & X-Banner
                        </div>
                        <div class="text-[11px] text-slate-300 mb-2">
                            1–31 Desember • Berlaku untuk semua order online & offline
                        </div>
                        <p class="text-xs text-slate-200">
                            Tulis detail promo atau event di sini. Nanti bisa kita hubungkan dengan modul
                            voucher atau kode promo khusus customer.
                        </p>
                    </div>

                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 md:p-5 text-xs text-slate-300">
                        <div class="font-semibold text-slate-100 mb-2">Kenapa pilih Xeon?</div>
                        <ul class="space-y-1">
                            <li>• Lead time jelas, status bisa dipantau.</li>
                            <li>• Histori order tersimpan, repeat order jadi mudah.</li>
                            <li>• Harga transparan dengan estimator otomatis.</li>
                            <li>• Workflow internal dari CS sampai gudang terintegrasi.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        {{-- TESTIMONI --}}
        <section id="testimoni" class="border-t border-slate-800 bg-slate-950">
            <div class="max-w-6xl mx-auto px-4 py-10 md:py-14 space-y-5">
                <h2 class="text-xl md:text-2xl font-semibold">Apa Kata Klien</h2>

                <div class="grid md:grid-cols-3 gap-5 text-sm">
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 flex flex-col gap-2">
                        <div class="font-semibold text-slate-100">Toko A</div>
                        <p class="text-xs text-slate-300">
                            “Order banner dan brosur jadi lebih mudah. Tinggal repeat order dari riwayat sebelumnya.”
                        </p>
                    </div>
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 flex flex-col gap-2">
                        <div class="font-semibold text-slate-100">Perusahaan B</div>
                        <p class="text-xs text-slate-300">
                            “Tracking status di Xeon Panel membantu memastikan semua materi promosi siap sebelum event.”
                        </p>
                    </div>
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 flex flex-col gap-2">
                        <div class="font-semibold text-slate-100">Freelancer C</div>
                        <p class="text-xs text-slate-300">
                            “Desain, revisi, sampai cetak tercatat rapi. Semua tim internal kamu juga terasa lebih
                            terorganisir.”
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- FOOTER --}}
    <footer class="border-t border-slate-800 bg-slate-950 py-4 text-[11px] text-slate-500 text-center">
        © {{ date('Y') }} Xeon Digital Printing. All rights reserved.
    </footer>

    {{-- POPUP KONTAK ADMIN --}}
    <div id="contact-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-950/75">
        <div class="bg-slate-900 border border-slate-700 rounded-2xl p-6 w-full max-w-md shadow-2xl">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold">Hubungi Admin</h3>
                <button type="button" onclick="document.getElementById('contact-modal').classList.add('hidden')"
                    class="w-7 h-7 inline-flex items-center justify-center rounded-full bg-slate-800 hover:bg-slate-700">
                    ✕
                </button>
            </div>
            <p class="text-xs text-slate-300 mb-3">
                Chat langsung admin untuk konsultasi desain, penawaran corporate, atau status pesanan.
            </p>
            <div class="space-y-2 text-sm">
                <a href="https://wa.me/62xxxxxxxxxx"
                    class="block px-3 py-2 rounded-full bg-sky-500 text-slate-950 font-semibold text-center hover:bg-sky-400 transition">
                    Chat via WhatsApp
                </a>
                <div class="text-xs text-slate-400 text-center">
                    Atau email ke <span class="text-slate-200">halo@xeonprinting.com</span>
                </div>
            </div>
        </div>
    </div>

    {{-- SIMPLE ANIMATIONS --}}
    <script>
        // fade-in / slide-up on scroll
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('opacity-100', 'translate-y-0');
            });
        }, {
            threshold: 0.1
        });

        document.querySelectorAll('.animate-fade-in, .animate-slide-up').forEach(el => {
            el.classList.add('opacity-0', 'translate-y-4', 'transition', 'duration-700');
            observer.observe(el);
        });
    </script>

</body>

</html>
