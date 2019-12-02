<?php

namespace App\Service;

use App\Server\AbstractServer;
use React\EventLoop\LoopInterface;
use React\Datagram\Socket;
use React\Datagram\Factory;

class Server extends AbstractServer
{
    /* @var $loop LoopInterface */
    protected $loop;

    public function listen(): void
    {
        $this
            ->loop()
            ->server()
            ->run();
    }

    protected function getConnectionStr(): string
    {
        return "udp://$this->host:$this->port";
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