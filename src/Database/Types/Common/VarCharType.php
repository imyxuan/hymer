<?php

namespace PickOne\Hymer\Database\Types\Common;

use Doctrine\DBAL\Types\StringType as DoctrineStringType;

class VarCharType extends DoctrineStringType
{
    public const NAME = 'varchar';

    public function getName()
    {
        return static::NAME;
    }
}
