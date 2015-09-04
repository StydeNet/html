<?php

namespace Styde\Html;

use Illuminate\Translation\Translator as Lang;
use Styde\Html\Access\VerifyAccess;

class FieldBuilder
{
    use VerifyAccess;

    /**
     * The FormBuilder class required to generate controls
     * (inputs, selects, radios, etc.)
     *
     * @var \Styde\Html\FormBuilder
     */
    protected $form;
    /**
     * The Theme class required to render the fields
     *
     * @var \Styde\Html\Theme
     */
    protected $theme;
    /**
     * The Laravel's translator class, with this object the field builder will
     * search for attribute names to use them as labels.
     *
     * @var \Illuminate\Translation\Translator
     */
    protected $lang;
    /**
     * Set convenient abbreviations for the HTML attributes
     * i.e. "ph" instead of "placeholder", etc.
     * You can set the abbreviations in the configuration file (config/html.php)
     *
     * @var array
     */
    protected $abbreviations = [];
    /**
     * Default CSS classes for each input type.
     * You can set these in the config file.
     *
     * @var array
     */
    protected $cssClasses = [];
    /**
     * Default templates for each input type
     * You can set these in the config file.
     *
     * @var array
     */
    protected $templates = [];
    /**
     * Current session errors. If a field contains errors, they'll be render as
     * part of the field's template.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Creates a new Field Builder.
     *
     * This is similar to a factory class, but this one generates HTML instead
     * of objects.
     *
     * @param \Styde\Html\FormBuilder $form
     * @param \Styde\Html\Theme $theme
     * @param \Illuminate\Translation\Translator $lang
     */
    public function __construct(FormBuilder $form, Theme $theme, Lang $lang)
    {
        $this->form = $form;
        $this->theme = $theme;
        $this->lang = $lang;
    }

    // Setters

    /**
     * Set the attribute abbreviation options i.e.:
     * ['ph' => 'placeholder', 'req' => 'required']
     *
     * You can set these values in the config file
     *
     * @param array $abbreviations
     */
    public function setAbbreviations(array $abbreviations)
    {
        $this->abbreviations = $abbreviations;
    }

    /**
     * Set the default CSS classes for each input type.
     * You can set these values in the config file.
     *
     * @param array $cssClasses
     */
    public function setCssClasses(array $cssClasses)
    {
        $this->cssClasses = $cssClasses;
    }

    /**
     * Set the default templates for each input type.
     * You can set these values in the config file.
     *
     * @param array $templates
     */
    public function setTemplates(array $templates)
    {
        $this->templates = $templates;
    }

    /**
     * Set the current session errors. If a field contains errors,
     * they'll be render as part of the field's template.
     * You must set them as an associative array of arrays, i.e.:
     *
     * [
     *   'email' => ['Invalid email']
     *   'password' => ['Needs upper case', 'Needs lower case', 'Needs klingon']
     * ]
     *
     * @param array $errors
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * Dynamically handle calls to the field builder.
     *
     * The method's name will be used as the input type
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array(
            [$this, 'build'],
            array_merge([$method], $parameters)
        );
    }

    /**
     * Create a form input field.
     *
     * @param string $type
     * @param string $name
     * @param string $value
     * @param array  $attributes
     *
     * @return string
     */
    public function input($type, $name, $value = null, $attributes = array())
    {
        return $this->build($type, $name, $value, $attributes);
    }

    /**
     * Create a text input field.
     *
     * @param string $name
     * @param string $value
     * @param array  $attributes
     *
     * @return string
     */
    public function text($name, $value = null, $attributes = array())
    {
        return $this->build('text', $name, $value, $attributes);
    }

    /**
     * Create a password input field.
     *
     * @param string $name
     * @param array  $attributes
     *
     * @return string
     */
    public function password($name, $attributes = array())
    {
        return $this->build('password', $name, '', $attributes);
    }

    /**
     * Create a hidden input field.
     *
     * @param string $name
     * @param string $value
     * @param array  $attributes
     *
     * @return string
     */
    public function hidden($name, $value = null, $attributes = array())
    {
        return $this->form->input('hidden', $name, $value, $attributes);
    }

    /**
     * Create an e-mail input field.
     *
     * @param string $name
     * @param string $value
     * @param array  $attributes
     *
     * @return string
     */
    public function email($name, $value = null, $attributes = array())
    {
        return $this->build('email', $name, $value, $attributes);
    }

    /**
     * Create a URL input field.
     *
     * @param string $name
     * @param string $value
     * @param array  $attributes
     *
     * @return string
     */
    public function url($name, $value = null, $attributes = array())
    {
        return $this->build('url', $name, $value, $attributes);
    }

    /**
     * Create a file input field.
     *
     * @param string $name
     * @param array  $attributes
     *
     * @return string
     */
    public function file($name, $attributes = array())
    {
        return $this->build('file', $name, null, $attributes);
    }

    /**
     * Create a textarea input field.
     *
     * @param string $name
     * @param string $value
     * @param array  $attributes
     *
     * @return string
     */
    public function textarea($name, $value = null, $attributes = array())
    {
        return $this->build('textarea', $name, $value, $attributes);
    }

    /**
     * Create a radios field.
     *
     * @param string $name
     * @param array  $options
     * @param string $selected
     * @param array  $attributes
     *
     * @return string
     */
    public function radios($name, $options = array(), $selected = null, $attributes = array())
    {
        return $this->build('radios', $name, $selected, $attributes, $options);
    }

    /**
     * Create a select box field.
     *
     * @param string $name
     * @param array  $options
     * @param string $selected
     * @param array  $attributes
     *
     * @return string
     */
    public function select($name, $options = array(), $selected = null, $attributes = array())
    {
        return $this->build('select', $name, $selected, $attributes, $options);
    }

    /**
     * Create a checkboxes field.
     *
     * @param string $name
     * @param array  $options
     * @param string $selected
     * @param array  $attributes
     *
     * @return string
     */
    public function checkboxes($name, $options = array(), $selected = null, $attributes = array())
    {
        return $this->doBuild('checkboxes', $name, $selected, $attributes, $options);
    }

    /**
     * Create a checkbox input field.
     *
     * @param string $name
     * @param mixed  $value
     * @param null   $selected
     * @param array  $attributes
     *
     * @internal param bool $checked
     *
     * @return string
     */
    public function checkbox($name, $value = 1, $selected = null, $attributes = array())
    {
        return $this->build('checkbox', $name, $selected, $attributes, $value);
    }

    /**
     * Get the option list for the select box or the group of radios
     *
     * @param string $name
     * @param array $options
     * @return array
     * @internal param array $attributes
     */
    protected function getOptionsList($name, $options)
    {
        if (empty($options)) {
            $options = $this->getOptionsFromModel($name);
        }

        return $options;
    }

    /**
     * Attempt to get the option list from the model
     *
     * The model needs to be set and have a method with the following convention:
     *
     * attribute -> get[Attribute]Options, i.e.:
     * user_id -> getUserIdOptions()
     *
     * Otherwise it will return an empty array
     *
     * @param $name
     *
     * @return mixed
     */
    protected function getOptionsFromModel($name)
    {
        $model = $this->form->getModel();

        if (is_null($model)) {
            return array();
        }

        $method = 'get'.String::studly($name).'Options';

        if (method_exists($model, $method)) {
            return $model->$method();
        }

        return array();
    }

    /**
     * Adds an empty option for select inputs if the option list is not empty.
     *
     * You can pass the empty option's text as the "empty" key in the
     * attribute's array. Or you can set this as a lang's key (see the
     * getEmptyOption method below).
     *
     * @param $name
     * @param array $options
     * @param array $attributes
     *
     * @return array
     */
    protected function addEmptyOption($name, array $options, array &$attributes)
    {
        if (empty($options)) {
            return [];
        }

        if (isset($attributes['empty'])) {
            $text = $attributes['empty'];
            unset($attributes['empty']);
        } else {
            $text = $this->getEmptyOption($name);
        }

        if ($text === false) {
            return $options;
        }

        return ['' => $text] + $options;
    }

    /**
     * Get the empty text (for select controls) from the Translator component
     * You can set this as a lang's key with the following convention:
     *
     * attribute -> validation.empty_option.[attribute] i.e.:
     * user_id -> 'validation.empty_option.user_id' => 'Select user'
     *
     * You can also set a validation.empty_option.default as a fallback.
     *
     * @param $name
     * @return string
     * @internal param $attributes
     */
    protected function getEmptyOption($name)
    {
        $emptyText = $this->lang->get("validation.empty_option.$name");

        if ($emptyText != "validation.empty_option.$name") {
            return $emptyText;
        }

        $emptyText = $this->lang->get('validation.empty_option.default');

        if ($emptyText != 'validation.empty_option.default') {
            return $emptyText;
        }

        return '';
    }

    /**
     * Get the default template for the field, based on it's type, if no
     * template is set in the configuration for a particular type,
     * the template "default" will be used
     *
     * @param $type
     * @return mixed
     */
    protected function getDefaultTemplate($type)
    {
        return isset($this->templates[$type])
            ? $this->templates[$type]
            : 'default';
    }

    /**
     * Get the custom template for an individual field if no custom template is
     * set, this will return null.
     *
     * You can pass a custom template using the 'template' key in the attributes
     * array.
     *
     * @param $attributes
     * @return string|null
     */
    protected function getCustomTemplate($attributes)
    {
        return isset($attributes['template']) ? $attributes['template'] : null;
    }

    /**
     * Get the HTML name for the input control.
     *
     * You can use dots to specify arrays:
     *
     * product.category.name will be converted to: product[category][name]
     *
     * @param $name
     * @return string
     */
    protected function getHtmlName($name)
    {
        if (strpos($name, '.')) {
            $segments = explode('.', $name);
            return array_shift($segments).'['.implode('][', $segments).']';
        }

        return $name;
    }

    /**
     * Get the ID's attribute for the control
     *
     * @param $value
     * @return mixed
     */
    protected function getHtmlId($value)
    {
        if (strpos($value, '.')) {
            return str_replace('.', '_', $value);
        }

        return $value;
    }

    /**
     * Gets whether a field is required or not
     *
     * @param $attributes
     * @return bool
     */
    protected function getRequired($attributes)
    {
        if (in_array('required', $attributes)) {
            return true;
        }

        if (isset($attributes['required'])) {
            return $attributes['required'];
        }

        return false;
    }

    /**
     * Get the field's label: it can be set in the attributes array with the key
     * 'label'. If the 'label' key is not found, then we will use the translator
     * component to search for a translation:
     *
     * i.e. name => validation.attributes.name.
     *
     * If this is not found either, it'll generate a label based on the name.
     *
     * @param string $name
     * @param array $attributes
     * @return string
     */
    protected function getLabel($name, array $attributes = [])
    {
        if (isset($attributes['label'])) {
            return $attributes['label'];
        }

        $attribute = 'validation.attributes.'.$name;
        
        $label = $this->lang->get($attribute);

        if ($label == $attribute) {
            $label = str_replace(['_', '.'], ' ', $name);
        }

        return ucfirst($label);
    }

    /**
     * Get the default HTML classes for a particular type.
     *
     * If the type is not defined it will use the 'default' key in the
     * cssClasses array, otherwise it will return an empty string.
     *
     * @param $type
     * @return string
     */
    protected function getDefaultClasses($type)
    {
        return isset($this->cssClasses[$type])
            ? $this->cssClasses[$type]
            : isset($this->cssClasses['default'])
                ? $this->cssClasses['default']
                : '';
    }

    /**
     * Get the HTML classes for a particular field.
     *
     * It concatenates the default CSS classes plus the custom classes (passed
     * as the class key in the $attributes array).
     *
     * And it will also add an extra class if the control has any errors.
     *
     * @param string $type
     * @param array $attributes
     * @param string|null $errors
     * @return string
     */
    protected function getClasses($type, array $attributes = [], $errors = null)
    {
        $classes = $this->getDefaultClasses($type);

        if (isset($attributes['class'])) {
            $classes .= ' '.$attributes['class'];
        }

        if ( ! empty($errors)) {
            $classes .= ' '.(isset($classes['error']) ? $classes['error'] : 'error');
        }

        return trim($classes);
    }

    /**
     * Get the control's errors (if any)
     * @param $name
     * @return string|null
     */
    protected function getControlErrors($name)
    {
        return isset($this->errors[$name]) ? $this->errors[$name] : [];
    }

    /**
     * Get the HTML attributes for a control (input, select, etc.)
     *
     * This will assign the CSS classes, the id attribute, normalize the
     * required attribute and unset the custom attributes like "template".
     *
     * @param $type
     * @param $attributes
     * @param $errors
     * @param $htmlId
     * @param $required
     *
     * @return array
     */
    protected function getHtmlAttributes($type, $attributes, $errors, $htmlId, $required)
    {
        $attributes['class'] = $this->getClasses($type, $attributes, $errors);

        $attributes['id'] = $htmlId;

        if ($required && !in_array('required', $attributes)) {
            $attributes[] = 'required';
        }

        unset($attributes['template'], $attributes['required'], $attributes['label']);

        return $attributes;
    }

    /**
     * Search for abbreviations and replace them with the right attributes
     *
     * @param array $attributes
     * @return array
     */
    protected function replaceAttributes(array $attributes)
    {
        foreach ($this->abbreviations as $abbreviation => $attribute) {
            if (isset($attributes[$abbreviation])) {
                $attributes[$attribute] = $attributes[$abbreviation];
                unset($attributes[$abbreviation]);
            }
        }

        return $attributes;
    }

    /**
     * Swap values ($value and $attributes) if necessary, then call doBuild
     *
     * @param string $type
     * @param string $name
     * @param mixed|null $value
     * @param array $attributes
     * @param array|null $options
     * @return string
     */
    protected function build($type, $name, $value = null, $attributes = array(), $options = null)
    {
        /**
         * Swap values so programmers can skip the $value argument
         * and pass the $attributes array directly.
         */
        if (is_array($value)) {
            $attributes = $value;
            $value = null;
        }

        return $this->doBuild($type, $name, $value, $attributes, $options);
    }

    /**
     * Build and render a field
     *
     * @param string $type
     * @param string $name
     * @param mixed $value
     * @param array $attributes
     * @param array|null $options
     * @return string
     */
    protected function doBuild($type, $name, $value = null, $attributes = array(), $options = null)
    {
        $attributes = $this->replaceAttributes($attributes);

        if (!$this->checkAccess($attributes)) {
            return '';
        }

        $required = $this->getRequired($attributes);
        $label = $this->getLabel($name, $attributes);
        $htmlName = $this->getHtmlName($name);
        $id = $this->getHtmlId($name);
        $errors = $this->getControlErrors($id);
        $hasErrors = !empty($errors);
        $customTemplate = $this->getCustomTemplate($attributes);

        $attributes = $this->getHtmlAttributes($type, $attributes, $errors, $id, $required);

        $input = $this->buildControl($type, $name, $value, $attributes, $options, $htmlName);

        return $this->theme->render(
            $customTemplate,
            compact('htmlName', 'id',  'label', 'input', 'errors', 'hasErrors', 'required'),
            'fields.'.$this->getDefaultTemplate($type)
        );
    }

    /**
     * Builds a control according to the $type (input, select, radios, etc.)
     *
     * @param string $type
     * @param string $name
     * @param mixed $value
     * @param array $attributes
     * @param array|null $options
     * @param string $htmlName
     *
     * @return string
     */
    protected function buildControl($type, $name, $value, $attributes, $options, $htmlName)
    {
        switch ($type) {
            case 'password':
            case 'file':
                return $this->form->$type($htmlName, $attributes);
            case 'select':
                return $this->form->$type(
                    $htmlName,
                    $this->addEmptyOption(
                        $name,
                        $this->getOptionsList($name, $options),
                        $attributes
                    ),
                    $value,
                    $attributes
                );
            case 'radios':
            case 'checkboxes':
                return $this->form->$type(
                    $htmlName,
                    $this->getOptionsList($name, $options),
                    $value,
                    $attributes
                );
            case 'checkbox':
                return $this->form->checkbox(
                    $htmlName,
                    $options ?: 1,
                    $value,
                    $attributes
                );
            default:
                return $this->form->$type($htmlName, $value, $attributes);
        }
    }

}
