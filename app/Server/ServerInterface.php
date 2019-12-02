<?php

namespace App\Server;

interface ServerInterface {

    public function bind(string $host, int $port): ServerInterface;

    public function stop(): void;

    public function listen(): void;
}