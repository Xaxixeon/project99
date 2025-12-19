<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3" id="cuba-navbar">

    {{-- Sidebar Toggle Button --}}
    <button class="btn btn-outline-secondary me-3" id="sidebarToggleMain">
        <i class="fa fa-bars"></i>
    </button>

    {{-- Title --}}
    <div class="navbar-brand fw-bold text-primary">
        Admin Panel
    </div>

    <div class="ms-auto d-flex align-items-center">

        {{-- Dark Mode Toggle --}}
        <button class="btn btn-light me-3" id="darkModeToggle">
            <i class="fa fa-moon"></i>
        </button>

        {{-- Notifications --}}
        <div class="dropdown me-3">
            <button class="btn btn-light position-relative" data-bs-toggle="dropdown">
                <i class="fa fa-bell"></i>
                <span class="badge bg-danger position-absolute top-0 end-0 rounded-pill">3</span>
            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="width: 280px;">
                <li class="dropdown-header fw-bold">Notifications</li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item small" href="#">New order received</a></li>
                <li><a class="dropdown-item small" href="#">Customer registered</a></li>
                <li><a class="dropdown-item small" href="#">System backup completed</a></li>
            </ul>
        </div>

        {{-- User Menu --}}
        <div class="dropdown">
            <button class="btn btn-light d-flex align-items-center" data-bs-toggle="dropdown">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}"
                    class="rounded-circle me-2" width="32" height="32">
                <span>{{ Auth::user()->name }}</span>
                <i class="fa fa-chevron-down ms-2 small"></i>
            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li class="dropdown-header small">Account</li>
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item text-danger">
                            <i class="fa fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    #cuba-navbar {
        position: sticky;
        top: 0;
        z-index: 998;
    }

    #cuba-navbar .dropdown-menu {
        border-radius: 10px;
    }

    /* Dark Mode */
    .dark-mode {
        background: #1e1e1e !important;
        color: #e5e7eb !important;
    }

    .dark-mode .card {
        background: #2a2a2a !important;
        color: #e5e7eb !important;
    }

    .dark-mode .navbar {
        background: #2a2a2a !important;
    }

    .dark-mode .sidebar-wrapper {
        background: #2a2a2a !important;
    }

    #darkModeToggle.active i {
        color: #facc15 !important;
    }
</style>


<script>
    // Toggle Sidebar
    document.getElementById('sidebarToggleMain')
        .addEventListener('click', function() {
            document.getElementById('sidebar-wrapper').classList.toggle('collapsed');
        });

    // Dark Mode Toggle
    const darkToggle = document.getElementById('darkModeToggle');

    darkToggle.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        darkToggle.classList.toggle('active');

        localStorage.setItem('darkMode',
            document.body.classList.contains('dark-mode') ? 'enabled' : 'disabled'
        );
    });

    // Load saved dark mode state
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode');
        darkToggle.classList.add('active');
    }
</script>
