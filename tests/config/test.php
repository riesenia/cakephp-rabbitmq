<?php

return [
    'Riesenia.CakephpRabbitMQ' => [
        'server' => [
            'host' => 'rabbitmq'
        ],
        'min' => [
            'command' => 'bin/cake test',
            'retry' => false
        ],
        'retry_15s_with_max_3_times' => [
            'command' => 'bin/cake test',
            'retry_time' => 15 * 1000,
            'retry_max' => 3
        ],
        'custom_setting' => [
            'queue' => [
                'name' => 'q'
            ],
            'exchange' => [
                'name' => 'ex'
            ],
            'routing_key' => 'rk',
            'retry_queue' => [
                'name' => 're_q'
            ],
            'retry_exchange' => [
                'name' => 're_ex'
            ],
            'retry_routing_key' => 're_rk'
        ],
        'invalid_callback' => [
            'callback' => 'invalid'
        ],
        'too_many_callback' => [
            'command' => 'invalid',
            'cake_command' => 'invalid'
        ]
    ]
];
