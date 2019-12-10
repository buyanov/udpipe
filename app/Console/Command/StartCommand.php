<?php
namespace App\Console\Command;

use App\Console\Command;
use App\Service\Server;
use Symfony\Component\Console\Input\InputArgument;

class StartCommand extends Command
{
    protected $name = 'server:start';

    protected $description = 'description';

    protected $help = '';

    private $server;

    protected $options = [
        ['address',         'a', InputArgument::OPTIONAL, 'Local source address'],
        ['port',            'p', InputArgument::OPTIONAL, 'Specify local port for remote connects', 2514],
    ];

    public function __construct(Server $server)
    {
        parent::__construct();

        $this->server = $server;
    }

    public function handle()
    {
        $address = $this->input->getOption('address') ?? '0.0.0.0';
        $port = $this->input->getOption('port') ?? '2514';

        $this->output->success(sprintf('Server listening on %s:%d', $address, $port));
        $this->output->comment('Quit the server with CTRL-C.');

        $this->_startServer($address, $port);
    }

    private function _startServer($address, $port)
    {
        $this->server
            ->bind($address, $port)
            ->listen();
    }


}