<?php

namespace IMyxuan\Hymer\Events;

use Illuminate\Queue\SerializesModels;
use IMyxuan\Hymer\Models\Menu;

class MenuDisplay
{
    use SerializesModels;

    public $menu;

    public function __construct(Menu $menu)
    {
        $this->menu = $menu;

        // @deprecate
        //
        event('hymer.menu.display', $menu);
    }
}
