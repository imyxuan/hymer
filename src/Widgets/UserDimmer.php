<?php

namespace PickOne\Hymer\Widgets;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PickOne\Hymer\Facades\Hymer;

class UserDimmer extends BaseDimmer
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $count = Hymer::model('User')->count();
        $string = trans_choice('hymer::dimmer.user', $count);

        return view('hymer::dimmer', array_merge($this->config, [
            'icon'   => 'hymer-group',
            'title'  => "{$count} {$string}",
            'text'   => __('hymer::dimmer.user_text', ['count' => $count, 'string' => Str::lower($string)]),
            'button' => [
                'text' => __('hymer::dimmer.user_link_text'),
                'link' => route('hymer.users.index'),
            ],
            'image' => hymer_asset('images/widget-backgrounds/01.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::user()->can('browse', Hymer::model('User'));
    }
}
