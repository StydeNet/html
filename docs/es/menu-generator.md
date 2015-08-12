# Menus (MenuGenerator)

Los menús no son elementos estáticos, frecuentemente necesitas marcar la sección activa, traducir items, generar URLs dinámicas o mostras/ocultar opciones sólo para ciertos usuarios.

Así que en lugar de agregar una gran cantidad de código boilerplate HTML y Blade, puedes utilizar este componente para generar menús dinámicos con estilos para el framework de CSS en uso.

Para generar un menú simplemente agrega el siguiente código en la plantilla de tu diseño:

`{!! Menu::make('items.aqui', 'clases css opcionales') !!}`

`'items.aqui'` puede ser un array o una llave de configuración (que contiene un array), donde se especificarán los menu items, por ejemplo:

```
[
	'home' => ['url' => ''],
	'about' => ['title' => 'Who we are', 'url' => 'about-us'],
	'contact-us' => ['full_url' => 'http://contact.us']
]
```

Cada item en el array será un menu item, la llave del array es obligatoria y será usada para generar algunas opciones predeterminadas, cada valor de un menu item necesita ser un array de opciones (todas ellas son opcionales).

Puedes especificar las siguientes opciones para cada menu item:

## URL

Por supuesto, esta opción es la parte más importante de cada menu item y por tanto, tiene varias opciones para especificar una URL:

###full_url

Si pasas la llave 'full_url' dentro de la configuración del item, éste lo devolverá como la URL sin ninguna acción adicional, es decir:

`['full_url' => 'https://styde.net']`

###url

Puedes pasar una URL relativa usando la llave 'url'. El URL resultante será generado usando el método `UrlGenerator::to`, es decir:

`['url' => 'contact-us']`

También puedes pasar una llave 'secure' para indicar si ese URL particular debe utilizar https o no. Igualmente puedes especificar un valor secure predeterminado usando el método `setDefaultSecure` (false por defecto).

`['url' => 'login', 'secure' => 'true']`

###route

Especifica el nombre de una ruta para un menu item: 

`['route' => 'home']`

###route con parámetros

Puedes establecer una ruta con parámetros pasando un array en vez de un string como el valor de la llave 'route'.

El primer valor será tomado como el nombre de la ruta y los otros serán los parámetros de la ruta.

`['route' => ['profile', 'sileence']]`

###action

Especifica una acción para un menu item. 

###action con parámetros

Puedes establecer una acción con parámetros pasando un array en vez de un string como valor de la llave 'action'.

El primer valor será la acción y los otros serán los parámetros de la acción.

###default placeholder

Si ninguna de las opciones anteriores es encontrada, entonces el URL será simplemente un placeholder "#".

###Parámetros dinámicos

Algunas veces necesitarás utilizar parámetros dinámicos para construir rutas y acciones, para ese caso, en vez de un valor se pasa un nombre precedido por :, por ejemplo:

`['route' => ['profile', ':username']]`

Después puedes asignar un valor usando los métodos `setParam` or `setParams`, así: 

`{!! Menu::make('config.items')->setParam('username', 'sileence') !!}`

o de esta manera:

`{!! Menu::make('config.items')->setParams(['username' => 'sileence']) !!}`

##title

Especifica un título para un menu item usando la llave 'title' en el array de opciones, es decir:

`['title' => 'Contact me']`

Si el título no es definido y estás usando la opción `translate_texts`, buscará la llave de idioma para el menu item, siguiendo esta convención: `menu.[key]`, por ejemplo: 

```
[
    'home' => ['url' => '/']
]
```

En este caso, como title no está definido, buscará la llave de idioma para `menu.home`. 

Si no se encuentra ni la opción title o la llave de idioma, el componente generará un título basado en la llave del menu, es decir, 'home' generará 'Home', 'contact-us' generará 'Contact us', etc.

[Aprender más sobre traducir textos](internationalization.md)

##id

La llave del menu item será utilizada por defecto como atributo id del elemento HTML del menú. En caso de necesitar sustituir este comportamiento se puede pasar la opción 'id'.

##submenu

Se puede especificar una llave submenu y pasar otro array de menu items, así:

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

Los items del sub-menu serán renderizados con las mismas opciones y fallbacks que el menu item.

##active 

Todos los menu items tendrán establecido el valor active en false por defecto, a menos que la URL de un menu item o sub-menu tenga el mismo o parcial valor que la URL actual. 

Por ejemplo: 

```
[
    'news' => ['url' => 'news/']
]
```

Será considerada la URL activa si el actual URL es news/ o news/algun-slug

##CSS classes

Puedes pasar clases de CSS para un menu item determinado usando la opción 'class'.

El item activo también tendrá la clase 'active' y los items con sub-menus tendrán la clase 'dropdown'.

Puedes personalizar estas clases usando: 

```
{!! Menu::make('items')
        ->setActiveClass('Active')
        ->setDropDownClass('dropdown') !!}
```

##Renderizar menús and plantillas personalizadas

Si tratas `Menu::make` como un string, el menú será renderizado automáticamente, pero también se puede llamar el método `render`, el cual acepta como argumento opcional una plantilla personalizada, así:

`{!! Menu::make('menu.items')->render('custom-template') !!}`

##Access handler

Muchas veces es útil mostrar o esconder opciones para usuarios invitados o registrados con ciertos roles, para ello se puede usar el Access Handler incluído en este componente:

[Aprender más sobre el access handler](access-handler.md)