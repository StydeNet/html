# StydeNet Html package

Este paquete contiene una colección de clases de PHP Laravel diseñado para generar componentes de HTML comunes, tales como:

* Menús
* Mensajes de alerta
* Form fields
* Colección de radios y checkboxes

Éste es una extensión del [paquete HTML de Laravel Collective](https://github.com/laravelcollective/html) y será útil si estás trabajando en un CMS personalizado, un panel de administración o básicamente cualquier proyecto que necesite generar HTML dinámicamente.

## Cómo instalar 

1. La mejor forma de instalar este paquete es a través de Composer.

**Para Laravel 5.4**:

Instala ejecutando `composer require "styde/html=~1.3"` o agregando `"styde/html": "~1.3"` a tu archivo `composer.json` y luego ejecuta `composer update`.

**Para Laravel 5.3**:

Instala ejecutando `composer require "styde/html=~1.2"` o agregando `"styde/html": "~1.2"` a tu archivo `composer.json` y luego ejecuta `composer update`.

**Para Laravel 5.2**:

Instala ejecutando `composer require "styde/html=~1.1"` o agregando `"styde/html": "~1.1"` a tu archivo `composer.json` y luego ejecuta `composer update`.

**Para Laravel 5.1**:

Instala ejecutando `composer require "styde/html=~1.0"` o agregando `"styde/html": "~1.0"` a tu archivo `composer.json` y luego ejecuta `composer update`..

2. Lo siguiente es agregar el nuevo service provider al array de `providers` en `config/app.php`

```php
'providers' => [
    // ...
    Styde\Html\HtmlServiceProvider::class,
    // ...
],
```

3. Agrega el middleware `\Styde\Html\Alert\Middleware::class` **ANTES** del middleware `EncryptCookies` que se encuentra en el array `$middleware` (si es Laravel 5.1, 5.2 y 5.3)  o en el array `$middlewareGroups` (en Laravel 5.4) del archivo `app/Http/Kernel.php` : 

```php
// Para Laravel 5.4
protected $middlewareGroups = [
    \Styde\Html\Alert\Middleware::class,
    //...
];

// Para Laravel 5.3, 5.2, 5.1
protected $middleware = [
    //...
    \Styde\Html\Alert\Middleware::class,
    //...
];
```

Este middleware es necesario para hacer que los mensajes de alerta persistan entre sesiones después que cada petición es completada.

Tomar en cuenta que los siguientes alias globales estarán automáticamente disponible (no necesitas agregarlos):

```php
Alert => Styde\Html\Facades\Alert
Field => Styde\Html\Facades\Field
Menu  => Styde\Html\Facades\Menu
Form  => Collective\Html\FormFacade
Html  => Collective\Html\HtmlFacade
```

Si planeas utilizar el _Access Handler_ como una clase independiente, necesitarás agregar el siguiente alias:

```php
'aliases' => [
    // ...
    'Access' => Styde\Html\Facades\Access::class,
    // ...
],
```

Opcionalmente, también puedes ejecutar `php artisan vendor:publish --provider='Styde\Html\HtmlServiceProvider'` para publicar el archivo de configuración en `config/html.php` de esta manera ver y configurar sus opciones y valores.

## Uso

Puesto que este paquete está usando gran parte de [LaravelCollective/Html](https://github.com/laravelcollective/html), su documentación para formularios y campos es aplicable a este paquete.

## Entorno de pruebas (Sandbox)

Este paquete tiene como objetivo estar bien documentado y testeado unitariamente; sin embargo, hay otro repositorio que incluye las pruebas de integración y varias rutas, por tanto, puedes clonarlo para ver los componentes de este paquete en acción en tu navegador o ejecutar las pruebas de integración incluídas. 

[Ver el repositorio de pruebas](https://github.com/StydeNet/html-integration-tests)

Puedes revisar los ejemplos y pruebas como otra manera, además de leer la documentación, para aprender más sobre lo que se puede hacer con este componente. 

## Configuración

Este paquete fue creado con la configuración en mente, si no has utilizado este componente antes, puedes simplemente ejecutar:

```zsh
php artisan vendor:publish --provider='Styde\Html\HtmlServiceProvider'
```
Esto publicará todas las opciones de la configuración en el archivo `config/html.php` donde puedes explorar y leer los comentarios para aprender más sobre las opciones de configuración y sus valores.
    
*Nota:* Debido a que la configuración por defecto se fusionará con la configuración personalizada, no es necesario publicar la configuración completa en cada proyecto; en cambio, sólo establece los valores que necesites sustituir.

Lee esta documentación para aprender más sobre las diferentes opciones de configuración que este paquete proporciona. 

## Form Field builder

El Field Builder permitirá renderizar el markup completamente dinámico que necesites para cada campo del formulario con solo una línea de código. 

Si has usado antes el componente HTML de Laravel Collective, ya sabes cómo utilizar los conceptos básicos de este componente; simplemente reemplaza el alias “Form” por “Field”, por ejemplo, sustituye:

```blade
{!! Form::text('name', 'value', $attributes) !!}
```

Por esto:

```blade
{!! Field::text('name', 'value', $attributes) !!}
```

[Aprender más sobre el field builder](docs/es/field-builder.md)

## Forms

Este paquete agrega la siguiente funcionalidad al Form Builder de Laravel Collective:

#### novalidate

Desactiva la validación de HTML5, ideal para entornos local o desarrollo. 

```php
//config/html.php
return [
    'novalidate' => true
];
```

#### radios

Genera una colección de radios:

i.e. 
```blade
{!! Form::radios('status', ['a' => 'Active', 'i' => 'Inactive']) !!}
```

#### checkboxes

Genera una colección de checkboxes

```php
$options = [
    'php' => 'PHP',
    'js' => 'JS'
];
$checked = ['php'];
```

```blade
{!! Form::checkboxes('tags', $options, $checked) !!}
```

[Aprender más sobre el form builder](docs/es/form-builder.md)

## Mensajes de alerta

Este componente permitirá generar complejas mensajes de alerta.

```php
Alert::info('Su cuenta está a punto de caducar')
    ->details('Renueva ahora para aprender acerca de:')
    ->items(['Laravel', 'PHP, '¡y más!'])
    ->button('¡Renueva ahora!', url('renew'), 'primary');
```

```blade
{!! Alert::render() !!}
```

[Aprender más sobre este componente](docs/es/alert-messages.md)

## Generador de menús

Los menús no son elementos estáticos, frecuentemente necesitas marcar la sección activa, traducir items, generar URLs dinámicas o mostras/ocultar opciones sólo para ciertos usuarios.

Así que en lugar de agregar una gran cantidad de código boilerplate HTML y Blade, puedes utilizar este componente para generar menús dinámicos con estilos para el framework de CSS en uso.

Para generar un menú simplemente agrega el siguiente código en la plantilla de tu diseño:

```blade
{!! Menu::make('items.here') !!}
```

[Aprender más sobre el generador de menús](docs/es/menu-generator.md)

## HTML builder

Este paquete extiende la funcionalidad del HTML Builder de the Laravel Collective.

Hay un solo método adicional _por ahora_, pero ¡es muy útil!

#### Generar clases de CSS:

```blade
{!! Html::classes(['home' => true, 'main', 'dont-use-this' => false]) !!}
```

Devuelve: ` class=“home main”`.

[Aprender más sobre el HTML builder](docs/es/html-builder.md)

### Helpers

Además de utilizar los métodos facade `Alert::message` y `Menu::make`, puedes usar:

```php
alert('this is the message', 'type-of-message')
```

`menu($items, $clases)`

## Access handler

Algunas veces quieres mostrar o esconder menu items, form fields, etc. para ciertos usuarios, con este componente se puede hacer sin necesidad de condicionales o código boilerplate. Sólo pasa una de las siguientes opciones como un atributo de campo o valor del menu item:

1. callback: debe devolver true si se quiere dar acceso, false en caso contrario.
2. logged: true: requiere que el usuario esté autenticado, false: requiere usuario invitado.
3. roles: true si el usuario tiene uno de los roles requeridos.

Es decir: 

```blade
{!! Field::select('user_id', null, ['roles' => 'admin']) !!}
```

[Aprender más sobre el access handler](docs/es/access-handler.md)

## Themes

Este paquete fue creado pensando que hay muchos frameworks de CSS por ahí, y aunque sólo Twitter Bootstrap es incluído por defecto, planeamos agregar más paquetes en el futuro (y también invitamos a colaborar).

Puedes crear tus propios temas o modificar el existente con facilidad:

Para cambiar y/o personalizar el tema, simplemente ejecuta:

```zsh
php artisan vendor:publish
```

Luego ir a `config/html.php`  y cambiar el valor de `theme`:

```php
//config/html.php
return [
    ‘theme’ => ‘tu-tema-aqui’
];
```

Puedes editar y/o crear nuevas plantillas en: `resources/views/themes/` 

[Aprender más sobre temas](docs/es/themes.md)

## Internationalización

También este paquete ha sido creado pensando en la internacionalización.

Si no planeas usar esta característica, puedes desactivar las traducciones en la configuración:

```php
//config/html.php
return [
    //…
    ‘translate_texts’ => false
    //…
];
```

Pero si tu proyecto necesita implementar más de un idioma o quieres organizar todos los textos en `resources/lang/` en vez de escribirlos directamente en los controladores, vistas, etc. establece `’translate_texts’` a `true`.

[Aprender más sobre la internationalización](docs/es/internationalization.md)

## Más documentación

Puedes encontrar gran cantidad de comentarios si te adentras en el código fuente al igual que en las pruebas unitarias en el directorio `spec/`, también puedes clonar el [repositorio de pruebas de integración](https://github.com/StydeNet/html-integration-tests).

Si tienes preguntas adicionales, no dudes en ponerte en contacto conmigo a través de Twitter ([@Sileence](https://twitter.com/sileence)) o enviándome un correo a [admin@styde.net](mailto:admin@styde.net).
