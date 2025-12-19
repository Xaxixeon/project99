<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderStatusLog;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TakingOrderController extends Controller
{
    /**
     * Form create purchase order (staff/admin).
     */
    public function create()
    {
        return view('orders.create', [
            'customers' => Customer::orderBy('name')->get(),
        ]);
    }

    /**
     * Store purchase order + auto task + status log.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'  => 'nullable|exists:customers,id',
            'customer_name'=> 'nullable|string|max:255',
            'customer_phone'=> 'nullable|string|max:50',
            'product_type' => 'required|string|max:255',
            'size'         => 'nullable|string|max:255',
            'material'     => 'nullable|string|max:255',
            'quantity'     => 'required|integer|min:1',
            'finishing'    => 'nullable|string|max:255',
            'need_design'  => 'boolean',
            'deadline'     => 'nullable|date',
            'notes'        => 'nullable|string',
        ]);

        $needDesign = $request->boolean('need_design');
        $status     = $needDesign ? 'designing' : 'ready_to_print';

        DB::beginTransaction();

        // Ensure customer exists if only name/phone provided
        $customerId = $data['customer_id'] ?? null;
        if (!$customerId && (!empty($data['customer_name']) || !empty($data['customer_phone']))) {
            $customer = Customer::create([
                'name'    => $data['customer_name'] ?? 'Customer',
                'phone'   => $data['customer_phone'] ?? null,
                'email'   => null,
                'address' => null,
            ]);
            $customerId = $customer->id;
        }

        /** @var \App\Models\Order $order */
        $order = Order::create([
            'order_code'   => 'ORD-' . strtoupper(Str::random(8)),
            'customer_id'  => $customerId,
            'product_type' => $data['product_type'],
            'size'         => $data['size'] ?? null,
            'material'     => $data['material'] ?? null,
            'quantity'     => $data['quantity'],
            'finishing'    => $data['finishing'] ?? null,
            'need_design'  => $needDesign,
            'deadline'     => $data['deadline'] ?? null,
            'notes'        => $data['notes'] ?? null,
            'order_status' => $status,
            'created_by'   => Auth::id(),
            'status'       => $status, // keep backward compatibility
            'subtotal'     => 0,
            'shipping'     => 0,
            'discount'     => 0,
            'total'        => 0,
        ]);

        // Log status
        $this->logStatus($order, null, $status);

        // Auto create task
        if ($needDesign) {
            Task::create([
                'order_id'   => $order->id,
                'role'       => 'designer',
                'task_type'  => 'create_design',
                'status'     => 'pending',
                'description'=> 'Buat desain sesuai brief.',
            ]);
        } else {
            Task::create([
                'order_id'   => $order->id,
                'role'       => 'operator',
                'task_type'  => 'print_job',
                'status'     => 'pending',
                'description'=> 'Siapkan file & mulai produksi.',
            ]);
        }

        DB::commit();

        return redirect()->route('orders.index')
            ->with('success', 'Order berhasil dibuat.');
    }

    protected function logStatus(Order $order, $prev, $new)
    {
        OrderStatusLog::create([
            'order_id'        => $order->id,
            'previous_status' => $prev,
            'new_status'      => $new,
            'changed_by'      => Auth::id(),
        ]);
    }
}
