<?php

namespace IMyxuan\Hymer\Database\Types\Postgresql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use IMyxuan\Hymer\Database\Types\Type;

class MoneyType extends Type
{
    public const NAME = 'money';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        return 'money';
    }
}
