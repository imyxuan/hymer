<?php

namespace PickOne\Hymer\Database\Types\Postgresql;

use PickOne\Hymer\Database\Types\Common\DoubleType;

class DoublePrecisionType extends DoubleType
{
    public const NAME = 'double precision';
    public const DBTYPE = 'float8';
}
