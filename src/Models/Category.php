<?php

namespace IMyxuan\Hymer\Models;

use Illuminate\Database\Eloquent\Model;
use IMyxuan\Hymer\Facades\Hymer;
use IMyxuan\Hymer\Traits\Translatable;

class Category extends Model
{
    use Translatable;

    protected $translatable = ['slug', 'name'];

    protected $table = 'categories';

    protected $fillable = ['slug', 'name'];

    public function posts()
    {
        return $this->hasMany(Hymer::modelClass('Post'))
            ->published()
            ->orderBy('created_at', 'DESC');
    }

    public function parentId()
    {
        return $this->belongsTo(self::class);
    }
}
