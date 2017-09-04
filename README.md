# StydeNet Html package

[![Build Status](https://travis-ci.org/StydeNet/html.svg?branch=master)](https://travis-ci.org/StydeNet/html)
[![Downloads](https://img.shields.io/packagist/dt/styde/html.svg)](https://packagist.org/packages/styde/html)
[![Version](https://img.shields.io/packagist/v/styde/html.svg)](https://packagist.org/packages/styde/html)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)

This package contains a collection of Laravel PHP classes designed to generate common HTML components, such as:

* Menus
* Alert messages
* Form fields
* Collection of radios and checkboxes

This is an extension of the Laravel Collective [HTML package](https://github.com/laravelcollective/html) and will be very useful if you are working on a custom CMS, an admin panel or basically any project that needs to generate HTML dynamically.

## How to install

1. The preferred way to install this package is through Composer:

**Laravel 5.5 users**:

Install by running `composer require "styde/html=~1.4"` or adding `"styde/html": "~1.4"` to your `composer.json` file and then running `composer update`.

**Laravel 5.4 users**:

Install by running `composer require "styde/html=~1.3"` or adding `"styde/html": "~1.3"` to your `composer.json` file and then running `composer update`.

**Laravel 5.3 users**:

Install by running `composer require "styde/html=~1.2"` or adding `"styde/html": "~1.2"` to your `composer.json` file and then running `composer update`.

**Laravel 5.2 users**:

Install by running `composer require "styde/html=~1.1"` or adding `"styde/html": "~1.1"` to your `composer.json` file and then running `composer update`.

**Laravel 5.1 users**:

Install by running `composer require "styde/html=~1.0"` or adding `"styde/html": "~1.0"` to your `composer.json` file and then running `composer update`.

2. Next, add the new provider to the `providers` array in `config/app.php` (this step is not necessary if you are using Laravel 5.5 with package auto-discovery)

```php
'providers' => [
    // ...
    Styde\Html\HtmlServiceProvider::class,
    // ...
],
```

3. Also, you need to register in the `app/Http/Kernel.php` file the `\Styde\Html\Alert\Middleware::class` middleware **BEFORE** the `EncryptCookies` middleware. For Laravel 5.4, it's in the `$middlewareGroups` array and for previous versions (Laravel 5.3, 5.2, 5.1) it's in the `$middleware` array:

```php
// For Laravel 5.4 and 5.5
protected $middlewareGroups = [
    \Styde\Html\Alert\Middleware::class,
    //...
];

// For Laravel 5.3, 5.2, 5.1
protected $middleware = [
    //...
    \Styde\Html\Alert\Middleware::class,
    //...
];
```

This middleware is needed to make the alert messages persistent between sessions, after each request is completed.

Please notice that the following global aliases will be automatically available (you don't need to add them):

```php
Alert => Styde\Html\Facades\Alert
Field => Styde\Html\Facades\Field
Menu  => Styde\Html\Facades\Menu
Form  => Collective\Html\FormFacade
Html  => Collective\Html\HtmlFacade
```

If you plan to use the _Access Handler_ as a standalone class, you will need to add the following alias:

```php
'aliases' => [
    // ...
    'Access' => Styde\Html\Facades\Access::class,
    // ...
],
```

Optionally, you may also run `php artisan vendor:publish --provider='Styde\Html\HtmlServiceProvider'` to publish the configuration file in `config/html.php` and review its options and values.

## Usage

Since this package is largely using [LaravelCollective/Html](https://github.com/laravelcollective/html), its documentation for forms and fields is applicable to this package.

## Sandbox

[![Build Status](https://travis-ci.org/StydeNet/html-integration-tests.svg)](https://travis-ci.org/StydeNet/html-integration-tests)

This package aims to stay well documented and unit tested; however, there is another repository that includes integration tests and several routes, so you can clone it to watch the components of this package in action in your browser or run the included integration tests. 

[Check out the sandbox repository](https://github.com/StydeNet/html-integration-tests)

You can review those examples and tests as another way to learn more about what you can do with this component, besides reading the documentation.

## Configuration

This package was created with configuration in mind, if you haven't used this component before, you can simply run:

```zsh
php artisan vendor:publish --provider='Styde\Html\HtmlServiceProvider'
```

this will publish all the configuration options to: `config/html.php` file, where you can explore and read the comments to learn more about the configuration options and their values.
    
*Note:* Since the default configuration will be merged with the custom configuration, you don't need to publish the entire configuration in every project; instead, just set the values you need to override.  

Read this documentation to learn more about the different configuration options this package provides.

## Form Field builder

The Field Builder will allow you to render the full dynamic markup you need for each form field with only one line of code.

If you have used the Laravel Collective HTML component before, you already know the basics, simply replace the alias “Form” with “Field”, for example, replace:

```blade
{!! Form::text('name', 'value', $attributes) !!}
```

With this:

```blade
{!! Field::text('name', 'value', $attributes) !!}
```

[Learn more about the field builder](docs/field-builder.md)

## Forms

This package adds the following functionality to the Laravel Collective's Form Builder:

#### novalidate

Deactivate the HTML5 validation, ideal for local or development environments

```php
//config/html.php
return [
    'novalidate' => true
];
```

#### radios

Generate a collection of radios:
i.e.:
```blade
{!! Form::radios('status', ['a' => 'Active', 'i' => 'Inactive']) !!}
```

#### checkboxes

Generate a collection of checkboxes

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

[Learn more about the form builder](docs/form-builder.md)

## Alert messages

This component will allow you to generate complex alert messages.

```php
Alert::info('Your account is about to expire')
    ->details('Renew now to learn about:')
    ->items(['Laravel', 'PHP, 'And more!'])
    ->button('Renew now!', url('renew'), 'primary');
```

```blade
{!! Alert::render() !!}
```

[Learn more about the alert component](docs/alert-messages.md)

## Menu generator

Menus are not static elements, sometimes you need to mark the current section, translate items, generate dynamic URLs or show/hide options for certain users.

So instead of adding a lot of HTML and Blade boilerplate code, you can use this component to generate dynamic menus styled for your preferred CSS framework.

To generate a menu simply add the following code in your layout’s template:

```blade
{!! Menu::make('items.here') !!}
```

[Learn more about the menu generator](docs/menu-generator.md)

## HTML builder

This package extends the functionality of the Laravel Collective’s HTML Builder.

There’s only one extra method _for now_, but it’s very useful!

#### Generate CSS classes:

```blade
{!! Html::classes(['home' => true, 'main', 'dont-use-this' => false]) !!}
```

Returns: `class="home main"`

[Learn more about the HTML builder](docs/html-builder.md)

### Helpers

In addition of using the facade methods `Alert::message` and `Menu::make`, you can use:

```php
alert('this is the message', 'type-of-message')
```

`menu($items, $classes)`

## Access handler

Sometimes you want to show or hide certain menu items, form fields, etc. for certain users, with this component you can do it without the need of conditionals or too much extra boilerplate code, just pass one of the following options as a field attribute or menu item value.

1. callback: a function that should return true if access is granted, false otherwise.
2. logged: true: requires authenticated user, false: requires guest user.
3. roles: true if the user belongs to any of the required roles.

i.e.: 

```blade
{!! Field::select('user_id', null, ['roles' => 'admin']) !!}
```

[Learn more about the access handler](docs/access-handler.md)

## Themes

There are a lot of CSS frameworks out there, this package was created with that in mind, and even though only Twitter Bootstrap is included out of the box, we plan to add more packages in the future (we also invite you to collaborate).

But you can also create your own themes with ease, or modify the existing one:

To change and / or customize the theme, simply run: 

```zsh
php artisan vendor:publish
```

Then go to `config/html.php` and change the theme value:

```php
//config/html.php
return [
    'theme' => 'your-theme-here'
];
```

You can edit and/or create new templates in `resources/views/themes/` 

[Learn more about the themes](docs/themes.md)

## Internationalization

This package was also created with internationalization in mind.

If you don’t plan to use this feature, you can deactivate translations in the configuration

```php
//config/html.php
return [
    //…
    'translate_texts' => false
    //…
];
```

But if your project needs to implement more than one language or you want to organize all the texts in `resources/lang/` instead of hard coding them in the controllers, views, etc. set `'translate_texts'` to `true`.

[Learn more about the internationalization](docs/internationalization.md)

## More documentation

You can find a lot of comments if you dig into the source code, as well as unit tests in the spec/ directory, you can also clone the [integration tests repository](https://github.com/StydeNet/html-integration-tests).

If you have additional questions, feel free to contact me on Twitter ([@Sileence](https://twitter.com/sileence)) or send me an email to [admin@styde.net](mailto:admin@styde.net).

## License

The Styde\Html package is open-sourced software licensed under the MIT license.
