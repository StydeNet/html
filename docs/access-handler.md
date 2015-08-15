# Access handler

Sometimes you want to show or hide menu items, form fields, etc. for certain users, with this component you can do it without the need of conditionals or boilerplate code.

This package include a `BasicAccessHandler` class but you can build your own `AccessHandler` implementation.

## BasicAccessHandler

Just pass one of the following options as a field attribute or menu item value:

1. *callback*: should return true if you want to grant access, false otherwise.
2. *logged*: true: requires authenticated user, false: requires guest user.
3. *roles*: true if the user has any of the required roles.
4. If no option is passed, this will return true (the item will be rendered)

*WARNING*: note this package will only prevents the elements from appearing in the front end, you still need to protect the backend access using middleware, etc.

Examples: 

#### Form fields

`{!! Field::select('user_id', null, ['role' => 'admin'])`

#### Menu items

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
            'roles' => 'admin'
        ]
    ]
];
```
     
`{!! Menu::make('menu.items') !}}`
     
## Customization

If you are working on a complex project with lots of different access rules, etc. You may need to implement your own AccessHandler, in order to do this, create a new class that implements the `Styde\Html\Access\AccessHandler` interface, then extend the HtmlServiceProvider and override the `registerAccessHandler` method.

## Standalone

If you want to use the access handler class as a standalone component, please add this global alias in `config/app.php`

```
  'aliases' => [
    // ...
    'Access' => Styde\Html\Facades\Access,
    // ...
  ],
```

Then you can use the facade wherever you want:

```
@if (Access:check(['roles' => ['admin, 'editor']]))
    <p>
        <a href='{{ url('admin/posts', [$post->id]) }}'>
            Edit this page
        </a>
    </p>
@endif
```

## Deactivate the access handler

You can deactivate this component in the configuration:

```
//config/html.php
return [
    //..
    'control_access' => true,
    //..
];
```
By doing this, the callback, logged and roles attributes will simply be ignored and all users will be able to see all items. 