# Temas

Este paquete fue creado teniendo en cuenta que hay un montón de frameworks de CSS por ahí (de todo tipo) y aunque *Bootstrap* (versión 3 y 4) y *Bulma* sean incluidos por defecto, se planea agregar más temas en el futuro (te invitamos a colaborar).

## Cambiar el tema

El tema Bootstrap 4 theme está definido por defecto, pero tu puedes ir a `config/html.php` y cambiar el valor del tema:
```php
//config/html.php

return [
    'theme' => 'bulma',
];
```
>Nota: `bootstrap` es para Bootstrap versión 3, `bootstrap4` es para Bootstrap versión 4 and `bulma` es para Bulma CSS versión 0.7.2.

## Personalización

 También puedes crear temas propios, publicarlos y personalizarlos, si es necesario.

Para cambiar o personalizar un theme, simplemente ejecuta: 

```bash
php artisan vendor:publish
```

Luego ir a `config/html.php` y cambiar el valor de theme:

```php
//config/html.php

return [
    'theme' => 'custom-theme'
];
```

Después crea una carpeta en `resources/views/themes/` llamada 'custom-theme', para ahorrar algo de tiempo, puedes copiar la carpeta `bootstrap/` y pegarla como 'custom-theme'.

Si es necesario puedes cambiar todas las plantillas dentro de ese directorio o agregar nuevas. 

### Personalizar plantillas individuales 

Quizás no necesites crear o usar un nuevo tema y simplemente necesitas sustituir una plantilla determinada; esto se puede hacer también, debido a que la mayoría de los métodos lo soporta, por ejemplo:

```blade
{!! Menu::make('menu.items')->render('custom-template') !!}
```

```blade
{!! Alert::render('custom-template') !!}
```

```blade
{!! Field::email('email', ['template' => 'custom-template'])
```

### Personalizar plantillas por tipo de campo (fieldBuilder)

¿Estás usando un framework de CSS que requiere un markup diferente para un tipo de campo determinado? No te preocupes, solo lee la sección de "Personalizar por tipo" de la [página field builder](field-builder.md)

## Pull requests

Si creas un tema para un framework de CSS popular, puedes colaborar haciendo fork de este repositorio y crear un pull request, recuerda guardar las plantillas en la carpeta `themes/` y actualizar el archivo `config.php`.

Puedes probar tu tema usando este repositorio: [https://github.com/StydeNet/html-integration-tests](https://github.com/StydeNet/html-integration-tests).

Gracias.
