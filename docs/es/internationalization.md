# Internacionalización

Puedes configurar si quieres usar este paquete para tratar de traducir los textos o no, por ejemplo si tu proyecto sólo necesita ser implementado en un idioma y prefieres simplemente escribir los textos donde los necesites en lugar de utilizar el componente Translator, desactiva las traducciones en la configuración:

```php
//config/html.php
return [
  //...
  'translate_texts' => false
  //...
];
```

Pero si tu proyecto necesita ser implementado en más de un idioma o quieres organizar todos los textos en un solo lugar en vez de ponerlos en los controladores, vistas, etc. establece `'translate_texts'` en `true`.

*Nota:* El Field Builder siempre tratará de traducir los textos.

## Traducir etiquetas (field builder)

Si quieres tener un label específico en un campo, puedes hacerlo pasándolo como parte del array de atributos:
 
```blade
{!! Field::text('name', ['label' => 'Nombre completo']) !!}
```
 
También puedes definirlo como parte del array `attributes`en el archivo `resources/lang/en/validation.php`:
 
```php
     //resources/lang/en/validation.php
     //..
     'attributes' => [
         'name' => 'Nombre completo'
     ],
```

Toma en cuenta que esto también es una convención usada por el componente Laravel Validator, de esta manera puedes tener todos los textos de los labels en un mismo lugar.  

[Aprender más sobre field builder](field-builder.md)

## Traducir mensajes de alerta

Si `'translate_texts'` es definido como `true`, este componente asumirá que todos los mensajes de alerta son de hecho llaves de idioma e intentará traducirlas. Es decir, puedes hacer cosas como:

```blade
{!! Alert::success('messages.users.updated')
		->button('messages.users.go_to_profile', url('users/profile')) !!}
```

Por supuesto, si la llave de idioma no es encontrada, éste devolverá el string literal (también puesdes pasar el mensaje completo en lugar de una llave de idioma).

[Aprender más sobre el componente alert](alert-messages.md)

## Traducir elementos de menú

Si `'translate_texts'` es definido como `true`, pero no específicas un título explícito para un menu item; el componente buscará un llave de idioma en: `menu.[llave_menu_item]` si la llave no es encontrada, el paquete intentará convertir la llave del menu item en un formato de título. Por ejemplo:

```php
//resources/lang/en/menu.php
return [
    'home' => 'Homepage'
];
```

```php
//config/menu.php
return [
    'items' => [
        'home'  => [],
        'about' => ['title' => 'Who we are'],
        'contact-us' => []
    ]
];
```

```blade
{!! Menu::make('menu.items') !!}
``

Devolverá algo así:

```blade
<ul>
    <li><a href="#">Homepage</a></li>
    <li><a href="#">Who we are</a></li>
    <li><a href="#">Contact us</a></li>
</ul>
```

Tomar en cuenta que:
 
* "Homepage" es tomado desde la llave del menu "menu.home".
* "Who we are" es definido explícitamente (no se intenta traducir)
* "Contact us" es generado desde la llave "contact-us" (debido a que la clave de idioma no está definida)

[Aprender más sobre el generador de menús](menu-generator.md)