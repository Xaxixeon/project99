<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderWorkflowController extends Controller
{
    private function log($order, $message)
    {
        OrderLog::create([
            'order_id' => $order->id,
            'user_id'  => Auth::id(),
            'message'  => $message
        ]);
    }

    // ============================
    // CUSTOMER SERVICE
    // ============================

    public function assignDesigner(Order $order)
    {
        $order->update([
            'status' => 'assigned',
            'designer_id' => request('designer_id') ?? null
        ]);

        $this->log($order, "Order di-assign ke designer");

        return back()->with('success', 'Order berhasil di-assign');
    }

    // ============================
    // DESIGNER WORKFLOW
    // ============================

    public function startDesign(Order $order)
    {
        $order->update([
            'status' => 'designing',
        ]);

        $this->log($order, "Designer mulai mengerjakan desain");
        return back()->with('success', 'Desain dimulai');
    }

    public function finishDesign(Order $order)
    {
        $order->update([
            'status' => 'design_done',
        ]);

        $this->log($order, "Desain selesai dikerjakan");
        return back()->with('success', 'Desain selesai');
    }

    // ============================
    // PRODUCTION WORKFLOW
    // ============================

    public function startProduction(Order $order)
    {
        $order->update([
            'status' => 'production',
        ]);

        $this->log($order, "Produksi dimulai");
        return back()->with('success', 'Produksi dimulai');
    }

    public function print(Order $order)
    {
        $order->update([
            'status' => 'printing',
        ]);

        $this->log($order, "Proses print berjalan");
        return back()->with('success', 'Sedang printing');
    }

    public function finishJob(Order $order)
    {
        $order->update([
            'status' => 'finishing',
        ]);

        $this->log($order, "Finishing berlangsung");
        return back()->with('success', 'Finishing');
    }

    // ============================
    // CASHIER & PACKING
    // ============================

    public function markReady(Order $order)
    {
        $order->update([
            'status' => 'ready'
        ]);

        $this->log($order, "Order selesai dan siap dibayar");
        return back()->with('success', 'Order siap dibayar');
    }

    public function pay(Order $order)
    {
        $order->update([
            'status' => 'paid'
        ]);

        $this->log($order, "Pembayaran diterima");
        return back()->with('success', 'Pembayaran diterima');
    }

    // ============================
    // SHIPPING
    // ============================

    public function pack(Order $order)
    {
        $order->update([
            'status' => 'packing'
        ]);

        $this->log($order, "Order sedang dikemas");
        return back()->with('success', 'Sedang packing');
    }

    public function ship(Order $order)
    {
        $order->update([
            'status' => 'shipping'
        ]);

        $this->log($order, "Order dikirim");
        return back()->with('success', 'Order dikirim');
    }

    // ============================
    // COMPLETE
    // ============================

    public function complete(Order $order)
    {
        $order->update([
            'status' => 'completed'
        ]);

        $this->log($order, "Order selesai");
        return back()->with('success', 'Order selesai');
    }
}
