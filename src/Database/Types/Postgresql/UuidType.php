<?php

namespace PickOne\Hymer\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PickOne\Hymer\Database\Types\Type;

class UuidType extends Type
{
    public const NAME = 'uuid';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'uuid';
    }
}
