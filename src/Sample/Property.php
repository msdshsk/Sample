<?php

namespace Sample;

use Sample\Property\Traits\Accessor;

class Property
{
    use Accessor;

    public function __construct(array $ary)
    {
        $this->setProperties($ary);
    }
}
