<?php

namespace PickOne\Hymer\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PickOne\Hymer\Database\Types\Type;

class PolygonType extends Type
{
    public const NAME = 'polygon';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'polygon';
    }
}
