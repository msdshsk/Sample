<?php

namespace Sample\Property;

use Sample\Property as ParentProperty;
use Sample\Property\Interfaces\Settable;
use Sample\Property\Interfaces\Gettable;
use Sample\Property\Interfaces\Arrayable;

class Standard extends ParentProperty implements Settable, Gettable, Arrayable
{
}
