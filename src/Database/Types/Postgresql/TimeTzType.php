<?php

namespace PickOne\Hymer\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PickOne\Hymer\Database\Types\Type;

class TimeTzType extends Type
{
    public const NAME = 'timetz';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'time(0) with time zone';
    }
}
