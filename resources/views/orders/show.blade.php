@extends('layouts.admin')
@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">
                    Order #{{ $order->order_code }}
                </h1>
                <p class="text-gray-600 text-sm">
                    Dibuat pada {{ $order->created_at->format('d M Y H:i') }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                @php
                    $staff = auth('staff')->user();
                    $canInvoice = $staff && (method_exists($staff, 'hasRole')
                        ? ($staff->hasRole('admin') || $staff->hasRole('cashier') || $staff->hasRole('superadmin'))
                        : true);
                @endphp
                @if($canInvoice)
                    @if(!$order->invoice)
                        <form action="{{ route('orders.invoice.create', $order) }}" method="POST">
                            @csrf
                            <button class="px-4 py-2 rounded bg-sky-600 text-white text-sm">
                                Generate Invoice
                            </button>
                        </form>
                    @else
                        <a href="{{ route('invoices.show', $order->invoice) }}"
                           class="px-4 py-2 rounded bg-slate-700 text-white text-sm">
                            Lihat Invoice
                        </a>
                    @endif
                @endif

                <a href="{{ route('orders.index') }}" class="text-sm text-gray-600 hover:underline">
                    ← Kembali ke daftar order
                </a>
            </div>
        </div>

        {{-- Info Utama --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- Customer --}}
            <div class="bg-white p-4 rounded-xl shadow space-y-2">
                <h2 class="font-bold text-gray-800 flex items-center gap-2">
                    👤 Customer
                </h2>
                @if ($order->customer)
                    <div class="font-semibold">{{ $order->customer->name }}</div>
                    <div class="text-sm text-gray-600">{{ $order->customer->phone }}</div>
                    <div class="text-sm text-gray-600">{{ $order->customer->email }}</div>

                    <div class="mt-2 flex flex-wrap gap-2">
                        <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700">
                            Member: {{ strtoupper($order->customer->member_type ?? 'basic') }}
                        </span>
                        @if ($order->customer->instansi)
                            <span class="px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-700">
                                Instansi: {{ $order->customer->instansi->name }}
                            </span>
                        @endif
                    </div>
                @else
                    <div class="text-sm text-gray-500">Customer tidak terdaftar (walk-in)</div>
                @endif
            </div>

            {{-- Status / Workflow --}}
            <div class="bg-white p-4 rounded-xl shadow space-y-2">
                <h2 class="font-bold text-gray-800 flex items-center gap-2">
                    🔄 Status Produksi
                </h2>

                @php
                    $statusOrder = array_keys($workflowSteps);
                    $currentIndex = array_search($order->status, $statusOrder);
                @endphp

                <div class="space-y-2 text-sm">
                    @foreach ($workflowSteps as $key => $label)
                        @php
                            $index = array_search($key, $statusOrder);
                            $done = $index !== false && $index <= $currentIndex;
                        @endphp
                        <div class="flex items-center gap-2">
                            <div
                                class="w-3 h-3 rounded-full 
                            {{ $done ? 'bg-green-500' : 'bg-gray-300' }}">
                            </div>
                            <span class="{{ $done ? 'text-gray-900 font-semibold' : 'text-gray-500' }}">
                                {{ $label }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Payment Summary --}}
            <div class="bg-white p-4 rounded-xl shadow space-y-2">
                <h2 class="font-bold text-gray-800 flex items-center gap-2">
                    💰 Pembayaran
                </h2>

                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal</span>
                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Ongkir</span>
                    <span>Rp {{ number_format($order->shipping, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Diskon</span>
                    <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                </div>
                <div class="border-t my-2"></div>
                <div class="flex justify-between font-bold">
                    <span>Total</span>
                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>

                <div class="mt-2 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Metode</span>
                        <span class="font-medium">{{ strtoupper($order->payment_method ?? '-') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Status Pembayaran</span>
                        <span
                            class="px-2 py-1 rounded-full text-xs
                        {{ $order->status === 'paid' || $order->status === 'completed'
                            ? 'bg-green-100 text-green-700'
                            : 'bg-yellow-100 text-yellow-700' }}">
                            {{ in_array($order->status, ['paid', 'completed']) ? 'Lunas' : 'Belum Lunas' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Items --}}
        <div class="bg-white p-4 rounded-xl shadow space-y-3">
            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                📦 Item Pesanan
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-left">
                        <tr>
                            <th class="p-3">Produk</th>
                            <th class="p-3">Detail</th>
                            <th class="p-3 text-center">Qty</th>
                            <th class="p-3 text-right">Harga Satuan</th>
                            <th class="p-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr class="border-t align-top">
                                <td class="p-3">
                                    <div class="font-semibold">{{ $item->name }}</div>
                                    <div class="text-xs text-gray-500">
                                        SKU: {{ $item->product->sku ?? '-' }}
                                    </div>
                                </td>
                                <td class="p-3 text-xs text-gray-700">
                                    @php $attrs = $item->attributes ?? []; @endphp
                                    @if (!empty($attrs))
                                        <ul class="list-disc pl-4 space-y-1">
                                            @foreach ($attrs as $k => $v)
                                                @if (is_array($v))
                                                    <li><span class="font-semibold">{{ ucfirst($k) }}</span>:
                                                        {{ implode(', ', $v) }}</li>
                                                @else
                                                    <li><span class="font-semibold">{{ ucfirst($k) }}</span>:
                                                        {{ $v }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-gray-400">Tidak ada atribut khusus</span>
                                    @endif

                                    @if ($item->design_file)
                                        <div class="mt-2">
                                            <a href="{{ $item->design_file }}" target="_blank"
                                                class="text-xs text-blue-600 hover:underline">
                                                📎 File desain
                                            </a>
                                        </div>
                                    @endif

                                    @if ($item->note)
                                        <div class="mt-1 text-xs text-gray-600">
                                            📝 {{ $item->note }}
                                        </div>
                                    @endif
                                </td>
                                <td class="p-3 text-center">{{ $item->qty }}</td>
                                <td class="p-3 text-right">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td class="p-3 text-right font-semibold">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Files --}}
        <div class="bg-white p-4 rounded-xl shadow space-y-3">
            <h3 class="text-lg font-semibold">Files</h3>

            <ul class="space-y-2">
                @foreach($order->files as $f)
                    <li class="flex items-center justify-between bg-slate-800 px-3 py-2 rounded">
                        <div>
                            <div class="font-semibold text-sm text-white">{{ $f->file_original_name }}</div>
                            <div class="text-xs text-slate-400">
                                {{ $f->type }} • {{ $f->created_at->format('d M Y H:i') }}
                                @if($f->approved)
                                    • <span class="text-emerald-400">Approved</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex gap-2 items-center">
                            <a href="{{ Storage::url($f->file_path) }}"
                               class="text-xs bg-slate-700 px-2 py-1 rounded text-white">Download</a>

                            @if(auth('staff')->check() && !$f->approved)
                                <form action="{{ route('staff.order-files.approve', $f) }}" method="POST">
                                    @csrf
                                    <button class="text-xs bg-emerald-600 px-2 py-1 rounded text-white">
                                        Approve
                                    </button>
                                </form>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Order Chat --}}
        <div class="bg-white p-4 rounded-xl shadow space-y-3">
            <h3 class="text-lg font-semibold">Order Chat</h3>

            <div class="max-h-64 overflow-y-auto space-y-2 mb-3 bg-slate-900 p-3 rounded">
                @foreach($order->chats as $chat)
                    <div class="flex {{ $chat->sender_type === 'customer' ? 'justify-start' : 'justify-end' }}">
                        <div class="max-w-xs bg-slate-800 px-3 py-2 rounded text-sm text-white">
                            <div class="text-xs text-slate-400 mb-1">
                                {{ ucfirst($chat->sender_type) }} • {{ $chat->created_at->format('d M Y H:i') }}
                            </div>
                            @if($chat->message)
                                <div>{{ $chat->message }}</div>
                            @endif
                            @if($chat->attachment)
                                <a href="{{ Storage::url($chat->attachment) }}" class="text-xs text-sky-400 underline mt-1 block">
                                    Download attachment
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <form action="{{ auth('staff')->check()
                    ? route('staff.orders.chat.store', $order)
                    : route('customer.orders.chat.store', $order) }}"
                  method="POST" enctype="multipart/form-data"
                  class="flex gap-2">
                @csrf
                <input type="text" name="message" placeholder="Tulis pesan..."
                       class="flex-1 px-3 py-2 rounded bg-slate-900 border border-slate-700 text-sm text-white">
                <input type="file" name="attachment" class="text-xs text-slate-600">
                <button class="px-4 py-2 rounded bg-sky-600 text-white text-sm">
                    Kirim
                </button>
            </form>
        </div>

        @if(auth('staff')->check())
            {{-- QC Checklist --}}
            <div class="bg-white p-4 rounded-xl shadow space-y-3">
                <h3 class="text-lg font-semibold">QC Checklist</h3>

                <table class="w-full bg-slate-900 rounded text-sm mb-3">
                    <tr class="border-b border-slate-700 text-slate-300">
                        <th class="p-2 text-left">Item</th>
                        <th class="p-2 text-left">Notes</th>
                        <th class="p-2 text-center">Status</th>
                        <th class="p-2 text-center">Action</th>
                    </tr>
                    @forelse($order->qcChecks as $qc)
                        <tr class="border-b border-slate-800">
                            <td class="p-2">{{ $qc->item }}</td>
                            <td class="p-2 text-slate-300">{{ $qc->notes }}</td>
                            <td class="p-2 text-center">
                                @if($qc->passed)
                                    <span class="px-2 py-1 rounded text-xs bg-emerald-600 text-white">Passed</span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs bg-red-600 text-white">Pending</span>
                                @endif
                            </td>
                            <td class="p-2 text-center">
                                <form action="{{ route('orders.qc.toggle', $qc) }}" method="POST">
                                    @csrf
                                    <button class="px-2 py-1 text-xs rounded bg-slate-700 text-white">
                                        Toggle
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-3 text-center text-slate-500">
                                Belum ada QC check.
                            </td>
                        </tr>
                    @endforelse
                </table>

                <form action="{{ route('orders.qc.store', $order) }}" method="POST" class="flex flex-col md:flex-row gap-2">
                    @csrf
                    <input type="text" name="item" placeholder="Item QC, misal: Warna sesuai proof"
                           class="flex-1 px-3 py-2 rounded bg-slate-900 border border-slate-700 text-sm text-white" required>
                    <input type="text" name="notes" placeholder="Catatan (opsional)"
                           class="flex-1 px-3 py-2 rounded bg-slate-900 border border-slate-700 text-sm text-white">
                    <label class="inline-flex items-center text-sm text-slate-300">
                        <input type="checkbox" name="passed" value="1" class="mr-1">
                        Passed
                    </label>
                    <button class="px-4 py-2 rounded bg-emerald-600 text-sm text-white">
                        Tambah QC
                    </button>
                </form>
            </div>

            {{-- Operasi Produksi --}}
            <div class="bg-white p-4 rounded-xl shadow space-y-3">
                <h3 class="text-lg font-semibold">Operasi Produksi</h3>

                <table class="w-full bg-slate-900 rounded text-sm mb-3">
                    <tr class="border-b border-slate-700 text-slate-300">
                        <th class="p-2 text-left">Jenis</th>
                        <th class="p-2 text-left">Staff</th>
                        <th class="p-2 text-left">Status</th>
                        <th class="p-2 text-left">Waktu</th>
                        <th class="p-2 text-left">Catatan</th>
                        <th class="p-2 text-center">Action</th>
                    </tr>
                    @forelse($order->operations as $op)
                        <tr class="border-b border-slate-800">
                            <td class="p-2 capitalize">{{ str_replace('_', ' ', $op->type) }}</td>
                            <td class="p-2 text-slate-300">{{ $op->staff?->name ?? '-' }}</td>
                            <td class="p-2">
                                @php
                                    $color = $op->status === 'done' ? 'bg-emerald-600' : ($op->status === 'in_progress' ? 'bg-amber-500' : 'bg-slate-600');
                                @endphp
                                <span class="px-2 py-1 rounded text-xs text-white {{ $color }}">
                                    {{ strtoupper($op->status) }}
                                </span>
                            </td>
                            <td class="p-2 text-xs text-slate-400">
                                Mulai: {{ $op->started_at?->format('d M H:i') ?? '-' }}<br>
                                Selesai: {{ $op->finished_at?->format('d M H:i') ?? '-' }}
                            </td>
                            <td class="p-2 text-slate-300">{{ $op->notes }}</td>
                            <td class="p-2 text-center">
                                <div class="flex gap-1 justify-center">
                                    @if($op->status === 'pending')
                                        <form action="{{ route('orders.operations.start', $op) }}" method="POST">
                                            @csrf
                                            <button class="px-2 py-1 text-xs rounded bg-sky-600 text-white">
                                                Start
                                            </button>
                                        </form>
                                    @endif

                                    @if(in_array($op->status, ['pending','in_progress']))
                                        <form action="{{ route('orders.operations.finish', $op) }}" method="POST">
                                            @csrf
                                            <button class="px-2 py-1 text-xs rounded bg-emerald-600 text-white">
                                                Finish
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-3 text-center text-slate-500">
                                Belum ada operasi produksi.
                            </td>
                        </tr>
                    @endforelse
                </table>

                <form action="{{ route('orders.operations.store', $order) }}" method="POST"
                      class="flex flex-col md:flex-row gap-2">
                    @csrf
                    <select name="type"
                            class="px-3 py-2 rounded bg-slate-900 border border-slate-700 text-sm text-white" required>
                        <option value="">Pilih operasi</option>
                        <option value="printing">Printing</option>
                        <option value="laminating">Laminating</option>
                        <option value="cutting">Cutting</option>
                        <option value="finishing_manual">Finishing Manual</option>
                        <option value="qc">QC</option>
                        <option value="packaging">Packaging</option>
                        <option value="delivery">Delivery</option>
                    </select>
                    <input type="text" name="notes" placeholder="Catatan"
                           class="flex-1 px-3 py-2 rounded bg-slate-900 border border-slate-700 text-sm text-white">
                    <button class="px-4 py-2 rounded bg-sky-600 text-sm text-white">
                        Tambah Operasi
                    </button>
                </form>
            </div>
        @endif

        {{-- Logs / Timeline --}}
        <div class="bg-white p-4 rounded-xl shadow space-y-3">
            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                📜 Log Aktivitas Order
            </h2>

            @if ($order->logs->isEmpty())
                <p class="text-sm text-gray-500">Belum ada log aktivitas.</p>
            @else
                <ul class="space-y-2 text-sm">
                    @foreach ($order->logs as $log)
                        <li class="flex gap-3">
                            <div class="text-xs text-gray-500 w-32">
                                {{ $log->created_at->format('d M Y H:i') }}
                            </div>
                            <div class="flex-1">
                                <span class="font-semibold">
                                    {{ $log->user->name ?? 'System' }}
                                </span>
                                <span class="text-gray-700">
                                    {{ $log->message ?? $log->status }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div>
@endsection
