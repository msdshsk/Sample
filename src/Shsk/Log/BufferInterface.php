<?php

namespace Shsk\Log;

interface BufferInterface
{
    public function bufferStart($name);
    public function bufferStop($name);
}
