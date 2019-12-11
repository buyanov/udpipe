<?php
namespace App\Console\Command;

use App\Console\Command;

class StopCommand extends Command
{
    protected $name = 'server:stop';

    protected $description = 'description';

    protected $help = '';

    protected $options = [];


    public function handle()
    {
        $pidFile = getcwd() . '/daemon.pid';
        if (file_exists($pidFile))
        {
            $pid = file_get_contents(getcwd() . '/daemon.pid');
            posix_kill($pid, SIGINT);
            unlink($pidFile);
        }
    }

}