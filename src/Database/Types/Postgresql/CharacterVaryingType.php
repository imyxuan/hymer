<?php

namespace IMyxuan\Hymer\Database\Types\Postgresql;

use IMyxuan\Hymer\Database\Types\Common\VarCharType;

class CharacterVaryingType extends VarCharType
{
    public const NAME = 'character varying';
    public const DBTYPE = 'varchar';
}
