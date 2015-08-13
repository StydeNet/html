# Menus (MenuGenerator)

Menus are not static elements,  it is often necessary to mark the active section, translate items, generate dynamic URLs or show/hide options only for certain users.

So instead of adding too much extra HTML and Blade boilerplate code, you can use this component to generate dynamic menus styled for your current CSS framework.

To generate a menu simply add the following code in your layout's template:

`{!! Menu::make('items.here', 'optional css classes') !!}`

`'items.here'` can be an array or a configuration key (that contains an array), there you will specify the menu items, for example:

```
[
	'home' => ['url' => ''],
	'about' => ['title' => 'Who we are', 'url' => 'about-us'],
	'contact-us' => ['full_url' => 'http://contact.us']
]
```

Each item in the array will be a menu item, the array key is required and will be used to generate default options, each menu item value needs to be an array of options (all of them optional).

You can specify the following options for each menu item:

## URL

Of course this is the most important part of each menu item, and therefore you have several options to specify an URL:

### full_url

If you pass a 'full_url' key within the item configuration, it will return it as the URL with no additional action, i.e.:

`['full_url' => 'https://styde.net']`

### url

You can pass a relative URL, using the 'url' key. The final URL will be generated using the method `UrlGenerator::to`, i.e.:

`['url' => 'contact-us']`

You can also pass a 'secure' key to indicate whether this particular URL should use https or not. You can also specify a default secure value using the `setDefaultSecure` method (false by default).

`['url' => 'login', 'secure' => 'true']`

### route

You can specify a route's name for a menu item.

`['route' => 'home']`

### route with parameters

You can specify a route with parameters if you pass an array instead of a string as the value of the 'route' key.

The first value will be taken as the route's name, and the others will be the route's parameters.

`['route' => ['profile', 'sileence']]`

### action

You can specify an action for a menu item

### action with parameters

You can specify an action with parameters if you pass an array instead of a string as the value of the 'action' key.

The first value will be the action, and the others will be the action parameters.

### default placeholder

If none of above options is found, then the URL will simply be a placeholder "#".

### Dynamic parameters

Sometimes you will need to use dynamic parameters to build routes and actions, in that case, instead of a value, pass a name precede with :, for example:

`['route' => ['profile', ':username']]`

Then you can assign a value using the setParams or setParam method, like this:

`{!! Menu::make('config.items')->setParam('username', 'sileence') !!}`

Or this:

`{!! Menu::make('config.items')->setParams(['username' => 'sileence']) !!}`

## title

You can specify a title for a menu item using the 'title' key in the options array, i.e.:

`['title' => 'Contact me']`

If no title is set and you are using the translate texts option, it will search for a lang key for the menu item, following this convention: `menu.[key]`, for example:

```
[
    'home' => ['url' => '/']
]
```

As no title is set, it will search for the `menu.home` language key

If neither the title option or the menu key is found, the component will generate a title based on the menu key. i.e.: 'home' will generate 'Home', 'contact-us' will generate 'Contact us', etc.

[Learn more about translate texts option](internationalization.md)

## id

The menu's item key will be used as the menu's item HTML id attribute by default. In case you need to override this behaviour, you can pass an 'id' option.

## submenu

You can specify a sub-menu key and pass another array of menu items, like this:

```
[
    'home' => [],
    'pages' => [
        'submenu' => [
            'about' => [],
            'company' => ['url' => 'company']
        ]
    ]
]
```

The sub-menu items will be rendered with the same options and fallbacks as the menu items.

## active option

All menu items will have the active value set to false as default, unless the URL of a menu item or sub-item has the same or partial base value than the current URL.

For example: 

```
[
    'news' => ['url' => 'news/']
]
```

Will be considered the active URL if the current URL is news/ or news/some-slug.

## CSS classes

You can pass CSS classes for a particular menu item using the 'class' option.

The active item will also get the 'active' class, and the items with sub-menus will get the 'dropdown' class.

You can customize these classes using:

```
{!! Menu::make('items')
        ->setActiveClass('Active')
        ->setDropDownClass('dropdown') !!}
```

## Render menus and custom templates

The menu will be rendered automatically if you treat `Menu::make` as a string, but you can also call the render method which accepts an optional custom template as an argument, like this:

`{!! Menu::make('menu.items')->render('custom-template') !!}`

## Access handler

It is often useful to show or hide options for guest or logged users with certain roles, you can do this using the Access Handler included in this component:

[Learn more about the access handler](access-handler.md)