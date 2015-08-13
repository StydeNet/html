# Alert Messages

This component will allow you to generate complex alert messages.

 ```
        Alert::info('Your account is about to expire')
            ->details('Renew now to learn about:')
            ->items([
                'Laravel',
                'PHP,
                'And more',
            ])
            ->button('Renew now!', '#', 'primary');
```

The messages will persist in the session until they are presented to the user with:

`{!! Alert::render() !!}`

## Create new alert messages

You can generate new alert messages with:

`{!! Alert::message('This is a message', 'alert-type') !!}`

The first argument is the text of the message, and the second one is the type of alert.

For example:

```
Alert::message('The end is near', 'danger');
```

You can also use magic methods, the name of the method then becomes the alert type:

```
Alert::success("It's all good now");
```

## Method chaining

You can specify more options by method chaining:

### details

You can pass a more detailed message chaining the details() method:

`{!! Alert::info('Some info')->details('A more detailed explanation goes here') !!}`

### call to actions

You can assign buttons to an alert message:

`{!! Alert::info()->button('Call to action', 'some-url', 'primary') !!}`

### html

You can directly pass HTML to the alert message

`{!! Alert::info()->html('<strong>HTML goes here</strong>') !!}`

Be careful since this won't be escaped

### view

You can even render a partial inside an alert message:

`{!! Alert::info()->view('partials/alerts/partial') !!}`

### items

You can pass an array of items (maybe an error list):

`{!! Alert::danger('Please fix these errors')->items($errors) !!}`

## Persist alert messages

Add the following middleware to the `$middleware` array in `app/Http/Kernel.php` **BEFORE** the `\App\Http\Middleware\EncryptCookies`: 

```
protected $middleware = [
    //...
    \Styde\Html\Alert\Middleware::class
    //...
];
```

This middleware is needed to persist the alert messages after each request is completed.

By default the alert messages will persist using the Laravel's session component. But you could also create your own implementation.

## Translations

If the `'translate_texts'` options is set to true in the configuration (it's true by default), the alert component will attempt to translate all the messages, using the `$message` value as a lang key, if the language key is not found, it will return the literal string.
 
If you don't need to use the translator component, just set translate_texts to false in the configuration:

```
//config/html.php
return [
    //...
    'translate_texts' => false
    //...
];
```

## Themes

By default, the alert messages will be rendered with the default template, located in themes/[theme]/alert, for example, for the Bootstrap theme that would be:

`vendor/styde/html/themes/bootstrap/alert.blade.php`

You can pass a custom template as the first argument of the render() method, i.e.:

`{!! Alert::render('partials/custom-alert-template') !!}`