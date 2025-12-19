<?php

namespace App\Http\Controllers\Workflow;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderFileController extends Controller
{
    public function upload(Request $request, Order $order)
    {
        $request->validate([
            'file' => 'required|file|max:50000',
            'type' => 'required|string',
        ]);

        $file = $request->file('file');
        $stored = $file->store('order_files/' . $order->id);

        OrderFile::create([
            'order_id' => $order->id,
            'file_path' => $stored,
            'file_original_name' => $file->getClientOriginalName(),
            'type' => $request->type,
            'uploaded_by_staff_id' => Auth::guard('staff')->id(),
            'uploaded_by_customer_id' => Auth::guard('customer')->id(),
        ]);

        return back()->with('success', 'File uploaded.');
    }

    public function approve(OrderFile $file)
    {
        $file->approved = true;
        $file->save();

        return back()->with('success', 'File approved.');
    }

    public function reject(OrderFile $file)
    {
        $file->approved = false;
        $file->save();

        return back()->with('success', 'File rejected.');
    }
}
