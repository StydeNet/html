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
        $items = [
            'home' => ['url' => '/'],
            'about' => [],
            'projects' => ['title' => 'Our projects', 'url' => 'projects'],
            'contact' => ['url' => 'contact-us'],
        ];

        $this->assertTemplateMatches('menu/menu', Menu::make($items));
    }

    /** @test */
    function it_generates_routes()
    {
        Route::get('dashboard', ['as' => 'dashboard']);
        Route::get('edit_home', ['as' => 'pages.edit']);

        $items = [
            'dashboard' => ['route' => 'dashboard'],
            'edit_home' => ['route' => ['pages.edit', 'home']],
        ];

        $this->assertTemplateMatches('menu/routes', Menu::make($items));
    }

    /** @test */
    function it_implements_routes_with_dynamic_parameters()
    {
        Route::get('account/{user_id}', ['as' => 'account']);
        Route::get('calendar/{year}/{month}/{day}', ['as' => 'calendar']);

        $items = [
            'account' => [
                'route' => ['account', ':user_id'],
            ],
            'calendar' => [
                'route' => ['calendar', ':year', ':month', ':day'],
            ],
        ];

        $menu = Menu::make($items)
            ->setParams(['year' => 2015, 'month' => 07, 'day' => 11])
            ->setParam('user_id', 20);

        $this->assertTemplateMatches('menu/parameters', $menu);
    }

    /** @test */
    function it_checks_for_access_using_the_access_handler_and_the_gate()
    {
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
        $items = [
            'home' => ['url' => '/'],
            'about' => [
                'submenu' => [
                    'team' => [],
                    'careers' => ['title' => 'Work with us'],
                ],
            ],
            'projects' => ['title' => 'Our projects', 'url' => 'projects'],
            'contact' => ['url' => 'contact-us'],
        ];

        $this->assertTemplateMatches('menu/submenu', Menu::make($items));
    }

}
