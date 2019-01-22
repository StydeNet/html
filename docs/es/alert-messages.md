# Mensajes de Alerta

Este componente permitirá generar mensajes de alerta complejos.

```php
Alert::info('Su cuenta está a punto de caducar')
    ->details('Renueva ahora para aprender acerca de:')
    ->items([
        'Laravel',
        'PHP',
        'y más',
    ])
    ->button('¡Renueva ahora!', '#', 'primary');
```

Los mensajes serán persistentes en la sesión hasta que sean presentados al usuario con:

```blade
{!! Alert::render() !!}
```

## Crear un nuevo mensaje de alerta

Se puede generar un nuevo mensaje de alerta con: 

```blade
{!! Alert::message('Este es el mensaje', 'tipo-alerta') !!}
```

El primer argumento es el texto del mensaje y el segundo es el tipo de alerta.

Por ejemplo:

```blade
{!! Alert::message('El final está cerca', 'danger') !!}
```

También se puede usar métodos mágicos, el nombre del método se convierte en el tipo de alerta:

```blade
{!! Alert::success("Está todo bien ahora") !!}
```

## Encadenamiento de métodos

Se puede especificar más opciones por encadenamiento de métodos:

### Detalles

Se puede pasar uno o más mensajes detallados encadenando el método details():

```blade
{!! Alert::info('Algo de información')->details('Una explicación más detallada va aquí') !!}
```

### Llamadas de acción

Se puede asignar botones a un mensaje de alerta: 

```blade
{!! Alert::info()->button('Llamada de acción', 'alguna-url', 'primary') !!}
```

### HTML

Se puede pasar HTML directamente a un mensaje de alerta: 

```blade
{!! Alert::info()->html('<strong>El HTML va aquí</strong>') !!}
```

Tenga cuidado ya que esto no será escapado.

### Vista

Se puede incluso renderizar una vista dentro de un mensaje de alerta:

```blade
{!! Alert::info()->view('partials/alerts/partial') !!}
```

### Elementos

Se puede pasar un array de elementos (tal vez una lista de errores):

```blade
{!! Alert::danger('Por favor corrija los siguientes errores')->items($errors) !!}
```

## Persistir los mensajes de alerta

Agrega el siguiente middleware al array `$middleware` en `app/Http/Kernel.php` **ANTES** de `\App\Http\Middleware\EncryptCookies`: 

```php
protected $middleware = [
    //...
    \Styde\Html\Alert\Middleware::class
    //...
];
```

Se necesita este middleware para persistir los mensajes de alerta después de que se complete cada petición. 

Por defecto, los mensajes de alerta serán persistidos usando el componente session de Laravel. Pero también se puede crear una implementación propia. 

## Traducciones

Si la opción `'translate_texts'` está definida como true en la configuración (es true por defecto), el componente de alerta intentará traducir todos los mensajes, utilizando el valor de la llave `$message`, pero si esta llave de idioma no es encontrada, devolverá el string literal.
 
Si no se necesita utilizar el componente Traductor, sólo define translate_texts como false en la configuración:

```php
//config/html.php

return [
    //...
    'translate_texts' => false
    //...
];
```

## Temas

Por defecto, los mensajes de alerta serán renderizados con la plantilla predeterminada, localizada en themes/[nombre-del-tema]/alert, por ejemplo, para el tema de Bootstrap sería: `vendor/styde/html/themes/bootstrap/alert.blade.php`

Se puede pasar una plantilla personalizada como el primer argumento del método render(), es decir:

```blade
{!! Alert::render('partials/custom-alert-template') !!}
```
