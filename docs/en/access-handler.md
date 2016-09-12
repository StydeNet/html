# Access handler

Sometimes you want to show or hide menu items, form fields, etc. for certain users, with this component you can do it without the need of conditionals or boilerplate code.

This package include a `BasicAccessHandler` class but you can build your own `AccessHandler` implementation.

## BasicAccessHandler

Just pass one of the following options as a field attribute or menu item value:

1. *callback*: should return true if you want to grant access, false otherwise.
2. *logged*: true: requires authenticated user, false: requires guest user.
3. *roles*: true if the user has any of the required roles.
4. *allows*: uses the Gate::allows method 
5. *check*: uses the Gate::check method (alias of allow)
6. *denies*: uses the Gate::denies method
7. If no option is passed, this will return true (the item will be rendered)

*WARNING*: note this package will only prevents the elements from appearing in the front end, you still need to protect the backend access using middleware, etc.

##Usage 

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

## Gate authorization

The allows, check and denies options accept a string or an array as a value.

If it is an string, it will be the name of the ability with no arguments.

If it is an array, the first position of the array will be the name of the ability, and the others will be the arguments 

Examples:

`{!! Field::text('change-password', ['allows' => 'change-password']) !!}`
`{!! Field::select('category', $options, ['allows' => ['change-post-category', $category]]) !!}`

If you are building menus, you can use dynamic parameters to pass values to the gate.

In the following example we will define a dynamic 'post' parameter, and pass it using setParam when building the menu:

```
// config/menu.php

return [
    'items' => [
        'view-post' => [],
        'edit-post' => [
            'allows' => ['update-post', ':post']
        ]
    ]
];
```
     
`{!! Menu::make('menu.items')->setParam('post', $post)->render() !}}`
     
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