<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['order.customer'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['order.customer', 'order.items.product']);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Generate invoice dari order (admin/cashier)
     */
    public function createFromOrder(Order $order)
    {
        if ($order->invoice) {
            return redirect()
                ->route('invoices.show', $order->invoice)
                ->with('info', 'Invoice sudah ada untuk order ini.');
        }

        $invoiceNo = $this->generateInvoiceNo();

        $invoice = Invoice::create([
            'order_id'   => $order->id,
            'invoice_no' => $invoiceNo,
            'amount'     => $order->total_price ?? (int) $order->total,
            'status'     => Invoice::STATUS_UNPAID,
        ]);

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Invoice berhasil dibuat.');
    }

    public function markPaid(Invoice $invoice)
    {
        $invoice->status = Invoice::STATUS_PAID;
        $invoice->save();

        if ($invoice->order && $invoice->order->status !== 'completed') {
            $invoice->order->status = 'completed';
            $invoice->order->save();
        }

        return back()->with('success', 'Invoice ditandai sebagai LUNAS.');
    }

    public function markUnpaid(Invoice $invoice)
    {
        $invoice->status = Invoice::STATUS_UNPAID;
        $invoice->save();

        return back()->with('success', 'Invoice dikembalikan ke status UNPAID.');
    }

    protected function generateInvoiceNo(): string
    {
        $prefix = 'INV-' . now()->format('Ymd') . '-';
        $random = strtoupper(Str::random(4));

        return $prefix . $random;
    }
}
