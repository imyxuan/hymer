<?php

namespace PickOne\Hymer\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PickOne\Hymer\Database\Types\Type;

class MultiPointType extends Type
{
    public const NAME = 'multipoint';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'multipoint';
    }
}
