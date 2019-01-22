# Form Builder

## Novalidate

Permite a los usuarios establecer la opción `novalidate` para cada formulario generado con el método `Form::open` o el método `Form::model` así los desarrolladores pueden saltar la validación de HTML5 para evaluar la validación backend en los entornos local o de desarrollo:

```php
return [
    //..
    'novalidate' => true
    //..
];
```

## Radios

Crea una lista de radios.

Esta función es similar a `Form::select` pero genera una colección de radios en vez de opciones. Es decir:

```php
Form::radios('status', ['a' => 'Active', 'i' => 'Inactive'])
```

Se puede pasar 'inline' como un valor en el arreglo de atributos para establecer los radios en línea (ellos se renderizarán con la plantilla 'radios-inline').

## Checkboxes

Crea una lista de checkboxes.

Esta función es similar a `Form::select` pero genera una colección de checkboxes en vez de opciones, es decir:

```php
$tags = [
    'php' => 'PHP',
    'python' => 'Python',
    'js' => 'JS',
    'ruby' => 'Ruby on Rails'
];

$checked = ['php', 'js'];
```

```blade
{!! Form::checkboxes('tags', $tags, $checked) !!}
```

Se puede pasar 'inline' como un valor en el arreglo de atributos para establecer los checkboxes en línea (ellos serán renderizados usando la plantilla 'checkboxes-inline').