@props(['status'])

@php
    $map = [
        'pending' => ['gray', 'Pending'],
        'assigned' => ['blue', 'Assigned'],
        'designing' => ['yellow', 'Designing'],
        'design_done' => ['green', 'Design Done'],
        'production' => ['purple', 'Production'],
        'printing' => ['blue', 'Printing'],
        'finishing' => ['yellow', 'Finishing'],
        'ready' => ['green', 'Ready for Payment'],
        'paid' => ['green', 'Paid'],
        'packing' => ['purple', 'Packing'],
        'shipping' => ['blue', 'Shipping'],
        'completed' => ['green', 'Completed'],
    ];

    [$color, $label] = $map[$status] ?? ['gray', ucfirst($status)];
@endphp

<x-badge :color="$color">{{ $label }}</x-badge>
