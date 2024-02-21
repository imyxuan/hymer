<?php

namespace PickOne\Hymer\Listeners;

use PickOne\Hymer\Events\BreadAdded;
use PickOne\Hymer\Facades\Hymer;

class AddBreadMenuItem
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
     * Create a MenuItem for a given BREAD.
     *
     * @param BreadAdded $event
     *
     * @return void
     */
    public function handle(BreadAdded $bread)
    {
        if (config('hymer.bread.add_menu_item') && file_exists(base_path('routes/web.php'))) {
            $menu = Hymer::model('Menu')->where('name', config('hymer.bread.default_menu'))->firstOrFail();

            $menuItem = Hymer::model('MenuItem')->firstOrNew([
                'menu_id' => $menu->id,
                'title'   => $bread->dataType->getTranslatedAttribute('display_name_plural'),
                'url'     => '',
                'route'   => 'hymer.'.$bread->dataType->slug.'.index',
            ]);

            $order = Hymer::model('MenuItem')->highestOrderMenuItem();

            if (!$menuItem->exists) {
                $menuItem->fill([
                    'target'     => '_self',
                    'icon_class' => $bread->dataType->icon,
                    'color'      => null,
                    'parent_id'  => null,
                    'order'      => $order,
                ])->save();
            }
        }
    }
}
