# HTML Builder

## Html::classes

Build an HTML class attribute dynamically.

For AngularJS users, this method is similar to the ng-class attribute of AngularJS.

You can specify one or more CSS classes as a key and a condition as a value. If the condition is evaluated as true, then the class(es) will be used, otherwise they will be skipped. You can also set the static class(es) (those which we'll always be used) as a value with no key.

Example:

`{!! Html::classes(['home' => true, 'main', 'dont-use-this' => false]) !!}`

Returns: ` class="home main"`.

Note that this function returns an empty space before the class attribute. So don't add another one, in other words use it like this:

`<p{!! classes(..) !!}>`

instead of this:

`<p {{!! classes(..) !!}>`

If no classes are evaluated as TRUE then this function will return an empty string.