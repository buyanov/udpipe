<?php

namespace App\Message;

abstract class AbstractMessage implements MessageInterface
{
    protected $message;

    protected function filter(array $whitelistedKeys): MessageInterface
    {
        $this->message = (object) array_intersect_key(
            $this->message,
            array_flip($whitelistedKeys)
        );

        return $this;
    }
}