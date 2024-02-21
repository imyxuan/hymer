<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use IMyxuan\Hymer\Models\Menu;
use IMyxuan\Hymer\Models\MenuItem;

class MenuItemsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $menu = Menu::where('name', 'admin')->firstOrFail();

        // Dashboard
        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('hymer::seeders.menu_items.dashboard'),
            'url'     => '',
            'route'   => 'hymer.dashboard',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'hymer-boat',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 1,
            ])->save();
        }

        // System Manage
        $manageMenuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('hymer::seeders.menu_items.system_manage'),
            'url'     => '',
        ]);
        if (!$manageMenuItem->exists) {
            $manageMenuItem->fill([
                'target'     => '_self',
                'icon_class' => 'hymer-laptop',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 2,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('hymer::seeders.menu_items.users'),
            'url'     => '',
            'route'   => 'hymer.users.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'hymer-person',
                'color'      => null,
                'parent_id'  => $manageMenuItem->id,
                'order'      => 1,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('hymer::seeders.menu_items.roles'),
            'url'     => '',
            'route'   => 'hymer.roles.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'hymer-lock',
                'color'      => null,
                'parent_id'  => $manageMenuItem->id,
                'order'      => 2,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('hymer::seeders.menu_items.media'),
            'url'     => '',
            'route'   => 'hymer.media.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'hymer-images',
                'color'      => null,
                'parent_id'  => $manageMenuItem->id,
                'order'      => 3,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('hymer::seeders.menu_items.settings'),
            'url'     => '',
            'route'   => 'hymer.settings.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'hymer-settings',
                'color'      => null,
                'parent_id'  => $manageMenuItem->id,
                'order'      => 4,
            ])->save();
        }

        // System Tools
        $toolsMenuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('hymer::seeders.menu_items.tools'),
            'url'     => '',
        ]);
        if (!$toolsMenuItem->exists) {
            $toolsMenuItem->fill([
                'target'     => '_self',
                'icon_class' => 'hymer-tools',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 3,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('hymer::seeders.menu_items.menu_builder'),
            'url'     => '',
            'route'   => 'hymer.menus.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'hymer-list',
                'color'      => null,
                'parent_id'  => $toolsMenuItem->id,
                'order'      => 1,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('hymer::seeders.menu_items.database'),
            'url'     => '',
            'route'   => 'hymer.database.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'hymer-data',
                'color'      => null,
                'parent_id'  => $toolsMenuItem->id,
                'order'      => 2,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('hymer::seeders.menu_items.compass'),
            'url'     => '',
            'route'   => 'hymer.compass.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'hymer-compass',
                'color'      => null,
                'parent_id'  => $toolsMenuItem->id,
                'order'      => 3,
            ])->save();
        }

        $menuItem = MenuItem::firstOrNew([
            'menu_id' => $menu->id,
            'title'   => __('hymer::seeders.menu_items.bread'),
            'url'     => '',
            'route'   => 'hymer.bread.index',
        ]);
        if (!$menuItem->exists) {
            $menuItem->fill([
                'target'     => '_self',
                'icon_class' => 'hymer-bread',
                'color'      => null,
                'parent_id'  => $toolsMenuItem->id,
                'order'      => 4,
            ])->save();
        }
    }
}
