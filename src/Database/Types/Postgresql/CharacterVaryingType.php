<?php

namespace PickOne\Hymer\Database\Types\Postgresql;

use PickOne\Hymer\Database\Types\Common\VarCharType;

class CharacterVaryingType extends VarCharType
{
    public const NAME = 'character varying';
    public const DBTYPE = 'varchar';
}
