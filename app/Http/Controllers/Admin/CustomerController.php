<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Instansi;
use App\Models\MemberType;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with(['instansi', 'memberType']);

        if ($request->q) {
            $q = '%' . $request->q . '%';
            $query->where('name', 'like', $q)
                ->orWhere('phone', 'like', $q)
                ->orWhere('email', 'like', $q);
        }

        if ($request->member_type_id) {
            $query->where('member_type_id', $request->member_type_id);
        }

        if ($request->instansi_id) {
            $query->where('instansi_id', $request->instansi_id);
        }

        return view('admin.customers.index', [
            'customers'   => $query->paginate(20),
            'instansi'    => Instansi::all(),
            'memberTypes' => MemberType::orderBy('name')->get(),
        ]);
    }


    public function create()
    {
        return view('admin.customers.create', [
            'instansi'    => Instansi::all(),
            'memberTypes' => MemberType::orderBy('name')->get(),
        ]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_code' => 'nullable|string',
            'name'          => 'required|string|max:255',
            'phone'         => 'nullable|string|max:50',
            'email'         => 'required|email|unique:customers,email',
            'address'       => 'nullable|string',
            'instansi_id'   => 'nullable|exists:instansis,id',
            'member_type_id' => 'nullable|exists:member_types,id',
            'password'      => 'nullable|confirmed|min:4',
            'status'        => 'required|string',
        ]);

        $memberType = !empty($data['member_type_id'])
            ? MemberType::find($data['member_type_id'])
            : null;
        $data['member_type'] = $memberType->code ?? 'retail';

        if ($data['password'] ?? null) {
            $data['password'] = Hash::make($data['password']);
        }

        Customer::create($data);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer berhasil ditambahkan.');
    }


    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', [
            'customer'    => Customer::with(['memberType', 'specialPrices.product'])->findOrFail($customer->id),
            'instansi'    => Instansi::all(),
            'memberTypes' => MemberType::orderBy('name')->get(),
            'products'    => Product::orderBy('name')->get(),
        ]);
    }


    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'customer_code' => 'nullable|string',
            'name'          => 'required|string|max:255',
            'phone'         => 'nullable|string|max:50',
            'email'         => 'required|email|unique:customers,email,' . $customer->id,
            'address'       => 'nullable|string',
            'instansi_id'   => 'nullable|exists:instansis,id',
            'member_type_id' => 'nullable|exists:member_types,id',
            'status'        => 'required|string',
            'special'        => 'array',
            'special.*.price_per_m2' => 'nullable|integer|min:0',
            'special.*.flat_price'   => 'nullable|integer|min:0',
        ]);

        $memberType = !empty($data['member_type_id'])
            ? MemberType::find($data['member_type_id'])
            : null;
        $data['member_type'] = $memberType->code ?? $customer->member_type;

        $customer->update($data);

        if ($request->has('special')) {
            foreach ($request->special as $productId => $sp) {
                $pricePerM2 = $sp['price_per_m2'] ?? null;
                $flat       = $sp['flat_price'] ?? null;

                $existing = $customer->specialPrices()
                    ->where('product_id', $productId)
                    ->first();

                if (!$pricePerM2 && !$flat) {
                    if ($existing) {
                        $existing->delete();
                    }
                    continue;
                }

                $customer->specialPrices()->updateOrCreate(
                    ['product_id' => $productId],
                    [
                        'price_per_m2' => $pricePerM2 ?: null,
                        'flat_price'   => $flat ?: null,
                    ]
                );
            }
        }

        return back()->with('success', 'Customer berhasil diperbarui.');
    }


    public function toggle($id)
    {
        $customer = Customer::findOrFail($id);

        $customer->status = $customer->status === 'active' ? 'inactive' : 'active';
        $customer->save();

        return back()->with('success', 'Status customer diperbarui.');
    }
}
