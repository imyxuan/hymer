<?php

namespace PickOne\Hymer\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PickOne\Hymer\Database\Types\Type;

class LongBlobType extends Type
{
    public const NAME = 'longblob';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'longblob';
    }
}
