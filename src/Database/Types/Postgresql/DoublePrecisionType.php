<?php

namespace IMyxuan\Hymer\Database\Types\Postgresql;

use IMyxuan\Hymer\Database\Types\Common\DoubleType;

class DoublePrecisionType extends DoubleType
{
    public const NAME = 'double precision';
    public const DBTYPE = 'float8';
}
