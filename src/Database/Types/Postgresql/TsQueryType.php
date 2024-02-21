<?php

namespace PickOne\Hymer\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PickOne\Hymer\Database\Types\Type;

class TsQueryType extends Type
{
    public const NAME = 'tsquery';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'tsquery';
    }
}
