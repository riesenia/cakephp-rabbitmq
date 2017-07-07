<?php

namespace RabbitMQ;

use RabbitMQ\Configure\Config;
use RabbitMQ\Connection\RabbitMQ;
use RabbitMQ\Helper\ColorfulConsole;
use RabbitMQ\Helper\MeaningfulTime;

/**
 * External API
 */
class CakephpRabbitMQ
{
    /**
     * Read the configs according to the key provided and listen to them,
     * listen all if not key is specified
     *
     * @param array $keys
     * @return void
     */
    public static function listen(array $keys = [])
    {
        $server = Config::getServer();
        if (empty($keys)) {
            $configs = Config::getAllConfigs();
        } else {
            $configs = Config::getConfigs($keys);
        }

        foreach ($configs as $key => $config) {
            if (empty($keys) || in_array($key, $keys)) {
                $configs[$key]['_callback'] = static::_callback($key, $config);
            } else {
                unset($configs[$key]);
            }
        }

        RabbitMQ::listen($server, $configs);
    }

    /**
     * Generate internal callback function for queue
     *
     * @param string $key
     * @param array $config
     * @return function($message)
     */
    protected static function _callback(string $key, array $config)
    {
        // Generate the callback according to the callback type provided
        $callback = NULL;
        
        // Callable
        if (!empty($config['callback'])) {
            $callback = $config['callback'];
        // Command
        } else if (!empty($config['command'])) {
            $command = $config['command'];
            $callback = function($message) use ($command) {
                exec($command . ' ' . $message->body, $output, $result);
                return $result;
            };
        // Cakephp command
        } else if (!empty($config['cake_command'])) {
            $cakeCommand = $config['cake_command'];
            $callback = function($message) use ($cakeCommand) {
                exec('bin' . DS . 'cake ' . $cakeCommand . ' ' . $message->body, $output, $result);
                return $result;
            };
        }
        $retryMax = $config['retry_max'];
        $m = new MeaningfulTime();
        $retryTime = $m($config['retry_time'], 'ms');

        // Internal callback function
        return function($message) use ($key, $callback, $retryMax, $retryTime) {
            $c = new ColorfulConsole();
            $c('default', sprintf("[*] Queue '%s' received message : '%s'", $key, $message->body));
            $result = call_user_func_array($callback, [$message]);
            
            try {
                $headers = $message->get('application_headers');
                $xDeath = $headers->getNativeData()['x-death'];
                $retryCount = $xDeath[1]['count'];
            // The message would not have the header at first time
            } catch (\OutOfBoundsException $e) {
                $retryCount = 0;
            }

            // On failed
            if ($result != 0) {
                // Retry
                if ($retryCount < $retryMax) {
                    $c('warning', sprintf("[!] Failed to process the message, retry after %s (retried %d times)", $retryTime, $retryCount));
                // Exceeded maximum retry
                } else {
                    $c('error', sprintf("[X] Failed to process the message and exceeded maximum retry count of %d, dropping the message", $retryMax));
                    // Drop the message by sending ack
                    $result = 0;
                }
            // On success
            } else {
                $c('success', sprintf("[√] Success to process the message"));
            }

            echo "\n";

            if ($result != 0) {
                // Redirect to the retry queue if non-zero value returned
                $message->delivery_info['channel']->basic_reject($message->delivery_info['delivery_tag'], false);
            } else {
                $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
            }
        };
    }

    /**
     * Send message to queue specified by key 
     *
     * @param string $key
     * @param string $message
     * @return void
     */
    public static function send(string $key, string $message)
    {
        $server = Config::getServer();
        $config = Config::get($key);

        RabbitMQ::send($server, $config, $message);
    }
}