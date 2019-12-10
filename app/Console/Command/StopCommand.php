<?php
namespace App\Console\Command;

use App\Console\Command;
use App\Service\Server;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputArgument;

class StopCommand extends Command
{
    use LockableTrait;

    protected $name = 'server:stop';

    protected $description = 'description';

    protected $help = '';

    private $server;

    protected $options = [];


    public function handle()
    {
        $pid = file_get_contents(getcwd() . '/daemon.pid');

        posix_kill($pid, SIGINT);
    }

}