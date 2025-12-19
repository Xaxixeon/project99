@extends('layouts.app')

@section('title', 'Order Detail - ' . $order->order_code)

@section('content')
    <div class="max-w-6xl mx-auto p-6">

        <h1 class="text-2xl font-bold mb-4">
            Order #{{ $order->order_code }}
        </h1>

        {{-- TOP INFO CARD --}}
        <div class="bg-white p-4 rounded shadow grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <h2 class="font-semibold text-lg mb-2">Customer Info</h2>
                <p><strong>Name:</strong> {{ $order->customer->name }}</p>
                <p><strong>Email:</strong> {{ $order->customer->email }}</p>
                <p><strong>Phone:</strong> {{ $order->customer->phone }}</p>
                <p><strong>Address:</strong> {{ $order->customer->address }}</p>
            </div>

            <div>
                <h2 class="font-semibold text-lg mb-2">Order Info</h2>
                <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
                <p><strong>Subtotal:</strong> Rp {{ number_format($order->subtotal, 0, ',', '.') }}</p>
                <p><strong>Shipping:</strong> Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</p>
                <p><strong>Total:</strong> Rp {{ number_format($order->total, 0, ',', '.') }}</p>
            </div>

        </div>


        {{-- PROGRESS TIMELINE --}}
        <h2 class="text-xl font-bold mt-8 mb-3">Progress Timeline</h2>

        <div class="bg-white p-4 rounded shadow">
            <ul class="border-l-2 border-gray-300 pl-4 space-y-3">
                @php
                    $steps = [
                        'assigned' => 'Assigned',
                        'designing' => 'Designing',
                        'design_done' => 'Design Complete',
                        'production' => 'Production',
                        'printing' => 'Printing',
                        'finishing' => 'Finishing',
                        'ready' => 'Ready for Payment',
                        'paid' => 'Paid',
                        'packing' => 'Packing',
                        'shipping' => 'Shipping',
                        'completed' => 'Completed',
                    ];
                @endphp

                <div class="flex space-x-3 overflow-x-auto pb-4">

                    @foreach ($steps as $key => $label)
                        <div class="flex flex-col items-center">
                            <div
                                class="
                    w-10 h-10 rounded-full flex items-center justify-center text-white
                    @if ($order->status == $key) bg-blue-600 ring-4 ring-blue-300
                    @elseif(array_search($key, array_keys($steps)) < array_search($order->status, array_keys($steps)))
                        bg-green-600
                    @else
                        bg-gray-400 @endif
                ">
                                {{ $loop->iteration }}
                            </div>
                            <div class="text-sm mt-2 w-24 text-center">{{ $label }}</div>
                        </div>
                    @endforeach

                </div>
                @foreach ([
            'assigned_at' => 'Assigned',
            'design_started_at' => 'Design Started',
            'design_completed_at' => 'Design Completed',
            'production_started_at' => 'Production Started',
            'production_completed_at' => 'Production Finished',
            'payment_at' => 'Payment Completed',
            'shipping_at' => 'Shipping Started',
            'completed_at' => 'Order Completed',
        ] as $field => $label)
                    @if ($order->$field)
                        <li>
                            <span class="font-bold">{{ $label }}</span><br>
                            <small class="text-gray-600">{{ $order->$field->format('d M Y H:i') }}</small>
                        </li>
                    @endif
                @endforeach

            </ul>
        </div>


        {{-- ORDER ITEMS --}}
        <h2 class="text-xl font-bold mt-8 mb-3">Order Items</h2>

        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3 text-left">Product</th>
                        <th class="p-3 text-left">Qty</th>
                        <th class="p-3 text-left">Price</th>
                        <th class="p-3 text-left">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr class="border-b">
                            <td class="p-3">{{ $item->product->name }}</td>
                            <td class="p-3">{{ $item->qty }}</td>
                            <td class="p-3">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="p-3">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        {{-- DESIGN FILES --}}
        @if (count($files))
            <h2 class="text-xl font-bold mt-8 mb-3">Design Files</h2>
            <div class="bg-white rounded shadow p-4">
                @forelse ($order->logs as $log)
                    <div class="border-b py-2">
                        <p class="font-semibold">{{ $log->description }}</p>
                        <p class="text-gray-600 text-sm">
                            By {{ $log->user->name ?? 'System' }} —
                            {{ $log->created_at->diffForHumans() }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500">No activity logged yet.</p>
                @endforelse
            </div>
            <div class="bg-white p-4 rounded shadow">
                @foreach ($files as $file)
                    <div class="p-2 border-b">
                        <p>{{ $file->filename }}</p>
                        <a href="{{ asset('storage/' . $file->path) }}" target="_blank" class="text-blue-600">Download</a>
                    </div>
                @endforeach
            </div>
        @endif


        {{-- WORKFLOW BUTTONS --}}
        <h2 class="text-xl font-bold mt-8 mb-3">Actions</h2>
        <div class="bg-white p-4 rounded shadow space-x-3">

            {{-- CUSTOMER SERVICE --}}
            @if (auth()->user()->hasRole('customer_service') && $order->status == 'new')
                <x-workflow.button action="{{ route('order.assignDesigner', $order) }}" label="Assign to Designer"
                    color="blue" />
            @endif

            {{-- DESIGNER --}}
            @if (auth()->user()->hasRole('designer') && $order->status == 'assigned')
                <x-workflow.button action="{{ route('order.startDesign', $order) }}" label="Start Design" />
            @endif

            @if (auth()->user()->hasRole('designer') && $order->status == 'designing')
                <x-workflow.button action="{{ route('order.finishDesign', $order) }}" label="Finish Design"
                    color="green" />
            @endif

            {{-- PRODUCTION --}}
            @if (auth()->user()->hasRole('production') && $order->status == 'design_done')
                <x-workflow.button action="{{ route('order.startProduction', $order) }}" label="Start Production" />
            @endif

            @if (auth()->user()->hasRole('production') && $order->status == 'production')
                <x-workflow.button action="{{ route('order.print', $order) }}" label="Start Printing" color="yellow" />
            @endif

            @if (auth()->user()->hasRole('production') && $order->status == 'printing')
                <x-workflow.button action="{{ route('order.finishJob', $order) }}" label="Finish Job" color="green" />
            @endif

            @if (auth()->user()->hasRole('production') && $order->status == 'finishing')
                <x-workflow.button action="{{ route('order.ready', $order) }}" label="Mark Ready" color="blue" />
            @endif

            {{-- CASHIER --}}
            @if (auth()->user()->hasRole('cashier') && $order->status == 'ready')
                <x-workflow.button action="{{ route('order.pay', $order) }}" label="Mark Paid" color="green" />
            @endif

            {{-- WAREHOUSE --}}
            @if (auth()->user()->hasRole('warehouse') && $order->status == 'paid')
                <x-workflow.button action="{{ route('order.pack', $order) }}" label="Pack Order" />
            @endif

            @if (auth()->user()->hasRole('warehouse') && $order->status == 'packing')
                <x-workflow.button action="{{ route('order.ship', $order) }}" label="Ship Order" color="yellow" />
            @endif

            @if (auth()->user()->hasRole('warehouse') && $order->status == 'shipping')
                <x-workflow.button action="{{ route('order.complete', $order) }}" label="Complete Order" color="green" />
            @endif

        </div>

    </div>
@endsection
