# Access handler

Sometimes you want to show or hide certain menu items, form fields, etc. for certain users, with this component you can do it without the need of conditionals or boiler plate code.

This package include a `BasicAccessHandler` class but you can build your own `AccessHandler` implementation.

## BasicAccessHandler

Just pass one of the following options as a field's attributes or menu item values.

1. callback: should return true if access is granted, false otherwise.
2. logged: true: requires authenticated user, false: requires guest user.
3. role: true if the user has any of the required roles.
4. By default, this will return true (grant access).

i.e.: 

`{!! Field::select('user_id', null, ['role' => 'admin'])`

`{!! Menu::make('menu.items') !}}`

```
// config/menu.php

return [
    'items' => [
        'account' => [
            'logged' => true
        ],
        'login' => [
            'logged' => false
        ],
        'settings' => [
            'role' => 'admin'
        ]
    ]
];
```
     
## Customization

If you are working on a complex project with lots of different access rules, you may need to implement your own AccessHandler

You only need to implement the `Styde\Html\Access\AccessHandler` interface, extend the HtmlServiceProvider and override the `registerAccessHandler` method.

## Standalone

If you want to use the access handler class as a standalone component, please add this alias in `config/app.php`

```
  'aliases' => [
    // ...
    'Access' => Styde\Html\Facades\Access,
    // ...
  ],
```

Then you can use the facade wherever you want:

```
@if (Access:check(['role' => ['admin, 'editor']]))
    <p>
        <a href='{{ url('admin/posts', [$post->id]) }}'>
            Edit this page
        </a>
    </p>
@endif
```