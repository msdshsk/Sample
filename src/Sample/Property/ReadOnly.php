<?php

namespace Sample\Property;

use Sample\Property as ParentProperty;
use Sample\Property\Interfaces\Gettable;
use Sample\Property\Interfaces\Arrayable;

class ReadOnly extends ParentProperty implements Gettable, Arrayable
{
}
