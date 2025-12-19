<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\StaffUser;
use Illuminate\Http\Request;

class AdminSearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $results = [];

        $orders = Order::where('order_code', 'like', "%{$q}%")
            ->orWhere('id', $q)
            ->limit(5)
            ->get();

        foreach ($orders as $order) {
            $results[] = [
                'type'  => 'Order',
                'label' => 'Order: ' . ($order->order_code ?? $order->id),
                'url'   => route('orders.show', $order),
            ];
        }

        $customers = Customer::where('name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->limit(5)
            ->get();

        foreach ($customers as $customer) {
            $results[] = [
                'type'  => 'Customer',
                'label' => ($customer->name ?? '-') . ' (' . ($customer->email ?? '-') . ')',
                'url'   => route('admin.customers.show', $customer->id ?? 0),
            ];
        }

        $staffs = StaffUser::where('name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->limit(5)
            ->get();

        foreach ($staffs as $staff) {
            $results[] = [
                'type'  => 'Staff',
                'label' => ($staff->name ?? '-') . ' (' . ($staff->email ?? '-') . ')',
                'url'   => route('admin.staff.edit', $staff->id ?? 0),
            ];
        }

        return response()->json($results);
    }
}
