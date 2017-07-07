# RabbitMQ for CakePHP

[![Build Status](https://travis-ci.org/chanpete/cakephp-rabbitmq.svg?branch=master)](https://travis-ci.org/chanpete/cakephp-rabbitmq)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

This plugin is for CakePHP 3.x that send and recevie messages by use of [RabbitMQ](https://www.rabbitmq.com/). 

## Installation

Using composer

```bash
composer require riesenia/cakephp-rabbitmq
```

## Bootstrap

Add the following to your `config/bootstrap.php` to load the plugin.

```php
Plugin::load('RabbitMQ');
```

## Configuration

Example configurations:

```php
    /**
     * Example configuration
     */
    'Riesenia.CakephpRabbitMQ' => [
        'server' => [
            'host' => '127.0.0.1',
            'port' => 5672,
            'user' => 'guest',
            'password' => 'guest'
        ],
        'email' => [
            'cake_command' => 'email send',
            'retry_time' => 15 * 60 * 1000,
            'retry_max' => 3
        ],
    ],
```

Put the configuration within the namespace `Riesenia.CakephpRabbitMQ`.

It's an associative array where every key is the alias for a specific queue configuration (except for `server` which state the configurations to connect to the server).

That is `email` in our example. So when you need to send a message to the email queue, you just call `CakephpRabbitMQ::send('email', 'this is a message');`.

### Basic Configuration keys

Below are just the basic configuration keys, for complete configuration keys, please look at [Complete Configuration](#complete-configuration-keys).

#### server

- `host` *(string)* - url to connect to the RabbitMQ server
- `port` *(int)* - port to connect to the RabbitMQ server
- `user` *(string)* - username to connect to the RabbitMQ server
- `password` *(string)* - password to connect to the RabbitMQ server

#### queue

- `retry` *(bool)* - retry on callback return failed
- `retry_time` *(int)* - retry period (in ms)
- `retry_max` *(int)* - maximum retry times
- `cake_command` *(string)* - on of the callback type, please look at below section for most details. 

## Callback

There is three type of callback available: `callback`, `command`, `cake_command`.

**Notice: You must specify one and only one callback!**

### cake_command
*(string)*

It will execute a cake command when the queue recevied a message. For example,
```php
        'cake_command' => 'email send'
```
will execute `bin/cake email send <message>` when recevied a message.

### command
*(string)*

It will execute a bash command when the queue recevied a message. For example,
```php
        'command' => 'rm -rf'
```
will execute `rm -rf <message>` when recevied a message.

### callback
*(callable)*

It will call the callback function when the queue recevied a message. For example,
```php
        'callback' => [new App/Shell/Mailer() ,'sendEmail']
```
will call the `sendEmail($message)` function when recevied a message.

**Notice: using `callback` will recevie the raw AMQPMessage**

The message you send is inside `$message->body`.

For more detail on PHP callable, please visit [PHP callable documentation](http://php.net/manual/en/language.types.callable.php)

## Usage

### Server

RabbitMQ comes with a built-in shell that listen to the queue and forward the message to the callback specified in the configuration.

To start the server, just run:
```bash
bin/cake rabbitmq server
```

To listen only certain queue, pass their alias as arguments to commad:
```bash
bin/cake rabbitmq server email sms
```

### Callback return

If retry is enable, the **callback must retrun a status code** to indicate whether the process of the message is successful or not. 

**Return 0 mean successful and all the other number means failed.**

### Send

To send a message to a queue is easy, just run:
```php
CakephpRabbitMQ::send('email', 'this is a message');
```

### Listen

If you want to start the server inside your shell, you can call:
```php
CakephpRabbitMQ::listen();
```
It will listen all queues stated in the configuration.

---

If you want to listen a subset of queues, pass a array with queue alias as argument:
```php
CakephpRabbitMQ::listen(['email']);
```

# Complete Configuration keys

## server

Please visit [RabbitMQ documentation](https://www.rabbitmq.com/amqp-0-9-1-reference.html) and [php-amqplib](https://github.com/php-amqplib/php-amqplib) for more detail on each configuration key.

```php
/**
* RabbitMQ server configuration
*/
'server' => [
    'host' => 'localhost',
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
