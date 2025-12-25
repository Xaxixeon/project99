<x-admin.layout>

    {{-- PAGE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-white">Admin Dashboard</h2>
    </div>

    {{-- TOP STATS CARDS --}}
    <div class="row">

        {{-- Total Orders --}}
        <div class="col-md-3 mb-4">
            <div class="dash-card">
                <div class="dash-card-icon bg-primary"><i class="fa fa-shopping-cart"></i></div>
                <div class="dash-card-info">
                    <p class="dash-card-title">Total Orders</p>
                    <h3 class="dash-card-value" id="totalOrders">{{ $totalOrders ?? 0 }}</h3>
                </div>
            </div>
        </div>

        {{-- Paid Orders --}}
        <div class="col-md-3 mb-4">
            <div class="dash-card">
                <div class="dash-card-icon bg-success"><i class="fa fa-check-circle"></i></div>
                <div class="dash-card-info">
                    <p class="dash-card-title">Paid Orders</p>
                    <h3 class="dash-card-value" id="paidOrders">{{ $paidOrders ?? 0 }}</h3>
                </div>
            </div>
        </div>

        {{-- Pending Orders --}}
        <div class="col-md-3 mb-4">
            <div class="dash-card">
                <div class="dash-card-icon bg-warning"><i class="fa fa-clock"></i></div>
                <div class="dash-card-info">
                    <p class="dash-card-title">Pending Payment</p>
                    <h3 class="dash-card-value" id="pendingOrders">{{ $pendingOrders ?? 0 }}</h3>
                </div>
            </div>
        </div>

        {{-- Processing Orders --}}
        <div class="col-md-3 mb-4">
            <div class="dash-card">
                <div class="dash-card-icon bg-info"><i class="fa fa-bolt"></i></div>
                <div class="dash-card-info">
                    <p class="dash-card-title">Processing</p>
                    <h3 class="dash-card-value" id="processingOrders">{{ $processingOrders ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- CUSTOMER + PRODUCT CARDS --}}
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="dash-card">
                <div class="dash-card-icon bg-danger"><i class="fa fa-users"></i></div>
                <div class="dash-card-info">
                    <p class="dash-card-title">Total Customers</p>
                    <h3 class="dash-card-value">{{ $totalCustomers ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="dash-card">
                <div class="dash-card-icon bg-success"><i class="fa fa-user-check"></i></div>
                <div class="dash-card-info">
                    <p class="dash-card-title">Active Customers</p>
                    <h3 class="dash-card-value">{{ $activeCustomers ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="dash-card">
                <div class="dash-card-icon bg-primary"><i class="fa fa-box"></i></div>
                <div class="dash-card-info">
                    <p class="dash-card-title">Total Produk</p>
                    <h3 class="dash-card-value">{{ $productCount ?? 0 }}</h3>
                </div>
            </div>
        </div>

    </div>

    {{-- CHARTS --}}
    <div class="row">

        {{-- LINE CHART --}}
        <div class="col-md-8 mb-4">
            <div class="chart-box">
                <h5 class="chart-title">Orders 7 Hari Terakhir</h5>
                <canvas id="ordersChart" height="120"></canvas>
            </div>
        </div>

        {{-- DONUT CHART --}}
        <div class="col-md-4 mb-4">
            <div class="chart-box">
                <h5 class="chart-title">Order Distribution</h5>
                <canvas id="donutChart" height="120"></canvas>
            </div>
        </div>

    </div>

    {{-- LATEST ORDERS --}}
    <div class="card bg-dark border-0 shadow mt-3">
        <div class="card-header border-secondary text-white">
            <h5 class="m-0 fw-bold">Order Terbaru</h5>
        </div>

        <div class="card-body p-0">
            <table class="table table-dark table-hover m-0">
                <tbody>
                    @foreach ($latestOrders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->customer->name ?? '-' }}</td>
                            <td>Rp {{ number_format($order->total) }}</td>
                            <td>
                                @if ($order->status === 'pending')
                                    <button class="btn btn-sm btn-success" onclick="markPaid({{ $order->id }})">
                                        <i class="fa fa-check"></i> Mark as Paid
                                    </button>
                                @else
                                    <span class="badge bg-success">Paid</span>
                                @endif
                            </td>

                            <td>{{ $order->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>

                <th>Waktu</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($latestOrders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->customer->name ?? '-' }}</td>
                            <td>Rp {{ number_format($order->total) }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status === 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    {{-- SCRIPTS --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        new Chart(document.getElementById('ordersChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: "Orders",
                    data: {!! json_encode($chartValues) !!},
                    borderColor: "#4fc3f7",
                    backgroundColor: "rgba(79,195,247,0.2)",
                    borderWidth: 3,
                    tension: 0.4
                }]
            }
        });

        new Chart(document.getElementById('donutChart'), {
            type: 'doughnut',
            data: {
                labels: ["Paid", "Pending", "Processing"],
                datasets: [{
                    data: [
                        {{ $paidOrders }},
                        {{ $pendingOrders }},
                        {{ $processingOrders }}
                    ],
                    backgroundColor: ["#4caf50", "#ff9800", "#03a9f4"]
                }]
            }
        });
    </script>

    <style>
        .dash-card {
            display: flex;
            background: #1a2332;
            padding: 18px;
            border-radius: 12px;
            align-items: center;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.3);
        }

        .dash-card-icon {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: white;
            margin-right: 15px;
        }

        .dash-card-title {
            color: #b0bec5;
            margin: 0;
        }

        .dash-card-value {
            color: white;
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }

        .chart-box {
            background: #1a2332;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.3);
        }

        .chart-title {
            color: #cfd8dc;
            margin-bottom: 15px;
        }
    </style>

    <script>
        window.Echo.channel('dashboard').listen('OrderUpdated', (e) => {

            // Update cards
            document.getElementById("totalOrders").innerText = e.totalOrders;
            document.getElementById("pendingOrders").innerText = e.pendingOrders;
            document.getElementById("paidOrders").innerText = e.paidOrders;

            // Sidebar badge realtime
            const sideBadge = document.getElementById("sidebarPendingOrders");
            if (sideBadge) sideBadge.innerText = e.pendingOrders;

            console.log("Dashboard updated:", e);
        });
    </script>

    <script>
        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `
        <i class="fa fa-bell"></i>
        <span>${message}</span>
    `;

            container.appendChild(toast);

            setTimeout(() => toast.remove(), 4000);
        }

        // 🔥 REALTIME LISTENER
        window.Echo.channel('dashboard')
            .listen('OrderUpdated', (e) => {
                showToast(e.message, e.type);
                console.log('Realtime toast:', e);
            });
    </script>

    <script>
        function markPaid(orderId) {
            fetch(`/admin/orders/${orderId}/mark-paid`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    if (!res.success) return;
                });
        }
    </script>

    <script>
        window.Echo.channel('dashboard')
            .listen('.order.paid', (e) => {

                // Update counters
                document.querySelectorAll('.dash-card-value')[0].innerText = e.totalOrders;
                document.querySelectorAll('.dash-card-value')[1].innerText = e.paidOrders;
                document.querySelectorAll('.dash-card-value')[2].innerText = e.pendingOrders;

                // Toast
                showToast(e.message, e.type);

                // Auto refresh latest orders (opsional cepat)
                setTimeout(() => location.reload(), 800);
            });
    </script>

    <div id="toast-container" style="position:fixed;top:20px;right:20px;z-index:9999"></div>

</x-admin.layout>
