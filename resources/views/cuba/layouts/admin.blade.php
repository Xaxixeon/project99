<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Admin Dashboard' }}</title>

    {{-- CSS Cuba --}}
    <link rel="stylesheet" href="{{ asset('cuba/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('cuba/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('cuba/css/icons.css') }}">
</head>

<body>
    <div id="toast-container" style="position:fixed;top:20px;right:20px;z-index:9999">
    </div>

    {{-- SIDEBAR --}}
    @include('cuba.components.sidebar')

    {{-- HEADER --}}
    @include('cuba.components.header')

    <div class="main-content">
        <div class="container-fluid pt-3">
            @yield('content')
        </div>
    </div>

    {{-- JS Cuba --}}
    <script src="{{ asset('cuba/js/jquery.min.js') }}"></script>
    <script src="{{ asset('cuba/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('cuba/js/app.js') }}"></script>

    <div id="toast-container"></div>

    <style>
        #toast-container {
            position: fixed;
            top: 80px;
            right: 25px;
            z-index: 9999;
        }

        .toast {
            background: linear-gradient(135deg, #1e293b, #0f172a);
            color: #e5e7eb;
            padding: 14px 18px;
            margin-bottom: 12px;
            border-radius: 10px;
            min-width: 260px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .45);
            animation: slideIn .4s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .toast.success {
            border-left: 4px solid #22c55e;
        }

        .toast.info {
            border-left: 4px solid #38bdf8;
        }

        .toast.warning {
            border-left: 4px solid #facc15;
        }

        @keyframes slideIn {
            from {
                transform: translateX(120%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</body>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (!window.Echo) return;

        Echo.channel('admin-orders')
            .listen('.order.created', (e) => {
                showToast(
                    'Order Baru Masuk',
                    'Order #' + e.order.id + ' berhasil dibuat'
                );
            });
    });

    function showToast(title, message) {
        const toast = document.createElement('div');

        toast.className = 'alert alert-primary shadow mb-2';
        toast.innerHTML = `
        <strong>${title}</strong><br>
        <small>${message}</small>
    `;

        document.getElementById('toast-container').appendChild(toast);

        setTimeout(() => toast.remove(), 5000);
    }
</script>


</html>
