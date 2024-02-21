<?php

namespace IMyxuan\Hymer\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use IMyxuan\Hymer\Database\Types\Type;

class LongTextType extends Type
{
    public const NAME = 'longtext';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'longtext';
    }
}
