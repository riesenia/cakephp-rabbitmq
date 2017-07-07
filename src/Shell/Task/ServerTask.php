<?php

namespace RabbitMQ\Shell\Task;

use RabbitMQ\CakephpRabbitMQ;

use Cake\Console\Shell;
use Cake\Core\Configure;

class ServerTask extends Shell
{
    /**
     * Listen queues according to the arguments provided
     *
     * @return void
     */
    public function main()
    {
        $this->out('<info>[*] Starting to listen messages. Press CTRL+C to exit</info>');
        $this->out();
        CakephpRabbitMQ::listen($this->args);
    }
}
