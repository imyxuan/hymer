<?php

namespace IMyxuan\Hymer\Widgets;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use IMyxuan\Hymer\Facades\Hymer;

class PageDimmer extends BaseDimmer
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
        $count = Hymer::model('Page')->count();
        $string = trans_choice('hymer::dimmer.page', $count);

        return view('hymer::dimmer', array_merge($this->config, [
            'icon'   => 'hymer-file-text',
            'title'  => "{$count} {$string}",
            'text'   => __('hymer::dimmer.page_text', ['count' => $count, 'string' => Str::lower($string)]),
            'button' => [
                'text' => __('hymer::dimmer.page_link_text'),
                'link' => route('hymer.pages.index'),
            ],
            'image' => hymer_asset('images/widget-backgrounds/03.jpg'),
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
        return Auth::user()->can('browse', Hymer::model('Page'));
    }
}
