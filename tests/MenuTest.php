<?php

namespace PickOne\Hymer\Tests;

use Illuminate\Support\Facades\Auth;
use PickOne\Hymer\Models\Menu;

class MenuTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Auth::loginUsingId(1);
    }

    public function testCanRenameMenu()
    {
        $menu = Menu::where('name', '=', 'admin')->first();
        $this->visitRoute('hymer.menus.edit', $menu->id)
             ->seeInField('name', $menu->name)
             ->type('new_admin', 'name')
             ->seeInElement('button', __('hymer::generic.save'))
             ->press(__('hymer::generic.save'))
             ->seePageIs(route('hymer.menus.index'))
             ->seeInDatabase('menus', [
                 'id'   => $menu->id,
                 'name' => 'new_admin',
             ]);
    }

    public function testCanDeleteMenuItem()
    {
        $menu = Menu::where('name', '=', 'admin')->first();
        $this->delete(route('hymer.menus.item.destroy', [
            'menu' => $menu->id,
            'id'   => $menu->items->first()->id,
        ]))->notSeeInDatabase('menu_items', [
            'id' => $menu->items->first()->id,
        ]);
    }

    public function testCanAddMenuItem()
    {
        $menu = Menu::where('name', '=', 'admin')->first();
        $this->post(route('hymer.menus.item.add', [
            'menu'    => $menu->id,
            'menu_id' => $menu->id,
            'type'    => 'url',
            'color'   => '#000000',
            'title'   => 'Title',
            'url'     => '#',
            'target'  => '_self',
        ]))->seeInDatabase('menu_items', [
            'menu_id' => $menu->id,
            'title'   => 'Title',
        ]);
    }

    public function testCanUpdateMenuItem()
    {
        $menu = Menu::where('name', '=', 'admin')->first();
        $item = $menu->items->first();
        $this->put(route('hymer.menus.item.update', [
            'id'      => $item->id,
            'menu'    => $menu->id,
            'type'    => 'url',
            'color'   => '#000000',
            'title'   => 'New Title',
            'url'     => '#',
            'target'  => '_self',
        ]))->seeInDatabase('menu_items', [
            'menu_id' => $menu->id,
            'id'      => $item->id,
            'title'   => 'New Title',
        ]);
    }

    public function testCanOrderMenu()
    {
        $menu = Menu::where('name', '=', 'admin')->first();
        $response = $this->post('http://localhost/admin/menus/1/order', ['order' => '[{"id":4},{"id":1},{"id":3},{"id":2},{"id":5,"children":[{"id":6},{"id":7},{"id":8},{"id":9},{"id":11}]},{"id":10},{"id":12}]']);
        $this->assertEquals(200, $response->response->status());
    }

    public function testCanSeeMenuBuilder()
    {
        $menu = Menu::where('name', '=', 'admin')->first();
        $response = $this->call('GET', route('hymer.menus.builder', ['menu' => $menu->id]));
        $this->assertEquals(200, $response->status());
    }
}