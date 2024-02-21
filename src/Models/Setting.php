<?php

namespace IMyxuan\Hymer\Models;

use Illuminate\Database\Eloquent\Model;
use IMyxuan\Hymer\Events\SettingUpdated;

class Setting extends Model
{
    protected $table = 'settings';

    protected $guarded = [];

    public $timestamps = false;

    protected $dispatchesEvents = [
        'updating' => SettingUpdated::class,
    ];
}
