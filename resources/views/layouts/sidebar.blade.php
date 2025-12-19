<aside class="w-64 bg-white shadow-lg min-h-screen py-6 px-4">

    <div class="text-xl font-bold mb-6">XEON Panel</div>

    <nav class="space-y-2">

        {{-- Dashboard umum --}}
        <a class="block px-3 py-2 rounded hover:bg-gray-200" href="{{ route('home') }}">Home</a>

        {{-- ADMIN --}}
        @role('admin')
            <h3 class="text-xs uppercase text-gray-500 mt-4 mb-1">Admin Menu</h3>
            <a class="block px-3 py-2 hover:bg-gray-200" href="/dashboard/admin">Dashboard Admin</a>
            <a class="block px-3 py-2 hover:bg-gray-200" href="/dashboard/admin/products">Kelola Produk</a>
            <a class="block px-3 py-2 hover:bg-gray-200" href="/dashboard/admin/users">Kelola User</a>
            <a class="block px-3 py-2 hover:bg-gray-200" href="/dashboard/admin/orders">Kelola Order</a>
        @endrole

        {{-- CUSTOMER SERVICE --}}
        @role('customer_service')
            <h3 class="text-xs uppercase text-gray-500 mt-4 mb-1">Customer Service</h3>
            <a class="block px-3 py-2 hover:bg-gray-200" href="/dashboard/customer-service">Dashboard CS</a>
            <a class="block px-3 py-2 hover:bg-gray-200" href="/dashboard/customer-service/orders">Order Masuk</a>
        @endrole

        {{-- DESIGNER --}}
        @role('designer')
            <h3 class="text-xs uppercase text-gray-500 mt-4 mb-1">Designer</h3>
            <a class="block px-3 py-2 hover:bg-gray-200" href="/dashboard/designer">Antrian Desain</a>
        @endrole

        {{-- PRODUCTION --}}
        @role('production')
            <h3 class="text-xs uppercase text-gray-500 mt-4 mb-1">Produksi</h3>
            <a class="block px-3 py-2 hover:bg-gray-200" href="/dashboard/production">Antrian Cetak</a>
            <a class="block px-3 py-2 hover:bg-gray-200" href="/dashboard/production/finishing">Antrian Finishing</a>
        @endrole

        {{-- CASHIER --}}
        @role('cashier')
            <h3 class="text-xs uppercase text-gray-500 mt-4 mb-1">Kasir</h3>
            <a class="block px-3 py-2 hover:bg-gray-200" href="/dashboard/cashier">Pembayaran</a>
        @endrole

        {{-- WAREHOUSE --}}
        @role('warehouse')
            <h3 class="text-xs uppercase text-gray-500 mt-4 mb-1">Gudang</h3>
            <a class="block px-3 py-2 hover:bg-gray-200" href="/dashboard/warehouse">Stok & Material</a>
        @endrole

        {{-- MARKETING --}}
        @role('marketing')
            <h3 class="text-xs uppercase text-gray-500 mt-4 mb-1">Marketing</h3>
            <a class="block px-3 py-2 hover:bg-gray-200" href="/dashboard/marketing">Lead & Campaign</a>
        @endrole

        {{-- MANAGER --}}
        @role('manager')
            <h3 class="text-xs uppercase text-gray-500 mt-4 mb-1">Manager</h3>
            <a class="block px-3 py-2 hover:bg-gray-200" href="/dashboard/manager">Laporan & Analitik</a>
        @endrole

    </nav>

</aside>
