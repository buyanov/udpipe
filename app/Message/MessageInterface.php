<?php

namespace App\Message;

interface MessageInterface
{
    public function __invoke(...$args);
}