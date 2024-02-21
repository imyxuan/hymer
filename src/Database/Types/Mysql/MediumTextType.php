<?php

namespace PickOne\Hymer\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PickOne\Hymer\Database\Types\Type;

class MediumTextType extends Type
{
    public const NAME = 'mediumtext';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'mediumtext';
    }
}
