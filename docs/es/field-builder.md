# Field (FieldBuilder)

Este componente permitirá generar el markup completo para los campos del formulario con una línea de código.

Si has usado antes el componente HTML de Laravel Collective, ya sabes cómo utilizar los conceptos básicos de este componente; simplemente reemplaza el alias “Form” por “Field”, por ejemplo, sustituye:

`{!! Form::text(‘name’, ‘value’, $attributes) !!}`

Por esto:

`{!! Field::text(‘name’, ‘value’, $attributes) !!}`

Esto generará un campo incluyendo el container, el label, el control y cualquier error asociado con él.

Los campos serán generado con el markup predeterminado de **Twitter Bootstrap** (pero también se puede publicar y personalizar las plantillas).

Igualmente hay un montón de opciones convenientes:

##Omitir el argumento value

Si no quieres pasar un argumento value (`null`) pero quiere pasar el array de `$attributes`, se puede saltar el segundo argumento, es decir, esto:

`{!! Field::text(‘name’, $attributes) !!}`

Es lo mismo que:

`{!! Field::text(‘name’, null, $attributes) !!}`


##Labels:

Se puede explicítamente pasar un label a un campo como parte del array de atributos, es decir: 

`{!! Field::text(’name’, [‘label’ => ‘Full name’]) !!}`

Como una segunda opción, se puede almacenar los labels en la carpeta lang/ con la misma convención usada para almacenar los nombres de los atributos para los errores de validación:

validation.atributos.[nombre_del_atributo].

(De esta manera se puede almacenar todas los labels del formulario en un solo lugar)

Si saltas ambas opciones, entonces FieldBuilder generará un label basado en el nombre del campo, es decir:

`full_name` se mostrará "Full name" como el label predeterminado.

##Templates

Por defecto, los campos serán renderizados con la plantilla predeterminada, ubicada en la carpeta [theme]/fields, por ejemplo, para el tema Bootstrap sería:

`vendor/styde/html/themes/bootstrap/fields/default.blade.php`

Pero se tiene la opción de personalizar la plantilla usada para un tipo o campo particular: 

###Personalizar por tipo

Algunos frameworks de CSS (como Bootstrap) necesitan diferentes markups para distintas tipos de campos, así que para utilizar la configuración que asigna una plantilla diferente a un tipo de campo determinado, se hace algo como esto:

```
	'themes' => [
		'bootstrap' => [
			'field_templates' => [
				'checkbox' => 'checkbox',
				'checkboxes' => 'collection',
				'radios' => 'collection'
			],
			//...
	//...
```

Con esta configuración los campos "checkbox" usarán la plantilla `vendor/styde/html/themes/bootstrap/fields/checkbox.blade.php` por defecto, mientras que los campos "checkboxes" y "radios" utilizará la plantilla `vendor/styde/html/themes/bootstrap/fields/collection.blade.php`.

Como puedes ver, la configuración es para este theme en específico, ya que cada framework de CSS tiene especificaciones diferentes.

Nota: sólo tienes que preocuparte por el theme que realmente necesitas, por lo que si no planeas usar Bootstrap, puedes borrar/omitir la configuración `bootstrap`

###Personalizar un campo determinado

Puedes especificar una `template` personalizada para un solo campo a través de `'template key'` del array `$attributes`, así:

`{!! Field::text(’name’, [’template’ => ’templates/my_template’]) !!}`

La ruta será relativa al directorio resources/views/

###Personalización de plantillas predeterminadas

Si quieres personalizar las plantillas predeterminadas, sólo ejecuta `php artisan vendor:publish` en la consola y todas las plantillas serán copiadas a la carpeta `resources/views/themes/[theme]/fields/`

De otra manera, el paquete usará las plantillas predeterminadas (almacenadas en `/vendor/styde/html/themes/`) y no será necesario copiar archivos adicionales dentro del proyecto.

##Atributo name

Puedes usar la notación de punto como nombre del campo, por ejemplo: `profile.twitter` y se transformará a `profile[twitter]`

##Atributo id

Éste se asignará automáticamente para control de cada input, si utilizas la notación de punto (ejemplo: user.name) los puntos serán reemplazados por guiones bajos (ejemplo: user_name)

##Atributo required

Puedes especificar un valor 'required' en el array de atributos:

`{!! Field::text(’name’, [’required’]) !!}`

O como un par llave => valor (el campo será marcado como `required` si el valor se evalúa como true, es decir:

`$required = true;`

`{!! Field::text(’name’, null, [’required’ => $required]) !!}`

Las plantillas de campo siempre tendrán una variable `required` por lo que pueda ser usado para imprimir clases de CSS adicionales o badges, para indicar si un campo es necesario u opcional, es decir:

```
    @if ($required)
        <span class="label label-info">Required</span>
    @endif
```

##Errores:

Cualquier error de sesión será cargado en el FieldBuilder a través de `HtmlServiceProvider` y se tendrá `$errors` específicos por cada campo disponible en la plantilla, también se tendrá una variable `$hasErrors` en caso que el campo tenga algún error y se necesite imprimir una clase de CSS adicional, etc.

Por ejemplo, con Twitter Bootstrap se necesitará una clase `has-error` en caso que se quiera que los campos del formulario con errores sean coloreados en rojo (tema de UX).

Este es un extracto de una plantilla personalizada para el theme Bootstrap:

`<div{!! Html::classes(['form-group', 'has-error' => $hasErrors]) !!}>`

Los inputs, selects, textareas, etc. con errores también tendrán una clase de CSS adicional que se puede configurar de esta manera:

```
    'themes' => [
        'bootstrap' => [
            //...
            'field_classes' => [
            		//...
                'error' => 'input-with-feedback'
                //...
```

Una vez más, si estás usando Twitter Bootstrap cualquier campo con errores tendrá la clase `input-with-feedback`. Esto también es necesario para mostrar el input en color rojo.

##Options

Para selects, radios and checkboxes, se puede omitir los argumentos de las opciones o pasar `null`:

`{!! Field::select('os') !!}` or `{!! Field::select('os', null) !!}`

Si existe un modelo vinculado al formulario, entonces el FieldBuilder verificará si hay un método llamado: `get[fieldName]Options`, en ese caso, será llamado y devolverá los valores a ser utilizados como las opciones, es decir:

```
class Product extends Model 

    //...
    
    public function getOsOptions()
    {
        return ['osx', 'linux', 'windows'];
    }
    
    //...
```

##Opción empty

Los campos select frecuentemente necesitan una opción empty (como "Selecciona una opción, por favor") que se puede pasar con el atributo `'empty'` de esta manera:

`{!! Field::select('os', null, ['empty' => 'Selecciona tu sistema operativo favorito']) !!}`

Si no se pasa el atributo `'empty'`, entonces el componente buscará uno usando el componente traslator.

Primero, buscará un texto empty personalizado según el nombre del campo, siguiendo esta convención: `"validation.empty_option.[field_name]"`

Si no se encuentra ninguno, se buscará la opción empty por defecto: `"validation.empty_option.default"`

En último caso, si ninguna de las opciones es encontrada, se usará un string vacío como opción empty.

##Abreviaturas

Para ahorrar algunas pulsaciones de teclas, puedes utilizar abreviaturas en lugar del nombre completo de los atributos, pasándolos en la configuración:

```
    /*
     * Especifica las abreviaturas para los atributos del campo del formulario
     */
    'abbreviations' => [
        'ph' => 'placeholder',
        'max' => 'maxlength',
        'tpl' => 'template'
    ],
```

Después se podrán hacer cosas como éstas:

`{!! Field::text('name', ['ph' => 'Esto será el placeholder]) !!}`

##Clases de CSS

Se puede pasar clases de CSS personalizadas para cada campo usando la llave 'class' del array de atributos, también se pueden agregar clases adicionales:

###Clases predeterminadas (por tipo)

Utilizando la configuración, se puede asignar clases de CSS predeterminadas para cada campo según su tipo:

```
    'themes' => [
			//...
        'bootstrap' => [
            //...
            'field_classes' => [
                // tipo => clase o clases de CSS
                'default' => 'form-control',
                'checkbox' => '',
                'error' => 'input-with-feedback'
            ],
```

Por supuesto, esto es para cada theme en específico, debido a que es imposible convencer a todos los autores de frameworks de CSS de usar las mismas clases.

###Clases de CSS para controls con errores

Si un input tiene errores, una clase de CSS adicional llamada `error` se agregará, también puede ser configurada para cada theme (véase más arriba).

##Control de acceso

Es posible que desees ocultar algunos campos para ciertos usuarios, esto se puede hacer usando el Access Handler incluído con este componente:

[Aprender más sobre el access handler](access-handler.md)
