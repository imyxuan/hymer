<?php

namespace PickOne\Hymer\Tests;

class RouteTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testGetRoutes()
    {
        $this->disableExceptionHandling();

        $this->visit(route('hymer.login'));
        $this->type('admin@admin.com', 'email');
        $this->type('password', 'password');
        $this->press(__('hymer::generic.login'));

        $urls = [
            route('hymer.dashboard'),
            route('hymer.media.index'),
            route('hymer.settings.index'),
            route('hymer.roles.index'),
            route('hymer.roles.create'),
            route('hymer.roles.show', 1),
            route('hymer.roles.edit', 1),
            route('hymer.users.index'),
            route('hymer.users.create'),
            route('hymer.users.show', 1),
            route('hymer.users.edit', 1),
            route('hymer.posts.index'),
            route('hymer.posts.create'),
            route('hymer.posts.show', 1),
            route('hymer.posts.edit', 1),
            route('hymer.pages.index'),
            route('hymer.pages.create'),
            route('hymer.pages.show', 1),
            route('hymer.pages.edit', 1),
            route('hymer.categories.index'),
            route('hymer.categories.create'),
            route('hymer.categories.show', 1),
            route('hymer.categories.edit', 1),
            route('hymer.menus.index'),
            route('hymer.menus.create'),
            route('hymer.menus.show', 1),
            route('hymer.menus.edit', 1),
            route('hymer.database.index'),
            route('hymer.bread.edit', 'categories'),
            route('hymer.database.edit', 'categories'),
            route('hymer.database.create'),
        ];

        foreach ($urls as $url) {
            $response = $this->call('GET', $url);
            $this->assertEquals(200, $response->status(), $url.' did not return a 200');
        }
    }
}
