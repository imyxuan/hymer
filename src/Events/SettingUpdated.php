<?php

namespace PickOne\Hymer\Events;

use Illuminate\Queue\SerializesModels;
use PickOne\Hymer\Models\Setting;

class SettingUpdated
{
    use SerializesModels;

    public $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }
}
