<?php

namespace IMyxuan\Hymer\Database\Types\Mysql;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use IMyxuan\Hymer\Database\Types\Type;

class MediumIntType extends Type
{
    public const NAME = 'mediumint';

    public function getSQLDeclaration(array $field, AbstractPlatform $platform)
    {
        $commonIntegerTypeDeclaration = call_protected_method($platform, '_getCommonIntegerTypeDeclarationSQL', $field);

        return 'mediumint'.$commonIntegerTypeDeclaration;
    }
}
