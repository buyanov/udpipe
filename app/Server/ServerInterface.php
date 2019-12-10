<?php

namespace App\Server;

interface ServerInterface {

    public function bind(string $host = '127.0.0.1', int $port = 2514): ServerInterface;

    public function stop(): void;

    public function listen(): void;
}