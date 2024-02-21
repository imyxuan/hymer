<?php

namespace IMyxuan\Hymer\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use IMyxuan\Hymer\Database\Types\Type;

class FloatType extends Type
{
    public const NAME = 'float';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'float';
    }
}
