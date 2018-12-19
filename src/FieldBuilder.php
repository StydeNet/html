<?php

namespace Styde\Html;

use Styde\Html\FormModel\Field;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Session\Session;
use Illuminate\Translation\Translator as Lang;

class FieldBuilder
{
    use Macroable {
        Macroable::__call as macroCall;
    }

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
     * Default templates for each input type
     * You can set these in the config file.
     *
     * @var array
     */
    protected $templates = [];
    /**
     * Current session.
     *
     * @var \Illuminate\Contracts\Session\Session
     */
    protected $session;

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
     * Set the current session
     */
    public function setSessionStore(Session $session)
    {
        $this->session = $session;
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
        if ($this->hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

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
     * @param array  $extra
     *
     * @return string
     */
    public function input($type, $name, $value = null, array $attributes = array(), array $extra = array())
    {
        return $this->swapAndBuild($type, $name, $value, $attributes, $extra);
    }

    /**
     * Create a text input field.
     *
     * @param string $name
     * @param string $value
     * @param array  $attributes
     * @param array  $extra
     *
     * @return string
     */
    public function text($name, $value = null, array $attributes = array(), array $extra = array())
    {
        return $this->swapAndBuild('text', $name, $value, $attributes, $extra);
    }

    /**
     * Create a password input field.
     *
     * @param string $name
     * @param array  $attributes
     * @param array  $extra
     *
     * @return string
     */
    public function password($name, array $attributes = array(), array $extra = array())
    {
        return $this->build('password', $name, '', $attributes, $extra);
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
    public function hidden($name, $value = null, array $attributes = array())
    {
        return $this->form->input('hidden', $name, $value, $attributes);
    }

    /**
     * Create an e-mail input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $attributes
     * @param  array  $extra
     *
     * @return string
     */
    public function email($name, $value = null, array $attributes = array(), array $extra = array())
    {
        return $this->swapAndBuild('email', $name, $value, $attributes, $extra);
    }

    /**
     * Create a URL input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $attributes
     * @param  array  $extra
     *
     * @return string
     */
    public function url($name, $value = null, array $attributes = array(), array $extra = array())
    {
        return $this->swapAndBuild('url', $name, $value, $attributes, $extra);
    }

    /**
     * Create a file input field.
     *
     * @param  string $name
     * @param  array  $attributes
     * @param  array  $extra
     *
     * @return string
     */
    public function file($name, array $attributes = array(), array $extra = array())
    {
        return $this->build('file', $name, null, $attributes, $extra);
    }

    /**
     * Create a textarea input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $attributes
     * @param  array  $extra
     *
     * @return string
     */
    public function textarea($name, $value = null, array $attributes = array(), array $extra = array())
    {
        return $this->swapAndBuild('textarea', $name, $value, $attributes, $extra);
    }

    /**
     * Create a radios field.
     *
     * @param string $name
     * @param array  $options
     * @param string $selected
     * @param array  $attributes
     * @param array  $extra
     *
     * @return string
     */
    public function radios($name, $options = array(), $selected = null, array $attributes = array(), array $extra = array())
    {
        return $this->swapAndBuild('radios', $name, $selected, $attributes, $extra, $options);
    }

    /**
     * Create a select box field.
     *
     * @param string $name
     * @param array  $options
     * @param string $selected
     * @param array  $attributes
     * @param array  $extra
     *
     * @return string
     */
    public function select($name, $options = array(), $selected = null, array $attributes = array(), array $extra = array())
    {
        /**
         * Swap values so programmers can skip the $value argument
         * and pass the $attributes array directly.
         */
        if (is_array($selected) && empty($attributes)) {
            $extra = $attributes;
            $attributes = $selected;
            $selected = null;
        }

        return $this->build('select', $name, $selected, $attributes, $extra, $options);
    }

    /**
     * Create a multiple select field
     *
     * @param $name
     * @param array $options
     * @param array $selected
     * @param array $attributes
     * @param array $extra
     * @return string
     */
    public function selectMultiple($name, $options = array(), $selected = null, array $attributes = array(), array $extra = array())
    {
        $attributes[] = 'multiple';
        return $this->build('select', $name, $selected, $attributes, $extra, $options);
    }

    /**
     * Create a checkboxes field.
     *
     * @param string $name
     * @param array  $options
     * @param string $selected
     * @param array  $attributes
     * @param array  $extra
     *
     * @return string
     */
    public function checkboxes($name, $options = array(), $selected = null, array $attributes = array(), array $extra = array())
    {
        return $this->build('checkboxes', $name, $selected, $attributes, $extra, $options);
    }

    /**
     * Create a checkbox input field.
     *
     * @param string $name
     * @param mixed  $value
     * @param null   $selected
     * @param array  $attributes
     * @param array  $extra
     *
     * @internal param bool $checked
     *
     * @return string
     */
    public function checkbox($name, $value = 1, $selected = null, array $attributes = array(), array $extra = array())
    {
        return $this->swapAndBuild('checkbox', $name, $selected, $attributes, $extra, $value);
    }

    /**
     * Build and render a field
     *
     * @param  string $type
     * @param  string $name
     * @param  mixed $value
     * @param  array $attributes
     * @param  array $extra
     * @param  array|null $options
     * @return string
     */
    public function build($type, $name, $value = null, array $attributes = [], array $extra = [], $options = null)
    {
        $field = new Field($this, $name, $type);

        $this->setCustomAttributes($attributes, $field);

        if (isset($attributes['required']) && ! $attributes['required']) {
            unset($attributes['required']);
        }

        $field->value($value)
            ->attributes($attributes)
            ->extra($extra)
            ->options($options);

        return $field;
    }

    /**
     * Render a Field object. Field objects can be built using the build function on this class.
     * Or through a FieldCollection in a FormModel.
     *
     * @param  Field $field
     * @return string
     */
    public function render(Field $field)
    {
        $required = $this->getRequired($field->attributes);

        $label = $field->label;
        if ($label == null) {
            $label = $this->getDefaultLabel($field->name);
        }

        $helpText = $field->helpText;

        $htmlName = $this->getHtmlName($field->name);

        $id = $this->getHtmlId($field->name, $field->attributes);

        $errors = $this->getControlErrors($id);

        $hasErrors = !empty($errors);

        $input = $this->buildControl(
            $field->type, $field->name, $field->value, $field->attributes, $field->getOptions(), $htmlName
        );

        return $this->theme->render(
            $field->template,
            array_merge(
                $field->extra,
                compact('htmlName', 'id', 'label', 'input', 'errors', 'hasErrors', 'required', 'helpText')
            ),
            'fields.'.$this->getDefaultTemplate($field->type)
        );
    }

    protected function setCustomAttributes(&$attributes, $field)
    {
        $custom = ['label', 'template', 'id', 'helpText'];

        foreach ($custom as $key) {
            if (isset($attributes[$key])) {
                $field->$key($attributes[$key]);
                unset($attributes[$key]);
            }
        }
    }

    /**
     * Get the option list for the select box or the group of radios
     *
     * @param  string $name
     * @param  array $options
     * @return array
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

        $method = 'get'.Str::studly($name).'Options';

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

        // Don't add an empty option if the select is "multiple"
        if (isset($attributes['multiple']) || in_array('multiple', $attributes)) {
            return $options;
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
     * @param  $name
     * @return string
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
     * @param  $type
     * @return string
     */
    protected function getDefaultTemplate($type)
    {
        return isset($this->templates[$type])
            ? $this->templates[$type]
            : 'default';
    }

    /**
     * Get the HTML name for the input control.
     *
     * You can use dots to specify arrays:
     *
     * product.category.name will be converted to: product[category][name]
     *
     * @param  string $name
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
     * @param  string $value
     * @param $attributes
     * @return string
     */
    protected function getHtmlId($value, $attributes)
    {
        if (isset($attributes['id'])) {
            return $attributes['id'];
        }

        if (strpos($value, '.')) {
            return str_replace('.', '_', $value);
        }

        return $value;
    }

    /**
     * Gets whether a field is required or not
     *
     * @param  array $attributes
     * @return bool
     */
    protected function getRequired($attributes)
    {
        if (isset($attributes['required'])) {
            return $attributes['required'];
        }

        if (in_array('required', $attributes)) {
            return true;
        }

        return false;
    }

    /**
     * Use the translator component to search for a translation
     *
     * i.e. name => validation.attributes.name.
     *
     * If this is not found, generate a label based on the field's name.
     *
     * @param  string $name
     * @return string
     */
    protected function getDefaultLabel($name)
    {
        $attribute = 'validation.attributes.'.$name;
        
        $label = $this->lang->get($attribute);

        if ($label == $attribute) {
            $label = str_replace(['_', '.'], ' ', $name);
        }

        return ucfirst($label);
    }

    /**
     * Get the control's errors (if any)
     *
     * @param  string $name
     * @return array
     */
    protected function getControlErrors($name)
    {
        if ($this->session) {
            if ($errors = $this->session->get('errors')) {
                return $errors->get($name, []);
            }
        }

        return [];
    }

    /**
     * Swap values ($value and $attributes) if necessary, then call build
     *
     * @param  string $type
     * @param  string $name
     * @param  mixed|null $value
     * @param  array $attributes
     * @param  array|null $options
     * @param  array $extra
     * @return string
     */
    protected function swapAndBuild($type, $name, $value = null, array $attributes = array(), array $extra = array(), $options = null)
    {
        /**
         * Swap values so programmers can skip the $value argument
         * and pass the $attributes array directly.
         */
        if (is_array($value)) {
            $extra = $attributes;
            $attributes = $value;
            $value = null;
        }

        return $this->build($type, $name, $value, $attributes, $extra, $options);
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
