# Internationalization

You can configure whether you want this package to attempt to translate texts or not, for example if your project only needs to implement one language and you prefer to simply write texts wherever you need them instead of using the Translator component, please deactivate translations in the configuration:

```
//config/html.php
return [
  //...
  'translate_texts' => false
  //...
];
```

But if your project needs to implement more than one language or you want to organize all the texts in one place instead of hardcoding them in the controllers, views, etc. leave `'translate_texts'` as `true`.

## Translating alert messages

If the `'translate_texts'` is set to `true`, this component will assume that all the alert messages are in fact language keys and will try to translate them. This means you can do things like this:

```
Alert::success('messages.users.updated')
		->button('messages.users.go_to_profile', url('users/profile'))
```
Of course is the lang key is not found, this will return the default text.

[Learn more about the alert component](docs/alert-messages.md)

## Translating menu items

If the `'translate_texts'` is set to `true`, and you don't specify an explicit title for a menu item; the component will search for a lang key in: `menu.[menu_item_key]` if this key is not found, the package will attempt to convert the menu item key in a title format. For example:

```
//resources/lang/en/menu.php
return [
    'home' => 'Homepage'
];
```

```
//config/menu.php
return [
    'items' => [
        'home'  => [],
        'about' => ['title' => 'Who we are'],
        'contact-us' => []
    ]
];
```

`{!! Menu::make('menu.items') !!}`

Will return something like:

```
<ul>
    <li><a href="#">Homepage</a></l
    <li><a href="#">Who we are</a></li>
    <li><a href="#">Contact us</a></li>
</ul>
```

Notice that:
 
* "Homepage" is taken from the menu key "menu.home".
* "Who we are" is explicit defined (not translation is attempted)
* "Contact us" is generated from the key "contact-us" (since no lang key is provided)

[Learn more about the menu generator](docs/menu-generator.md)