<?php

namespace IMyxuan\Hymer\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use IMyxuan\Hymer\Database\Types\Type;

class MacAddrType extends Type
{
    public const NAME = 'macaddr';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'macaddr';
    }
}
