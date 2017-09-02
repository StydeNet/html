<?php

namespace spec\Styde\Html\Menu;

use Illuminate\Contracts\Auth\Access\Gate;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Contracts\Config\Repository as Config;
use Styde\Html\Access\AccessHandler;
use Styde\Html\Theme;
use Illuminate\Translation\Translator as Lang;

class MenuGeneratorSpec extends ObjectBehavior
{
    function let(UrlGenerator $url, Config $config, Theme $theme)
    {
        $this->beConstructedWith($url, $config, $theme);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Styde\Html\Menu\MenuGenerator');
    }

    function it_generate_menu_items($url)
    {
        $url->current()->shouldBeCalled()->willReturn('http://example/about-us');
        $url->to('')->shouldBeCalled()->willReturn('http://example/');

        $url->to('', [], false)->shouldBeCalled()->willReturn('http://example/');
        $url->to('about-us', [], false)->shouldBeCalled()->willReturn('http://example/about-us');

        // When
        $menu = $this->make([
            'home' => ['url' => ''],
            'about' => ['title' => 'Who we are', 'url' => 'about-us'],
            'contact-us' => ['full_url' => 'http://contact.us']
        ]);

        // Expect
        $menu->shouldReturnAnInstanceOf('Styde\Html\Menu\Menu');

        $items = [
            'home' => [
                'class' => '',
                'submenu' => null,
                'id' => 'home',
                'active' => false,
                'url' => 'http://example/',
                'title' => 'Home'
            ],
            'about' => [
                'class' => 'active',
                'submenu' => null,
                'id' => 'about',
                'active' => true,
                'title' => 'Who we are',
                'url' => 'http://example/about-us'
            ],
            'contact-us' => [
                'class' => '',
                'submenu' => null,
                'id' => 'contact-us',
                'active' => false,
                'title' => 'Contact us',
                'url' => 'http://contact.us',
            ]
        ];
        $menu->getItems()->shouldReturn($items);
    }

    function it_renders_menus($theme)
    {
        // Having template
        $template = 'custom.template';
        $classes = 'navbar';

        $menu = $this->make([
            'home' => ['url' => '']
        ], $classes);

        $theme->render(
            $template, [
                'items' => [
                    'home' => [
                        'class' => 'active',
                        'submenu' => null,
                        'id' => 'home',
                        'active' => true,
                        'url' => null,
                        'title' => 'Home'
                    ],
                ],
                'class' => $classes
            ], 'menu'
        )->shouldBeCalled()->willReturn('<menu>');

        $menu->render($template)->shouldReturn('<menu>');
    }

    function it_checks_access(AccessHandler $access)
    {
        // Having
        $this->setAccessHandler($access);

        // Expect
        $access->check(Argument::withEntry("id", "home"))->shouldBeCalled()->willReturn(true);
        $access->check(Argument::withEntry("id", "your-account"))->shouldBeCalled()->willReturn(false);

        // When
        $menu = $this->make([
            'home' => [],
            'your-account' => []
        ]);

        // Expect
        $menu->shouldReturnAnInstanceOf('Styde\Html\Menu\Menu');

        $items = [
            'home' => [
                'class' => '',
                'submenu' => null,
                'id' => 'home',
                'active' => false,
                'title' => 'Home',
                'url' => '#',
            ]
        ];
        $menu->getItems()->shouldReturn($items);
    }

    function it_checks_access_through_the_gate(AccessHandler $access)
    {
        // Having
        $this->setAccessHandler($access);

        // Expect
        $access->check(Argument::withEntry('allows', ['update-post', 1]))
            ->shouldBeCalled()
            ->willReturn(true);

        // When
        $menu = $this->make([
            'edit-post' => [
                'allows' => ['update-post', ':post']
            ],
        ])->setParam('post', 1);

        // Expect
        $menu->shouldReturnAnInstanceOf('Styde\Html\Menu\Menu');

        $items = [
            'edit-post' => [
                'class' => '',
                'submenu' => null,
                'id' => 'edit-post',
                'active' => false,
                'title' => 'Edit post',
                'url' => '#',
            ]
        ];
        $menu->getItems()->shouldReturn($items);
    }

    function it_translates_menu_items(Lang $lang)
    {
        $this->setLang($lang);

        // Expect
        $lang->get('menu.home')->shouldBeCalled()->willReturn('Homepage');
        $lang->get('menu.about')->shouldNotBeCalled();

        // When
        $menu = $this->make([
            'home'  => ['url' => ''],
            'about' => ['title' => 'About us', 'url' => 'about-us']
        ]);

        $items = [
            'home' => [
                'class' => 'active',
                'submenu' => null,
                'id' => 'home',
                'active' => true,
                'url' => null,
                'title' => 'Homepage'
            ],
            'about' => [
                'class' => 'active',
                'submenu' => null,
                'id' => 'about',
                'active' => true,
                'title' => 'About us',
                'url' => null,
            ]
        ];
        $menu->getItems()->shouldReturn($items);
    }

    function it_generates_routes(UrlGenerator $url)
    {
        $dashboard = 'http://example.com/admin/dashboard';
        $home = 'htpp://example.com/admin/pages/home/edit';

        $url->current()->shouldBeCalled()->willReturn($dashboard);
        $url->to('')->shouldBeCalled()->willReturn('http://example/');

        $url->route('dashboard', [])->shouldBeCalled()->willReturn($dashboard);
        $url->route('pages.edit', ['home'])->shouldBeCalled()->willReturn($home);

        $menu = $this->make([
            'dashboard' => ['route' => 'dashboard'],
            'edit_home' => ['route' => ['pages.edit', 'home']]
        ]);

        $items = [
            'dashboard' => [
                'class' => 'active',
                'submenu' => null,
                'id' => 'dashboard',
                'active' => true,
                'title' => 'Dashboard',
                'url' => $dashboard
            ],
            'edit_home' => [
                'class' => '',
                'submenu' => null,
                'id' => 'edit_home',
                'active' => false,
                'title' => 'Edit home',
                'url' => $home
            ],
        ];
        $menu->getItems()->shouldReturn($items);
    }

    function it_implements_routes_with_dynamic_parameters(UrlGenerator $url)
    {
        // Having
        $user_id = 20;
        $year = 2015;
        $month = 07;
        $day = 11;

        $account = "http://example.com/account/$user_id";
        $calendar = "http://example.com/calendar/$year/$month/$day";

        $url->current()->shouldBeCalled()->willReturn($account);
        $url->to('')->shouldBeCalled()->willReturn('http://example/');

        $url->route('account', [$user_id])
            ->shouldBeCalled()
            ->willReturn($account);
        $url->route('calendar', [$year, $month, $day])
            ->shouldBeCalled()
            ->willReturn($calendar);

        // Generate new menu
        $menu = $this->make([
            'account' => [
                'route' => ['account', ':user_id']
            ],
            'calendar' => [
                'route' => ['calendar', ':year', ':month', ':day']
            ]
        ]);

        // With dynamic parameters
        $menu->setParams(compact('year', 'month', 'day'));
        $menu->setParam('user_id', $user_id);

        $items = [
            'account' => [
                'class' => 'active',
                'submenu' => null,
                'id' => 'account',
                'active' => true,
                'title' => 'Account',
                'url' => $account
            ],
            'calendar' => [
                'class' => '',
                'submenu' => null,
                'id' => 'calendar',
                'active' => false,
                'title' => 'Calendar',
                'url' => $calendar
            ],
        ];
        $menu->getItems()->shouldReturn($items);
    }

    function it_generates_submenu_items()
    {
        // Generate new menu
        $menu = $this->make([
            'home' => [],
            'pages' => [
                'submenu' => [
                    'about' => [],
                    'company' => ['url' => 'company']
                ]
            ]
        ]);

        $items = [
            'home' => [
                'class' => '',
                'submenu' => null,
                'id' => 'home',
                'active' => false,
                'title' => 'Home',
                'url' => '#'
            ],
            'pages' => [
                'class' => 'active dropdown',
                'submenu' =>[
                    'about' => [
                        'class' => '',
                        'submenu' => null,
                        'id' => 'about',
                        'active' => false,
                        'title' => 'About',
                        'url' => '#',
                        'id'  => 'about'
                    ],
                    'company' => [
                        'class' => 'active',
                        'submenu' => null,
                        'id' => 'company',
                        'active' => true,
                        'url' => null,
                        'title' => 'Company'
                    ],
                ],
                'id' => 'pages',
                'active' => true,
                'title' => 'Pages',
                'url' => '#'
            ]
        ];
        $menu->getItems()->shouldReturn($items);
    }

    function it_generates_nested_submenu_items_with_active_classes()
    {
        // Generate new menu
        $menu = $this->make([
            'home' => [],
            'pages' => [
                'submenu' => [
                    'about' => [
                        'submenu' => [
                            'company' => ['url' => 'company']
                        ]
                    ]
                ]
            ]
        ]);

        $items = [
            'home' => [
                'class' => '',
                'submenu' => null,
                'id' => 'home',
                'active' => false,
                'title' => 'Home',
                'url' => '#'
            ],
            'pages' => [
                'class' => 'active dropdown',
                'submenu' =>[
                    'about' => [
                        'class' => 'active dropdown',
                        'submenu' => [
                            'company' => [
                                'class' => 'active',
                                'submenu' => null,
                                'id' => 'company',
                                'active' => true,
                                'url' => null,
                                'title' => 'Company'
                            ],
                        ],
                        'id' => 'about',
                        'active' => true,
                        'title' => 'About',
                        'url' => '#',
                        'id'  => 'about'
                    ],
                ],
                'id' => 'pages',
                'active' => true,
                'title' => 'Pages',
                'url' => '#'
            ]
        ];
        $menu->getItems()->shouldReturn($items);
    }

    function it_supports_the_exact_option_to_set_active_menu_items($url)
    {
        $url->current()->shouldBeCalled()->willReturn('http://example/contact/london');
        $url->to('')->shouldBeCalled()->willReturn('http://example/');

        $url->to('contact', [], false)->shouldBeCalled()->willReturn('http://example/contact');
        $url->to('contact/london', [], false)->shouldBeCalled()->willReturn('http://example/contact/london');

        $menu = $this->make([
            'contact' => ['url' => 'contact', 'exact' => true],
            'contact-london' => ['url' => 'contact/london'],
        ]);

        $menu->getItems()->shouldReturn([
            'contact' => [
                'class' => '',
                'submenu' => null,
                'id' => 'contact',
                'active' => false,
                'url' => 'http://example/contact',
                'title' => 'Contact',
            ],
            'contact-london' => [
                'class' => 'active',
                'submenu' => null,
                'id' => 'contact-london',
                'active' => true,
                'url' => 'http://example/contact/london',
                'title' => 'Contact london',
            ]
        ]);
    }

    function it_allows_you_to_set_a_custom_active_url_resolver()
    {
        $this->setActiveUrlResolver(function ($values) {
            return $values['id'] == 'contact-us';
        });

        $menu = $this->make([
            'home' => [],
            'contact' => ['id' => 'contact-us'],
        ]);

        $menu->getItems()->shouldReturn([
            'home' => [
                'class' => '',
                'submenu' => null,
                'id' => 'home',
                'active' => false,
                'title' => 'Home',
                'url' => '#',
            ],
            'contact' => [
                'class' => 'active',
                'submenu' => null,
                'id' => 'contact-us',
                'active' => true,
                'title' => 'Contact',
                'url' => '#',
            ]
        ]);
    }

}
