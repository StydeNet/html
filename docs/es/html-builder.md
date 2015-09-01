# HTML Builder

## Html::classes

Construye dinámicamente un atributo de la clase HTML.

Para usuarios de AngularJS, este método es similar al del atributo ng-class de AngularJS.

Puedes especificar una o más clases CSS como una llave y una condición como un valor. Si la condición es true entonces la(s) clase(s) serán usadas, de lo contrario, serán omitidas.  Puedes también establecer clases estáticas (las cuales siempre se van a usar como un valor sin llave).

Ejemplo:

`{!! Html::classes(['home' => true, 'main', 'no-uses-esto' => false]) !!}`

Retorna: ` class="home main"`.

Tomar en cuenta que esta función retorna un espacio en blanco antes del atributo de la clase. Por tanto, no agregar otro, es decir, úsalo así:

`<p{!! classes(..) !!}>`

en vez de esto:

`<p {{!! classes(..) !!}>`

Si ninguna clase es evaluada como TRUE entonces esta función retornará una cadena vacía.
