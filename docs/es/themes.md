# Themes

Este paquete fue creado teniendo en cuenta que hay un montón de frameworks de CSS por ahí (de todo tipo) y aunque *Twitter Bootstrap* sea incluido por defecto, se planea agregar más themes en el futuro (te invitamos a colaborar). También puedes crear themes propios, publicarlos y personalizarlos, si es necesario.

Para cambiar o personalizar un theme, simplemente ejecuta: 

`php artisan vendor:publish`

Luego ir a `config/html.php` y cambiar el valor de theme:

```
//config/html.php
return [
    'theme' => 'custom-theme'
];
```

Después crear una carpeta en `resources/views/themes/` llamada 'custom-theme', para ahorrar algo de tiempo, puedes copiar la carpeta `bootstrap/` y pegarla como 'custom-theme'.

Si es necesario puedes cambiar todas las plantillas dentro de ese directorio o agregar nuevas. 

## Personalizar plantillas individuales 

Quizás no necesites crear o usar un nuevo theme y simplemente necesitas sustituir una plantilla determinada; esto se puede hacer también, debido a que la mayoría de los métodos lo soporta, por ejemplo:

`{!! Menu::make('menu.items')->render('custom-template') !!}`

`{!! Alert::render('custom-template') !!}`

`{!! Field::email('email', ['template' => 'custom-template'])`

## Personalizar plantillas por tipo de campo (field builder)

¿Estás usando un framework de CSS que requiere un markup diferente para un tipo de campo determinado? No te preocupes, solo lee la sección de "Personalizar por tipo" de la [página field builder](field-builder.md)

## Pull requests

Si creas un tema para un framework de CSS popular, puedes colaborar haciendo fork de este repositorio y crear un pull request, recuerda guardar las plantillas en la carpeta `themes/`. Gracias.
