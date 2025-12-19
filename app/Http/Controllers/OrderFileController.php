<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderFile;
use Illuminate\Http\Request;

class OrderFileController extends Controller
{
    public function storeForCustomer(Request $request, Order $order)
    {
        $request->validate([
            'file' => 'required|file|max:30720',
        ]);

        $customer = auth('customer')->user();

        $path = $request->file('file')->store('order_files');

        OrderFile::create([
            'order_id'                => $order->id,
            'uploaded_by_customer_id' => $customer->id,
            'file_path'               => $path,
            'file_original_name'      => $request->file('file')->getClientOriginalName(),
            'type'                    => 'customer_upload',
        ]);

        return back()->with('success', 'File berhasil diupload.');
    }

    public function storeForStaff(Request $request, Order $order)
    {
        $request->validate([
            'file' => 'required|file|max:30720',
            'type' => 'required|in:designer_output,revision',
        ]);

        $staff = auth('staff')->user();

        $path = $request->file('file')->store('order_files');

        OrderFile::create([
            'order_id'               => $order->id,
            'uploaded_by_staff_id'   => $staff->id,
            'file_path'              => $path,
            'file_original_name'     => $request->file('file')->getClientOriginalName(),
            'type'                   => $request->type,
        ]);

        return back()->with('success', 'File berhasil diupload.');
    }

    public function approve(OrderFile $file)
    {
        $file->approved = true;
        $file->save();

        return back()->with('success', 'File disetujui.');
    }
}
