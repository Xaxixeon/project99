<?php

return [
    'on_status' => [
        'assigned' => [
            \App\Automation\AutoAssignDesigner::class,
        ],
        'design' => [
            \App\Automation\AutoNotifyDesigner::class,
        ],
        'production' => [
            \App\Automation\AutoReserveMaterial::class,
            \App\Automation\AutoNotifyProductionLead::class,
            \App\Automation\ClearOverdueIfReturned::class,
        ],
        'ready' => [
            \App\Automation\NotifyCustomerReady::class,
        ],
        'design' => [
            \App\Automation\ClearOverdueIfReturned::class,
        ],
    ],
];
