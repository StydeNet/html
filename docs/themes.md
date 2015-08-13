# Themes

There are a lot of CSS (and all kind of) frameworks out there, this package was created with that in mind, and even though *Bootstrap* is included _out of the box_, we plan to add more themes in the future (we also invite you to collaborate). You can also create your own themes, publish and customize them if you need to.

To change or customize a theme, simply run: 

`php artisan vendor:publish`

Then go to `config/html.php` and change the theme value:

```
//config/html.php
return [
    'theme' => 'custom-theme'
];
```

Then create a folder in `resources/views/themes/` called 'custom-theme', to save some time, you can copy the bootstrap/ folder and paste it as 'custom-theme'.

Then you can change all the templates within that directory or add new ones if you need to.

## Customize individual templates

Maybe you don't need to create or use a new theme and you just simply need to override a particular template, you can do this too, since most methods support that, for example:

`{!! Menu::make('menu.items')->render('custom-template') !!}`

`{!! Alert::render('custom-template') !!}`

`{!! Field::email('email', ['template' => 'custom-template'])`

## Customize templates by field type (field builder)

Are you using a CSS framework that requires a different markup for a particular field type? Don't worry, just read the "Customize by type" section of the [ field builder page](field-builder.md)

## Pull requests

If you create a theme for a popular CSS framework, you can collaborate by forking this repository, and creating a pull request, remember to store the templates in the `themes/` folder. Thank you.