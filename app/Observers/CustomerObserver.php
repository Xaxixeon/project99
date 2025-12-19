<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\CustomerActivityLog;

class CustomerObserver
{
    public function updating(Customer $customer): void
    {
        $original = $customer->getOriginal();
        $changes  = $customer->getDirty();

        $loggable = ['name', 'email', 'phone', 'company', 'address'];

        $before = [];
        $after  = [];

        foreach ($loggable as $field) {
            if (!array_key_exists($field, $changes)) {
                continue;
            }
            $before[$field] = $original[$field] ?? null;
            $after[$field]  = $changes[$field];
        }

        if (!empty($before) || !empty($after)) {
            CustomerActivityLog::create([
                'customer_id'     => $customer->id,
                'staff_id'        => auth('staff')->id() ?? null,
                'action'          => 'updated',
                'before_payload'  => $before,
                'after_payload'   => $after,
            ]);
        }
    }

    public function deleted(Customer $customer): void
    {
        CustomerActivityLog::create([
            'customer_id' => $customer->id,
            'staff_id'    => auth('staff')->id() ?? null,
            'action'      => 'deleted',
        ]);
    }

    public function restored(Customer $customer): void
    {
        CustomerActivityLog::create([
            'customer_id' => $customer->id,
            'staff_id'    => auth('staff')->id() ?? null,
            'action'      => 'restored',
        ]);
    }
}
