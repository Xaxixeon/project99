<?php

namespace App\Http\Controllers\Admin;

use App\Events\OrderUpdated;
use App\Events\OrderPaid;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\PrintingMaterial;
use App\Models\PrintingFinishing;
use App\Models\Order;
use App\Events\OrderCreated;
use App\Services\PricingEngine;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.orders.create', [
            'products' => Product::with('variants')->get(),
            'materials' => PrintingMaterial::all(),
            'finishings' => PrintingFinishing::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $pricing = PricingEngine::calculate($request->all());
        // logic simpan order
        $order = Order::create([
            'product_variant_id' => $request->variant_id,
            'material_id'        => $request->material_id,
            'finishing_id'       => $request->finishing_id,
            'quantity'           => $pricing['quantity'],
            'width'              => $request->width,
            'height'             => $request->height,
            'area'               => $pricing['area'],

            'subtotal'           => $pricing['subtotal'],
            'discount_percent'   => $pricing['discount_percent'],
            'discount_amount'    => $pricing['discount_amount'],
            'tax_amount'         => $pricing['tax_amount'],
            'total'              => $pricing['total'],
        ]);

        // 🔥 trigger realtime
        event(new OrderCreated($order));
        event(new OrderUpdated());

        return redirect()->route('admin.orders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $order->update();

        // 🔥 trigger realtime
        event(new OrderUpdated(
            'Status order diperbarui',
            'info'
        ));

        return redirect()->route('admin.orders.show', $order->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    public function invoicePdf(Order $order)
    {
        $order->load([
            'productVariant.product',
            'material',
            'finishing',
            'customer',
        ]);

        $pdf = Pdf::loadView('admin.orders.invoice', [
            'order' => $order
        ])->setPaper('A4');

        return $pdf->download(
            'INV-' . $order->id . '.pdf'
        );
    }

    public function markAsPaid(Request $request, Order $order)
    {
        if ($order->status === 'paid') {
            return response()->json(['message' => 'Order already paid'], 422);
        }

        $order->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        broadcast(new OrderPaid($order))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Order marked as paid',
        ]);
    }
}
