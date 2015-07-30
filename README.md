#StydeNet Html package

This package contains a collection of Laravel PHP classes designed to generate common HTML components, like:

* Menus
* Alert messages
* Form fields
* Collection of radios and checkboxes

This is an extension of the Laravel Collective [HTML package](https://github.com/laravelcollective/html) and will be very useful for you if you are working in a custom CMS, an admin panel or basically any project that needs to generate dynamic HTML.

## Quickstart

Begin by installing this package through Composer. Do this either by running `composer require styde/html ~1.0` or adding `styde/html: ~1.0` to your `composer.json` and running `composer update`.

Next, add your new provider to the `providers` array of `config/app.php`

```
  'providers' => [
    // ...
    'Styde\Html\HtmlServiceProvider',
    // ...
  ],
```

Finally, add two class aliases to the `aliases` array of `config/app.php`



```
  'aliases' => [
    // ...
    'Styde\Html\HtmlServiceProvider',
    'Access'	=> Styde\Html\Facades\Access,
    'Alert'	=> Styde\Html\Facades\Alert,
    'Field'	=> Styde\Html\Facades\Field,
    'Menu'	=> Styde\Html\Facades\Menu,
    // ...
  ],
```

Optionally, you may also run `php artisan vendor:publish --provider='Styde\Html\HtmlServiceProvider'` to publish the configuration file and explore at own will.

## Usage

Since this package is largely using [LaravelCollective/Html](https://github.com/laravelcollective/html), following their documentation is perfectly sufficient for base functionality such as forms and fields.

### Form functionality (Field builder)

### Access handler

### Alert messages

### Menu generator

## Themes

## Readme in progress

This readme is currently in progress. However, you can find a lot of docblock comments if you dig into the source course, as well as unit tests in the spec directory.
