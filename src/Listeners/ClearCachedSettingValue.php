<?php

namespace IMyxuan\Hymer\Listeners;

use Cache;
use IMyxuan\Hymer\Events\SettingUpdated;

class ClearCachedSettingValue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * handle.
     *
     * @param SettingUpdated $event
     *
     * @return void
     */
    public function handle(SettingUpdated $event)
    {
        if (config('hymer.settings.cache', false) === true) {
            Cache::tags('settings')->forget($event->setting->key);
        }
    }
}
