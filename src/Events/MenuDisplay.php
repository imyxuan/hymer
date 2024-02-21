<?php

namespace PickOne\Hymer\Events;

use Illuminate\Queue\SerializesModels;
use PickOne\Hymer\Models\Menu;

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
