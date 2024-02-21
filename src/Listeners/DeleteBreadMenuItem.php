<?php

namespace IMyxuan\Hymer\Listeners;

use IMyxuan\Hymer\Events\BreadDeleted;
use IMyxuan\Hymer\Facades\Hymer;

class DeleteBreadMenuItem
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Delete a MenuItem for a given BREAD.
     *
     * @param BreadDeleted $bread
     *
     * @return void
     */
    public function handle(BreadDeleted $bread)
    {
        if (config('hymer.bread.add_menu_item')) {
            $menuItem = Hymer::model('MenuItem')->where('route', 'hymer.'.$bread->dataType->slug.'.index');

            if ($menuItem->exists()) {
                $menuItem->delete();
            }
        }
    }
}
