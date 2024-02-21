<?php

namespace PickOne\Hymer\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PickOne\Hymer\Database\Types\Type;

class MultiLineStringType extends Type
{
    public const NAME = 'multilinestring';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'multilinestring';
    }
}
