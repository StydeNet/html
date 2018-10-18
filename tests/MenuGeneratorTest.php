<?php

namespace Styde\Html\Tests;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\{Auth, Gate, Route, View};
use Styde\Html\Facades\Menu;
use Styde\Html\Menu\{MenuBuilder, MenuComposer, Item};

class MenuGeneratorTest extends TestCase
{
    /** @test */
    function it_renders_menus()
    {
        $menu = Menu::make(function ($items) {
            $items->url('/', 'Home');
            $items->placeholder('About us');
            $items->url('projects', 'Our projects');
            $items->url('contact-us', 'Contact us');
        });

        $this->assertInstanceOf(\Styde\Html\Menu\Menu::class, $menu);
        $this->assertTemplateMatches('menu/menu', $menu);
    }

    /** @test */
    function it_renders_menus_with_additional_classes()
    {
        $menu = Menu::make(function ($items) {
            $items->url('/', 'Home');
            $items->url('contact-us', 'Contact us');
        })->setClass('nav other css classes');

        $this->assertInstanceOf(\Styde\Html\Menu\Menu::class, $menu);
        $this->assertTemplateMatches('menu/menu-classes', $menu);
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
            $items->route('account', 'Account', compact('user_id'));
            $items->route('calendar', 'Calendar', compact('year', 'month', 'day'));
        });

        $this->assertTemplateMatches('menu/parameters', $menu);
    }

    /** @test */
    function it_generates_links_from_actions()
    {
        Route::get('dashboard', ['uses' => 'DashboardController@index']);
        Route::get('edit/{page}', ['uses' => 'PageController@edit']);

        $menu = Menu::make(function ($items) {
            $items->action('DashboardController@index', 'Dashboard');
            $items->action('PageController@edit', 'Edit home', ['home']);
        });

        $this->assertTemplateMatches('menu/routes', $menu);
    }

    /** @test */
    function it_generates_links_from_actions_with_parameters()
    {
        Route::get('account/{user_id}', ['uses' => 'AccountController@show']);
        Route::get('calendar/{year}/{month}/{day}', ['uses' => 'CalendarController@show']);

        // If you have external parameters, just pass them to the Closure and build the actions there.
        $user_id = 20;
        $year = 2015;
        $month = 7;
        $day = 11;

        $menu = Menu::make(function ($items) use ($user_id, $year, $month, $day) {
            $items->action('AccountController@show', 'Account', compact('user_id'));
            $items->action('CalendarController@show', 'Calendar', compact('year', 'month', 'day'));
        });

        $this->assertTemplateMatches('menu/parameters', $menu);
    }

    /** @test */
    function it_generates_links_from_url_with_parameters()
    {
        $user_id = 20;
        $year = 2015;
        $month = 7;
        $day = 11;

        $menu = Menu::make(function ($items) use ($user_id, $year, $month, $day) {
            $items->url('account', 'Account', ['user_id' => $user_id]);
            $items->url('calendar', 'Calendar', compact('year', 'month', 'day'));
        });

        $this->assertTemplateMatches('menu/parameters', $menu);
    }

    /** @test */
    function it_renders_menus_with_secure_urls()
    {
        $menu = Menu::make(function ($items) {
            $items->secureUrl('/', 'Home');
            $items->url('login', 'Log in', [], true);
        });

        $this->assertTemplateMatches('menu/secure-urls', $menu);
    }

    /** @test */
    function it_renders_menus_with_raw_urls()
    {
        $menu = Menu::make(function ($items) {
            $items->raw('https://laravel.com', 'Laravel');
            $items->raw('https://github.com', 'Github');
        });

        $this->assertTemplateMatches('menu/raw-urls', $menu);
    }

    /** @test */
    function it_can_exclude_items_based_on_permissions()
    {
        Route::get('/login', ['as' => 'login']);
        Route::get('/logout', ['as' => 'logout']);

        $fakeUser = new class extends Model implements AuthenticatableInterface {
            use Authenticatable;

            public function isA($role)
            {
                return $role == 'admin';
            }
        };

        $fakePost = new class { public $id = 1; };

        Auth::login($fakeUser);

        Gate::define('update-post', function ($user, $post) use ($fakePost) {
            return $post->id === $fakePost->id;
        });

        Gate::define('delete-post', function ($user) {
            return false;
        });

        $menu = Menu::make(function ($items) use ($fakePost) {
            // Should be included always.
            $items->url('posts/1', 'View post');

            // Should be included.
            $items->url('posts/1/edit', 'Edit post')->ifCan('update-post', $fakePost);

            // Should be excluded.
            $items->url('posts/1/suggest-changes', 'Suggest changes')->ifCannot('update-post', $fakePost);

            // Should be included: the fake user is an admin.
            $items->url('posts/1/publish', 'Publish post')->ifIs('admin');

            // Should be included: the user is authenticated.
            $items->route('logout', 'Logout')->ifAuth();

            // Should be excluded: the user is not a guest.
            $items->route('login', 'Sign in')->ifGuest();
        });

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

    /** @test */
    function it_generates_submenus_with_an_active_subsection()
    {
        $this->app->request->server->set('REQUEST_URI', '/careers');

        $menu = Menu::make(function ($items) {
            $items->url('/', 'Home');

            $items->submenu('About us', function ($items) {
                $items->placeholder('Team');
                $items->url('careers', 'Work with us');
            });

            $items->url('projects', 'Our projects');

            $items->url('contact-us', 'Contact us');
        });

        $this->assertTemplateMatches('menu/submenu-active', $menu);
    }

    /** @test */
    function menu_items_can_have_extra_attributes()
    {
        $item = new Item('path', 'text');

        $item->target('_blank');

        return $this->assertSame('_blank', $item->getItem()->target);
    }

    /** @test */
    function menu_items_can_have_extra_classes()
    {
        $item = new Item('path', 'text');

        $item->classes(['font-weight-bold', 'text-primary']);

        return $this->assertSame('font-weight-bold text-primary', $item->getItem()->class->toHtml());
    }

    /** @test */
    function can_customize_the_template()
    {
        View::addLocation(__DIR__.'/views');

        $menu = Menu::make(function ($items) {
            $items->url('/', 'Home');
        })->template('custom-templates/menu');

        $this->assertInstanceOf(\Styde\Html\Menu\Menu::class, $menu);
        $this->assertTemplateMatches('menu/custom-template', $menu);
    }

    /** @test */
    function can_create_menus_using_a_menu_composer()
    {
        $menu = new class extends MenuComposer {
            public function compose(MenuBuilder $items)
            {
                $items->url('/', 'Home');
                $items->placeholder('About us');
                $items->url('projects', 'Our projects');
                $items->url('contact-us', 'Contact us');
            }
        };

        $this->assertTemplateMatches('menu/menu', $menu);
    }

    /** @test */
    function can_create_menus_using_a_helper()
    {
        $config = function($items) {
            $items->url('/', 'Home');
            $items->placeholder('About us');
            $items->url('projects', 'Our projects');
            $items->url('contact-us', 'Contact us');
        };
        $this->assertTemplateMatches('menu/menu', menu($config));
    }
}
