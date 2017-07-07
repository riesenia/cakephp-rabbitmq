<?php
namespace RabbitMQ\Shell;

use RabbitMQ\CakephpRabbitMQ;

use Cake\Console\Shell;

class RabbitmqShell extends Shell
{
    /**
     * Tasks to load and instantiate
     *
     * @var array
     */
    public $tasks = ['RabbitMQ.Server'];

    /**
     * Return option parser instance
     *
     * @return Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        $parser->description('Cakephp-RabbitMQ shell')
            ->addSubcommands(
                [
                'server' => [
                    'help' => 'Start the server',
                    'parser' => $this->Server->getOptionParser()
                ]
                ]
            );

        return $parser;
    }

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
     * Main method
     *
     * @return bool|int success or error code
     */
    public function main()
    {
        $this->out();
        $this->out('For usage and list of available commands use <info>`cake server --help`</info>');
        $this->out();

        return true;
    }
}
