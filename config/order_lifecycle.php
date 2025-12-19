<?php

return [
    'lifecycle_by_status' => [
        'new'        => 'created',
        'assigned'   => 'in_progress',
        'design'     => 'in_progress',
        'production' => 'in_progress',
        'qc'         => 'review',
        'ready'      => 'ready_for_delivery',
        'shipped'    => 'delivering',
        'done'       => 'completed',
        'overdue'    => 'overdue',
    ],
];
