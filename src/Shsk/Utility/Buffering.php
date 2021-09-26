<?php

namespace Shsk\Utility;

class Buffering
{
    private $callback;
    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    public function output()
    {
        ob_start();
        $callback = $this->callback;
        return $callback();
        return ob_get_clean();
    }
}
