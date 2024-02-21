<?php

namespace IMyxuan\Hymer\Events;

use Illuminate\Queue\SerializesModels;
use IMyxuan\Hymer\Models\Setting;

class SettingUpdated
{
    use SerializesModels;

    public $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }
}
