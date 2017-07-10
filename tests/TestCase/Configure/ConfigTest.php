<?php
namespace RabbitMQ\Test\TestCase\Configure;

use RabbitMQ\Configure\Config;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;

class ConfigTest extends TestCase
{
    public function testCakeCanReadConfig()
    {
        $this->assertInternalType('array', Configure::read('Riesenia.CakephpRabbitMQ'));
    }

    public function testGetServerConfig()
    {
        $server = Config::getServer();

        $this->assertTextEquals(5672, $server['port']);
        $this->assertTextEquals('guest', $server['user']);
        $this->assertTextEquals('guest', $server['user']);
        $this->assertTextEquals('/', $server['vhost']);
    }

    public function testDefaultNameGeneration()
    {
        $config = Config::get('min');

        $this->assertTextEquals('min_exchange', $config['exchange']['name']);
        $this->assertTextEquals('min_queue', $config['queue']['name']);
        $this->assertTextEquals('min_routing_key', $config['routing_key']);

        $this->assertTextEquals('min_retry_exchange', $config['retry_exchange']['name']);
        $this->assertTextEquals('min_retry_queue', $config['retry_queue']['name']);
        $this->assertTextEquals('min_retry_routing_key', $config['retry_routing_key']);
    }

    public function testNoRetryBindingRouteGeneration()
    {
        $config = Config::get('min');

        $queueArgs = $config['queue']['arguments'];
        $this->assertTextEquals(false, isset($queueArgs['x-dead-letter-exchange']));
        $this->assertTextEquals(false, isset($queueArgs['x-dead-letter-routing-key']));

        $retryQueueArgs = $config['retry_queue']['arguments'];
        $this->assertTextEquals(false, isset($retryQueueArgs['x-message-ttl']));
        $this->assertTextEquals(false, isset($retryQueueArgs['x-dead-letter-exchange']));
        $this->assertTextEquals(false, isset($retryQueueArgs['x-dead-letter-routing-key']));
    }

    public function testRetryBindingRouteGeneration()
    {
        $config = Config::get('retry_15s_with_max_3_times');
        
        $queueArgs = $config['queue']['arguments'];
        $this->assertTextEquals([ 'S', 'retry_15s_with_max_3_times_retry_exchange' ], $queueArgs['x-dead-letter-exchange']);
        $this->assertTextEquals([ 'S', 'retry_15s_with_max_3_times_retry_routing_key' ], $queueArgs['x-dead-letter-routing-key']);

        $retryQueueArgs = $config['retry_queue']['arguments'];
        $this->assertTextEquals([ 'I', 15 * 1000 ], $retryQueueArgs['x-message-ttl']);
        $this->assertTextEquals([ 'S', 'retry_15s_with_max_3_times_exchange' ], $retryQueueArgs['x-dead-letter-exchange']);
        $this->assertTextEquals([ 'S', 'retry_15s_with_max_3_times_routing_key' ], $retryQueueArgs['x-dead-letter-routing-key']);
    }

    public function testOverrideDefaultConfig()
    {
        $config = Config::get('custom_setting');

        $this->assertTextEquals('ex', $config['exchange']['name']);
        $this->assertTextEquals('q', $config['queue']['name']);
        $this->assertTextEquals('rk', $config['routing_key']);

        $this->assertTextEquals('re_ex', $config['retry_exchange']['name']);
        $this->assertTextEquals('re_q', $config['retry_queue']['name']);
        $this->assertTextEquals('re_rk', $config['retry_routing_key']);
    }

    public function testOverridedRetryBindingRouteGeneration()
    {
        $config = Config::get('custom_setting');

        $queueArgs = $config['queue']['arguments'];
        $this->assertTextEquals([ 'S', 're_ex' ], $queueArgs['x-dead-letter-exchange']);
        $this->assertTextEquals([ 'S', 're_rk' ], $queueArgs['x-dead-letter-routing-key']);

        $retryQueueArgs = $config['retry_queue']['arguments'];
        $this->assertTextEquals([ 'S', 'ex' ], $retryQueueArgs['x-dead-letter-exchange']);
        $this->assertTextEquals([ 'S', 'rk' ], $retryQueueArgs['x-dead-letter-routing-key']);
    }

    public function testGetNotExistKey()
    {
        $this->expectException(\InvalidArgumentException::class);
        Config::get('NotExist');
    }

    public function testGetServerConfigWithGetFunction()
    {
        $this->expectException(\InvalidArgumentException::class);
        Config::get('server');
    }
}
