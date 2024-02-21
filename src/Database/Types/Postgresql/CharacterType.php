<?php

namespace IMyxuan\Hymer\Database\Types\Postgresql;

use IMyxuan\Hymer\Database\Types\Common\CharType;

class CharacterType extends CharType
{
    public const NAME = 'character';
    public const DBTYPE = 'bpchar';
}
