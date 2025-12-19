@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold mb-1">Kode Customer</label>
        <input type="text" name="customer_code" value="{{ old('customer_code', $customer->customer_code ?? '') }}"
            class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Opsional, bisa otomatis">
    </div>

    <div>
        <label class="block text-sm font-semibold mb-1">Nama</label>
        <input type="text" name="name" value="{{ old('name', $customer->name ?? '') }}" required
            class="w-full border rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-sm font-semibold mb-1">No HP</label>
        <input type="text" name="phone" value="{{ old('phone', $customer->phone ?? '') }}" required
            class="w-full border rounded-lg px-3 py-2 text-sm">
    </div>

    <div>
        <label class="block text-sm font-semibold mb-1">Email (opsional)</label>
        <input type="email" name="email" value="{{ old('email', $customer->email ?? '') }}"
            class="w-full border rounded-lg px-3 py-2 text-sm">
    </div>
</div>

<div>
    <label class="block text-sm font-semibold mb-1">Alamat (opsional)</label>
    <textarea name="address" class="w-full border rounded-lg px-3 py-2 text-sm" rows="2">{{ old('address', $customer->address ?? '') }}</textarea>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold mb-1">Instansi</label>
        <select name="instansi_id" class="w-full border rounded-lg px-3 py-2 text-sm">
            <option value="">Tidak terhubung</option>
            @foreach ($instansi as $ins)
                <option value="{{ $ins->id }}" @selected(old('instansi_id', $customer->instansi_id ?? null) == $ins->id)>
                    {{ $ins->name }}
                </option>
            @endforeach
        </select>
        <p class="text-xs text-gray-500 mt-1">
            Beberapa customer bisa tergabung dalam satu instansi (misalnya satu kantor).
        </p>
    </div>

    <div>
        <label class="block text-sm font-semibold mb-1">Jenis Member</label>
        <select name="member_type_id" class="w-full border rounded-lg px-3 py-2 text-sm">
            <option value="">Default (Retail)</option>
            @foreach ($memberTypes as $mt)
                <option value="{{ $mt->id }}" @selected(old('member_type_id', $customer->member_type_id ?? null) == $mt->id)>
                    {{ $mt->name ?? $mt->label ?? strtoupper($mt->code) }} (Diskon {{ $mt->discount_percent ?? $mt->default_discount }}%)
                </option>
            @endforeach
        </select>
    </div>
</div>

@if (!isset($customer))
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t pt-4 mt-4">
        <div>
            <label class="block text-sm font-semibold mb-1">Password Akun Online (opsional)</label>
            <input type="password" name="password" class="w-full border rounded-lg px-3 py-2 text-sm"
                placeholder="Jika diisi, customer bisa login untuk cek order">
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold mb-1">Status</label>
        <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm">
            <option value="active" @selected(($customer->status ?? 'active') === 'active')>Aktif</option>
            <option value="inactive" @selected(($customer->status ?? 'active') === 'inactive')>Nonaktif</option>
        </select>
    </div>
</div>
