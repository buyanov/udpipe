<?php
namespace App\Console\Command;

use App\Console\Command;
use App\Service\Server;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Process;

class ServeCommand extends Command
{
    protected $name = 'serve';

    protected $description = 'description';

    protected $help = '';

    private $server;

    protected $options = [
    ];

    public function handle()
    {
        $process = new Process(['/usr/bin/php', '/Users/buyanov/Work/udpipe/bin/udpipe', 'server:start'], null, ['APP_ENV' => 'prod']);

        $process->start();

        $process->wait(function ($type, $buffer) {
            if (Process::ERR === $type) {
                echo 'ERR > '.$buffer;
            } else {
                echo 'OUT > '.$buffer;
            }
        });
    }

}