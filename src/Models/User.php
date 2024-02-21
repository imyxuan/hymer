<?php

namespace IMyxuan\Hymer\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use IMyxuan\Hymer\Contracts\User as UserContract;
use IMyxuan\Hymer\Tests\Database\Factories\UserFactory;
use IMyxuan\Hymer\Traits\HymerUser;
use function Psy\debug;

class User extends Authenticatable implements UserContract
{
    use HymerUser, HasFactory;

    protected $guarded = [];

    public $additional_attributes = ['locale'];

    public function getAvatarAttribute($value)
    {
        return $value ?? config('hymer.user.default_avatar', 'users/default.png');
    }

    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function setSettingsAttribute($value)
    {
        $this->attributes['settings'] = $value ? $value->toJson() : json_encode(['locale' => 'zh_CN',]);
    }

    public function getSettingsAttribute($value)
    {
        return collect(json_decode((string)$value));
    }

    public function setLocaleAttribute($value)
    {
        $this->settings = $this->settings->merge(['locale' => $value]);
    }

    public function getLocaleAttribute()
    {
        return $this->settings->get('locale');
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
