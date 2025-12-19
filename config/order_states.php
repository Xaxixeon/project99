<?php

return [

    'transitions' => [
        'cs' => [
            'new'        => ['assigned'],
            'assigned'   => ['design'],
            'design'     => [],
            'production' => [],
            'qc'         => [],
            'ready'      => ['shipped'],
            'shipped'    => ['done'],
            'done'       => [],
        ],

        'designer' => [
            'assigned'   => ['design'],
            'design'     => ['production'],
            'production' => [],
            'qc'         => [],
            'ready'      => [],
            'shipped'    => [],
            'done'       => [],
        ],

        'production' => [
            'design'     => ['production'],
            'production' => ['qc'],
            'qc'         => [],
            'ready'      => [],
            'shipped'    => [],
            'done'       => [],
        ],

        'qc' => [
            'production' => ['qc'],
            'qc'         => ['ready'],
            'ready'      => [],
            'shipped'    => [],
            'done'       => [],
        ],

        'warehouse' => [
            'ready'    => ['shipped'],
            'shipped'  => ['done'],
            'done'     => [],
        ],

        'cashier' => [
            'ready'   => ['ready'],
            'shipped' => ['shipped'],
            'done'    => ['done'],
        ],

        'admin' => [
            'new'        => ['assigned', 'design', 'production', 'qc', 'ready', 'shipped', 'done', 'overdue'],
            'assigned'   => ['design', 'production', 'qc', 'ready', 'shipped', 'done', 'overdue'],
            'design'     => ['production', 'qc', 'ready', 'shipped', 'done', 'overdue'],
            'production' => ['qc', 'ready', 'shipped', 'done', 'overdue'],
            'qc'         => ['ready', 'shipped', 'done', 'overdue'],
            'ready'      => ['shipped', 'done', 'overdue'],
            'shipped'    => ['done', 'overdue'],
            'done'       => ['overdue'],
            'overdue'    => ['assigned', 'design', 'production', 'qc', 'ready', 'shipped', 'done'],
        ],
    ],

];
