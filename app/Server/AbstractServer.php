<?php

namespace App\Server;

use App\Service\ClickHouseClient;
use Psr\Log\LoggerInterface;

abstract class AbstractServer implements ServerInterface
{
    protected $host;
    protected $port;

    /** @var LoggerInterface  */
    protected $logger;

    /** @var \ClickHouseDB\Client */
    protected $clickHouseClient;

    public function __construct(LoggerInterface $logger, ClickHouseClient $clickHouseClient)
    {
        $this->logger = $logger;
        $this->clickHouseClient = $clickHouseClient->getClient();
    }

    public function bind(string $host = '127.0.0.1', int $port = 2514): ServerInterface
    {
        $this->setHost($host);
        $this->setPort($port);

        return $this;
    }

    protected function getConnectionStr(): string
    {
        return "udp://$this->host:$this->port";
    }

    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    public function setPort(string $port): void
    {
        $this->port = $port;
    }

    public function getName()
    {
        return 'UDPIPE';
    }

}