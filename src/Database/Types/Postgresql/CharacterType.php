<?php

namespace PickOne\Hymer\Database\Types\Postgresql;

use PickOne\Hymer\Database\Types\Common\CharType;

class CharacterType extends CharType
{
    public const NAME = 'character';
    public const DBTYPE = 'bpchar';
}
