<?php

namespace App\Service;

use App\Server\AbstractServer;
use React\EventLoop\LoopInterface;
use React\Datagram\Socket;
use React\Datagram\Factory;
use Symfony\Component\Console\Command\LockableTrait;

class Server extends AbstractServer
{
    use LockableTrait;

    /* @var $loop LoopInterface */
    protected $loop;

    public function listen(): void
    {
        if (!$this->lock()) {
            $this->logger->warning('The command is already running in another process.');
            return;
        }

        $this
            ->loop()
            ->server()
            ->run();

        $this->release();
    }

    protected function loop(): Server
    {
        if (null === $this->loop) {
            $this->loop = \React\EventLoop\Factory::create();
        }

        return $this;
    }

    protected function server(): Server
    {
        $this->loop->addSignal(SIGINT, [$this, 'stop']);
        $this->loop->addSignal(SIGQUIT, [$this, 'stop']);

        (new Factory($this->loop))
            ->createServer($this->getConnectionStr())
            ->then(function (Socket $server) {
                $server->on('message',
                    Message::withLoop($this->loop, $this->clickHouseClient)
                );
            });

        $this->logger->info("Server successfully started on {$this->host}");

        return $this;
    }

    public function stop(int $signal = 0): void
    {
        switch ($signal) {
            case SIGINT:    $this->logger->alert('Interrupt!'); break;
            case SIGQUIT:   $this->logger->alert('Stop service and quit'); break;
        }

        $this->quit();
    }

    protected function run(): void
    {
        $this->loop->run();
    }

    protected function quit(): void
    {
        $this->loop->stop();
    }
}