<?php

namespace Shsk\Log\Handler;

interface HandlerInterface
{
    public function write($level, $message, array $context = []);
}
