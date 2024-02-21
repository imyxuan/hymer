<?php

namespace PickOne\Hymer\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PickOne\Hymer\Database\Types\Type;

class PointType extends Type
{
    public const NAME = 'point';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'point';
    }
}
