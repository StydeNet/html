# Access handler

Algunas veces quieres mostrar o esconder menu items, form fields, etc. para ciertos usuarios, con este componente se puede hacer sin necesidad de condicionales o código boilerplate.

Este paquete incluye una clase `BasicAccessHandler` pero puedes construir tu propia implementación de `AccessHandler`.

## BasicAccessHandler

Sólo pasa una de las siguientes opciones como un atributo de campo o valor del menu item:

1. callback: debe devolver true si se quiere dar acceso, false en caso contrario.
2. logged: true: requiere que el usuario esté autenticado, false: requiere usuario invitado.
3. roles: true si el usuario tiene uno de los roles requeridos.
4. Si ninguna opción es pasada, éste devolverá true (el item será renderizado).

*WARNING*: tomar en cuenta que este paquete sólo desactivará la opción visual, aún se necesita proteger el acceso de backend usando middleware, etc.

Ejemplos: 

####Form fields

`{!! Field::select('user_id', null, ['role' => 'admin'])`

####Menu items

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
     
`{!! Menu::make('menu.items') !}}`
     
## Personalización

Si estás trabajando en un proyecto complejo con muchas reglas de acceso diferentes, etc. puedes necesitar implementar un  AccessHandler propio. Para hacer esto, crea una nueva clase que implemente la interface `Styde\Html\Access\AccessHandler`, entonces extiende el HtmlServiceProvider y sustituye el método `registerAccessHandler`.

## Standalone

Si se quiere usar la clase del access handler como un componente independiente, por favor agrega este alias global en `config/app.php`.

```
  'aliases' => [
    // ...
    'Access' => Styde\Html\Facades\Access,
    // ...
  ],
```

Luego se puede utilizar la facade donde se quiera:

```
@if (Access:check(['role' => ['admin, 'editor']]))
    <p>
        <a href='{{ url('admin/posts', [$post->id]) }}'>
            Editar esta página
        </a>
    </p>
@endif
```

##Desactivar el access handler

Se puede desactivar este componente en la configuración:

```
//config/html.php
return [
    //..
    'control_access' => false,
    //..
];
```

Haciendo esto, los atributos callback, logged y role serán simplemente ignorados.
