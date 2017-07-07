# Complete Configuration keys for RabbitMQ

This document state all available configuration keys and their defatult values.

## 



## server

Please visit [RabbitMQ documentation](https://www.rabbitmq.com/amqp-0-9-1-reference.html) and [php-amqplib](https://github.com/php-amqplib/php-amqplib) for more detail on each configuration key.

```php
/**
* RabbitMQ server configuration
*/
'server' => [
    'host' => 'localhsot',
    'port' => 5672,
    'user' => 'guest',
    'password' => 'guest',
    'vhost' => '/',
    'insist' => false,
    'login_method' => 'AMQPLAIN',
    'login_response' => null,
    'locale' => 'en_US',
    'connection_timeout' => 3.0,
    'read_write_timeout' => 3.0,
    'context' => null,
    'keepalive' => false,
    'heartbeat' => 0
 ];
```

## queue

Please visit [RabbitMQ documentation](https://www.rabbitmq.com/amqp-0-9-1-reference.html) for the meaning of each configuration key.

```php
/**
* Queue configuration
*/
'<alias>' => [
    // Main queue
    'exchange' => [
        'name' => '<alias>_exchange',
        'type' => 'direct',
        'passive' => false,
        'durable' => false,
        'auto-delete' => false,
        'internal' => false,
        'no-wait' => false,
        'arguments' => []
    ],
    'queue' => [
        'name' => '<alias>_queue',
        'passive' => false,
        'durable' => true,
        'exclusive' => false,
        'auto-delete' => false,
        'no-wait' => false,
        'arguments' => []
    ],
    'routing_key' => '<alias>_routing_key',
    
    // Retry setting
    'retry' => true,
    'retry_time' => 5 * 60 * 1000, // 5 mins
    'retry_max' => 5,

    // Retry queue
    'retry_exchange' => [
        'name' => '<alias>_retry_exchange',
        'type' => 'direct',
        'passive' => false,
        'durable' => false,
        'auto-delete' => false,
        'internal' => false,
        'no-wait' => false,
        'arguments' => []
    ],
    'retry_queue' => [
        'name' => '<alias>_retry_queue',
        'passive' => false,
        'durable' => true,
        'exclusive' => false,
        'auto-delete' => false,
        'no-wait' => false,
        'arguments' => []
    ],
    'retry_routing_key' => '<alias>_retry_routing_key',

    // Basic qos
    'basic_qos' => [
        'prefetch-size' => null,
        'prefetch-count' => 1,
        'global' => null
    ],

    // Basic consume
    'basic_consume' => [
        'consumer-tag' => '',
        'no-local' => false,
        'no-ack' => false,
        'exclusive' => false,
        'no-wait' => false
    ]
]
```