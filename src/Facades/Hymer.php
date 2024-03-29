<?php

namespace IMyxuan\Hymer\Facades;

use Illuminate\Support\Facades\Facade;

class Hymer extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @method static string image($file, $default = '')
     * @method static $this useModel($name, $object)
     *
     * @see \IMyxuan\Hymer\Hymer
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'hymer';
    }
}
