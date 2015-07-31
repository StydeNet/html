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

```php
  'providers' => [
    // ...
    'Styde\Html\HtmlServiceProvider',
    // ...
  ],
```

Finally, add two class aliases to the `aliases` array of `config/app.php`



```php
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

you can use the form/field builder for generation of just about any kind of form element. The form builder extends Laravel Collective's form builder. Hence, any method you can use on that form builder can be used in this package too. However, this form builder adds a few new input types, as well as the output not only of the fields, but the html as however is structured in the theme. You can access the field builder through the `Field` facade.

### Access handler

This package includes rudimentary access management through the `AccessHandler` interface. By default, a basic implementation is provided with the package. Using the access handler, you can use the check method for some level of user control. The check method will search for one of these options, in the following and only one option will be used to check if the user has access:

1. callback (should return true if access is granted, false otherwise)
2. logged (true: requires authenticated user, false: requires guest user)
3. role (true if the user has any of the required roles)
4. Returns true if no security options are set.

**Example in Blade:**

```
@if (Access::check('roles' => 'owner')
	{!! Field::text('super-secure', null, ['label' => 'Type yes to delete site']) !!}
@endif
```

This will check that the user has the owner role. However, you have to implement the role system yourself, this will merely check for the available roles and see if the current user has that role.

### Alert messages

### Menu generator

## Themes

## Readme in progress

This readme is currently in progress. However, you can find a lot of docblock comments if you dig into the source course, as well as unit tests in the spec directory.
