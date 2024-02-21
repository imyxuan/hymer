<?php

namespace IMyxuan\Hymer\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use IMyxuan\Hymer\Database\Types\Type;

class ByteaType extends Type
{
    public const NAME = 'bytea';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'bytea';
    }
}
