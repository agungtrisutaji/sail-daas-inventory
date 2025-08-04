<?php

return [
    'models' => [
        \App\Models\Staging::class => [
            'start_column' => 'staging_start',
            'finish_column' => 'staging_finish',
            'sla_column' => 'sla',
            'sla_threshold' => 2,
            'diff_method' => 'diffInDays',
        ],
        \App\Models\Deployment::class => [
            'start_relation' => 'unit.deliveries',
            'start_column' => 'actual_arrival_date',
            'finish_column' => 'bast_date',
            'sla_column' => 'sla',
            'sla_threshold' => 2,
            'diff_method' => 'diffInDays',
        ],
        \App\Models\Delivery::class => [
            'start_column' => 'delivery_date',
            'finish_column' => 'actual_arrival_date',
            'sla_column' => 'sla',
            'sla_threshold' => 2,
            'diff_method' => 'diffInDays',
        ],
    ],
];
