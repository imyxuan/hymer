<?php

namespace IMyxuan\Hymer\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use IMyxuan\Hymer\Database\Types\Type;

class YearType extends Type
{
    public const NAME = 'year';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'year';
    }
}
