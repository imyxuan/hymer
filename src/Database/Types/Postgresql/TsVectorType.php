<?php

namespace PickOne\Hymer\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PickOne\Hymer\Database\Types\Type;

class TsVectorType extends Type
{
    public const NAME = 'tsvector';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'tsvector';
    }
}
