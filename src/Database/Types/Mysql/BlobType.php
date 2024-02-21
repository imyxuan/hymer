<?php

namespace PickOne\Hymer\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PickOne\Hymer\Database\Types\Type;

class BlobType extends Type
{
    public const NAME = 'blob';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'blob';
    }
}
