<?php
namespace RabbitMQ\Shell;

use RabbitMQ\CakephpRabbitMQ;
use Cake\Console\Shell;

class RabbitmqShell extends Shell
{
    /**
     * Displays a header for the shell
     *
     * @return void
     */
    protected function _welcome()
    {
        $this->out();
        $this->out('<info>Welcome to Cakephp-RabbitMQ Server Shell</info>');
        $this->hr();
    }

    /**
     * Listen to queues
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
