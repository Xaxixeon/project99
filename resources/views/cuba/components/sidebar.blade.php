<div id="sidebar-wrapper" class="sidebar-pro-max">

    {{-- HEADER --}}
    <div class="sidebar-header d-flex align-items-center justify-content-between px-3 py-3">
        <h4 class="text-white fw-bold m-0">Xeon Panel</h4>

        <button id="sidebarCollapse" class="btn btn-sm btn-outline-light">
            <i class="fa fa-bars"></i>
        </button>
    </div>

    {{-- SIDEBAR SEARCH --}}
    <div class="sidebar-search px-3 mb-2">
        <input type="text" id="sidebarSearch" placeholder="Cari menu..." class="form-control">
    </div>

    <ul class="sidebar-menu" id="sidebarMenu">

        {{-- Dashboard --}}
        <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                <i class="fa fa-home me-2"></i>
                <span>Dashboard</span>

                {{-- Example badge --}}
                <span class="badge bg-info ms-auto">NEW</span>
            </a>
        </li>

        {{-- Customers --}}
        <li
            class="sidebar-item {{ request()->routeIs('admin.customers.*') || request()->routeIs('admin.member-type.*') || request()->routeIs('admin.instansi.*') ? 'open active' : '' }}">
            <a class="sidebar-link has-arrow">
                <i class="fa fa-users me-2"></i>
                <span>Customer</span>

                {{-- Badge displaying active users --}}
                <span class="badge bg-success ms-auto">{{ $activeCustomers ?? 0 }}</span>
            </a>

            <ul class="sidebar-submenu">
                <li><a href="{{ route('admin.customers.index') }}">Daftar Customer</a></li>
                <li><a href="{{ route('admin.member-type.index') }}">Tipe Member</a></li>
                <li><a href="{{ route('admin.instansi.index') }}">Instansi</a></li>
            </ul>
        </li>

        {{-- Staff --}}
        <li class="sidebar-item {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
            <a href="{{ route('admin.staff.index') }}" class="sidebar-link">
                <i class="fa fa-user-tie me-2"></i>
                <span>Staff</span>
            </a>
        </li>

        {{-- Produk --}}
        {{-- PRODUK --}}
        <li class="sidebar-item">
            <a href="{{ route('admin.products.index') }}" class="sidebar-link">
                <i class="fa fa-box"></i>
                <span>Produk</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="{{ route('admin.product-variants.index') }}" class="sidebar-link">
                <i class="fa fa-layer-group"></i>
                <span>Varian Produk</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="{{ route('admin.printing-materials.index') }}" class="sidebar-link">
                <i class="fa fa-scroll"></i>
                <span>Material Cetak</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="{{ route('admin.printing-finishings.index') }}" class="sidebar-link">
                <i class="fa fa-magic"></i>
                <span>Finishing</span>
            </a>
        </li>

        {{-- Pricing --}}
        <li class="sidebar-item {{ request()->routeIs('admin.pricing.*') ? 'active' : '' }}">
            <a href="{{ route('admin.pricing.index') }}" class="sidebar-link">
                <i class="fa fa-tags me-2"></i>
                <span>Harga Produk</span>
            </a>
        </li>

        {{-- Orders --}}
        <li class="sidebar-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <a href="{{ route('admin.orders.index') }}" class="sidebar-link">
                <i class="fa fa-shopping-cart me-2"></i>
                <span>Order & Tracking</span>

                {{-- Example realtime counter --}}
                <span class="badge bg-warning ms-auto">{{ $pendingOrders ?? 0 }}</span>
            </a>
        </li>

        {{-- Payment --}}
        <li class="sidebar-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
            <a href="{{ route('admin.payments.index') }}" class="sidebar-link">
                <i class="fa fa-credit-card me-2"></i>
                <span>Metode Pembayaran</span>
            </a>
        </li>

        {{-- Reports --}}
        <li class="sidebar-item disabled">
            <a class="sidebar-link">
                <i class="fa fa-chart-line me-2"></i>
                <span>Laporan Keuangan (Soon)</span>
            </a>
        </li>

    </ul>
</div>

{{-- PRO MAX SCRIPT --}}
<script>
    document.addEventListener("DOMContentLoaded", () => {

        // Sidebar collapse
        document.getElementById("sidebarCollapse").onclick = () => {
            document.getElementById("sidebar-wrapper").classList.toggle("collapsed");
        };

        // Submenu toggle
        document.querySelectorAll(".has-arrow").forEach(menu => {
            menu.addEventListener("click", () => {
                menu.closest(".sidebar-item").classList.toggle("open");
            });
        });

        // Sidebar Search
        const searchInput = document.getElementById("sidebarSearch");
        const menuItems = document.querySelectorAll("#sidebarMenu .sidebar-item");

        searchInput.addEventListener("keyup", function() {
            let q = this.value.toLowerCase();

            menuItems.forEach(item => {
                let text = item.innerText.toLowerCase();
                item.style.display = text.includes(q) ? "" : "none";
            });
        });

    });
</script>

<style>
    /* SIDEBAR PRO MAX VISUALS */
    .sidebar-pro-max {
        width: 260px;
        min-height: 100vh;
        background: #0e1726;
        transition: 0.3s ease;
    }

    .sidebar-pro-max.collapsed {
        width: 80px;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-item .sidebar-link {
        padding: 13px 22px;
        display: flex;
        align-items: center;
        color: #cbd5e1;
        transition: 0.2s;
    }

    .sidebar-item.active>.sidebar-link,
    .sidebar-item .sidebar-link:hover {
        background: #1e293b;
        color: #38bdf8;
    }

    .sidebar-item .has-arrow:after {
        content: "›";
        margin-left: auto;
        transition: 0.2s;
    }

    .sidebar-item.open .has-arrow:after {
        transform: rotate(90deg);
    }

    .sidebar-submenu {
        max-height: 0;
        overflow: hidden;
        padding-left: 45px;
        transition: 0.3s ease;
    }

    .sidebar-item.open .sidebar-submenu {
        max-height: 500px;
    }

    .sidebar-submenu a {
        display: block;
        padding: 8px 0;
        color: #94a3b8;
    }

    .sidebar-submenu a:hover {
        color: #38bdf8;
    }

    .disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* SEARCH */
    .sidebar-search input {
        background: #1a2234;
        border: 1px solid #334155;
        color: white;
    }
</style>
