<?php

namespace PickOne\Hymer\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PickOne\Hymer\Database\Types\Type;

class CidrType extends Type
{
    public const NAME = 'cidr';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'cidr';
    }
}
