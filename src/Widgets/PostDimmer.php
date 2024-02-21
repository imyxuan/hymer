<?php

namespace PickOne\Hymer\Widgets;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PickOne\Hymer\Facades\Hymer;

class PostDimmer extends BaseDimmer
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
        $count = Hymer::model('Post')->count();
        $string = trans_choice('hymer::dimmer.post', $count);

        return view('hymer::dimmer', array_merge($this->config, [
            'icon'   => 'hymer-news',
            'title'  => "{$count} {$string}",
            'text'   => __('hymer::dimmer.post_text', ['count' => $count, 'string' => Str::lower($string)]),
            'button' => [
                'text' => __('hymer::dimmer.post_link_text'),
                'link' => route('hymer.posts.index'),
            ],
            'image' => hymer_asset('images/widget-backgrounds/02.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::user()->can('browse', Hymer::model('Post'));
    }
}
