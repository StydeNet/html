<?php

namespace Styde\Html\Tests;

use Styde\Html\Facades\Menu;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\{Auth, Gate, Route};
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableInterface;

class MenuGeneratorTest extends TestCase
{
    /** @test */
    function it_render_menus()
    {
        $menu = Menu::make(function ($items) {
            $items->url('/', 'Home');
            $items->placeholder('About us');
            $items->url('projects', 'Our projects');
            $items->url('contact-us', 'Contact us');
        });

        $this->assertTemplateMatches('menu/menu', $menu);
    }

    /** @test */
    function it_generates_links_from_routes()
    {
        Route::get('dashboard', ['as' => 'dashboard']);
        Route::get('edit/{page}', ['as' => 'pages.edit']);

        $menu = Menu::make(function ($items) {
            $items->route('dashboard', 'Dashboard');
            $items->route('pages.edit', 'Edit home', ['home']);
        });

        $this->assertTemplateMatches('menu/routes', $menu);
    }

    /** @test */
    function it_generates_links_from_routes_with_parameters()
    {
        Route::get('account/{user_id}', ['as' => 'account']);
        Route::get('calendar/{year}/{month}/{day}', ['as' => 'calendar']);

        // If you have external parameters, just pass them to the Closure and build the routes there.
        $user_id = 20;
        $year = 2015;
        $month = 7;
        $day = 11;

        $menu = Menu::make(function ($items) use ($user_id, $year, $month, $day) {
            $items->route('account', 'Account')->parameters(compact('user_id'));
            $items->route('calendar', 'Calendar')->parameters(compact('year', 'month', 'day'));
        });

        $this->assertTemplateMatches('menu/parameters', $menu);
    }

    /** @test */
    function it_checks_for_access_using_the_access_handler_and_the_gate()
    {
        $this->markTestIncomplete();

        $fakeUser = new class extends Model implements AuthenticatableInterface {
            use Authenticatable;
        };

        $fakePost = new class { public $id = 1; };

        Auth::login($fakeUser);

        Gate::define('update-post', function ($user, $post) use ($fakePost) {
            return $post->id === $fakePost->id;
        });

        Gate::define('delete-post', function ($user) {
            return false;
        });

        $items = array(
            'view-post' => [
            ],
            'edit-post' => [
                'allows' => ['update-post', ':post']
            ],
            'review-post' => [
                'denies' => ['update-post', ':post']
            ],
            'delete-post' => [
                'allows' => 'delete-post'
            ]
        );

        $menu = Menu::make($items)->setParam('post', $fakePost);

        $this->assertTemplateMatches('menu/access-handler', $menu);
    }

    /** @test */
    function it_generates_submenus()
    {
        $menu = Menu::make(function ($items) {
            $items->url('/', 'Home');

            $items->submenu('About us', function ($items) {
                $items->placeholder('Team');
                $items->url('careers', 'Work with us');
            });

            $items->url('projects', 'Our projects');

            $items->url('contact-us', 'Contact us');
        });

        $this->assertTemplateMatches('menu/submenu', $menu);
    }

}
