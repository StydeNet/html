# Field (FieldBuilder)

This component will allow you to generate the entire markup for form fields with one line of code.

If you have used the Laravel Collective HTML component before, you already know how to use the basics of this component; simply replace the alias “Form” with “Field”, for example, replace:

`{!! Form::text(‘name’, ‘value’, $attributes) !!}`

With this:

`{!! Field::text(‘name’, ‘value’, $attributes) !!}`

This will generate a field including the container, the label, the control and any errors associated with it.

The fields will be generated with the **Bootstrap** markup out of the box (but you can also publish and customize the templates).

There are also other convenient options and handy fallbacks:

## Skip value argument

If you don't want to pass a value argument (`null`) but you want to pass the array of `$attributes`, you can  skip the second argument. i.e. this:

`{!! Field::text(‘name’, $attributes) !!}`

Is the same as:

`{!! Field::text(‘name’, null, $attributes) !!}`


## Labels:

You can explicitly pass a label for a field as part of the attributes array, i.e.:

`{!! Field::text(’name’, [‘label’ => ‘Full name’]) !!}`

As a second option, you can store the labels in the lang/ folder with the same convention used to store the attribute's names for the validation errors:

validation.attributes.[name_of_the_attribute].

(This way you can store all the form labels in one place)

If you skip both options, then the FieldBuilder will generate a label based on the name of the field. i.e.:

`full_name` will show "Full name" as the default label.

## Templates

By default, the fields will be rendered with the default template, located in the [theme]/fields folder, for example, for the Bootstrap theme that would be:

`vendor/styde/html/themes/bootstrap/fields/default.blade.php`

But you have options to customize the template used for a particular type or field:

### Customize by type

Some CSS frameworks (like Bootstrap) need different markups for different form field types, so you can use the configuration to assign a different template to a particular field type, like this:

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

With this configuration, the "checkbox" fields will use the `vendor/styde/html/themes/bootstrap/fields/checkbox.blade.php` template by default, while the "checkboxes" and "radios" fields will use the `vendor/styde/html/themes/bootstrap/fields/collection.blade.php` template.

As you can see, the configuration for this is theme specific, as every CSS framework has different specifications.

Note: you'd only have to worry about the theme you actually need, so if you don't plan to use Bootstrap, you can erase/skip the Bootstrap configuration.

### Customize for a particular field

You can specify a custom `template` for a single field through the `'template key'` of the `$attributes` array, like this:

`{!! Field::text(’name’, [’template’ => ’templates/my_template’]) !!}`

The path will be relative to the resources/views/ directory.

### Default templates customization

If you want to customize the default templates, just run: `php artisan vendor:publish` in the console, all the templates will be copied to the `resources/views/themes/[theme]/fields/` folder.

Otherwise, the package will use the default templates (stored in /vendor/styde/html/themes/) so you don’t need to copy extra files into your project.

## name attribute:

You can use dot syntax as the field’s name, for example: `profile.twitter` and it will be converted to `profile[twitter]`

## id attribute:

It will be assigned automatically to each input control, if you use dot syntax (i.e. user.name) the dots will be replaced by underscores (i.e. user_name)

## required attribute:

You can specify a 'required' value in the attributes array:

`{!! Field::text(’name’, [’required’]) !!}`

Or as a key => value pair (the field will be marked as required if the value evaluates to true, i.e.:

`$required = true;`

`{!! Field::text(’name’, null, [’required’ => $required]) !!}`

The field templates will always have a ‘required’ variable so you can use it to print extra CSS classes or badges, to indicate whether a field is required or optional, i.e.:

```
    @if ($required)
        <span class="label label-info">Required</span>
    @endif
```

## Errors:

Any session errors will be loaded into the FieldBuilder through the `HtmlServiceProvider`, and you will have the specific `$errors` for each field available in the field's template, you will also have a `$hasErrors` variable in case you need to print an additional CSS class, etc. if the field has any errors.

For example, with Twitter Bootstrap you will need a `has-error` class in case you want the form fields with errors to be colored in red (UX stuff).

This is an extract of the fields/default template for the Bootstrap theme:

`<div{!! Html::classes(['form-group', 'has-error' => $hasErrors]) !!}>`

Inputs, selects, textareas, etc. with errors will also get an extra CSS class, you can configure this in:


```
    'themes' => [
        'bootstrap' => [
            //...
            'field_classes' => [
            		//...
                'error' => 'input-with-feedback'
                //...
```

And once again, if you are using Twitter Bootstrap, any field with errors will get the `input-with-feedback` class. This is also required for showing the input in red color.

## Options

For selects, radios and checkboxes, you can skip the options argument or pass null:

`{!! Field::select('os') !!}` or `{!! Field::select('os', null) !!}`

If there is a model bound to the form, then the FieldBuilder will check if there is a method called: `get[fieldName]Options` in that case, it will be called and the returned value will be used as the options, i.e.:

```
class Product extends Model

    //...

    public function getOsOptions()
    {
        return ['osx', 'linux', 'windows'];
    }

    //...
```

## Empty option

Select fields usually need an empty option, (like "Select something please"), you can pass it as the `'empty'` attribute, like this:

`{!! Field::select('os', null, ['empty' => 'Select your favorite operative system']) !!}`

If you set the empty attribute to `false`, i.e. `['empty' => false]` , the empty option won't be rendered.

If you don't pass an 'empty' attribute, then the component will search for one using the translator component.

First it will search for a custom empty text according to the field's name, following this convention: `"validation.empty_option.[field_name]"`

If none is found it will search for a default empty option: `"validation.empty_option.default"`

At last if none of these options is not found, it will use an empty string as the empty option.

## Abbreviations

To save some key strokes, you can use abbreviations instead of the full name of the attributes, pass them in the configuration:

```
    /*
     * Specify abbreviations for the form field attributes
     */
    'abbreviations' => [
        'ph' => 'placeholder',
        'max' => 'maxlength',
        'tpl' => 'template'
    ],
```

Then you can do things like this:

`{!! Field::text('name', ['ph' => 'This will be the placeholder]) !!}`

## CSS classes

You can pass custom CSS classes for each field using the 'class' key of the attributes array, also some additional classes will be added:

### Default classes (by type)

Using the configuration, you can assign default CSS class for every field according to its type:

```
    'themes' => [
			//...
        'bootstrap' => [
            //...
            'field_classes' => [
                // type => CSS class or classes
                'default' => 'form-control',
                'checkbox' => '',
                'error' => 'input-with-feedback'
            ],
```

Of course this is theme specific, since it would be impossible to convince all CSS framework authors to use the same classes...

### CSS class for controls with errors

If an input has errors, an additional CSS class called 'error' will be added, this can also be configured for every theme (see above).

## Control access

You may want to hide some fields for certain users, you can do this using the Access Handler included with this component:

[Learn more about the access handler](access-handler.md)
