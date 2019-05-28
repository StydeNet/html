<?php

namespace Styde\Html;

use Styde\Html\Fields\Field;
use Illuminate\Contracts\Session\Session;
use Illuminate\Translation\Translator as Lang;

class FieldRenderer
{
    /**
     * The FormBuilder class required to generate the controls (selects, inputs, radios, etc).
     *
     * @var \Styde\Html\FormBuilder
     */
    protected $form;

    /**
     * The Theme class required to render the fields.
     *
     * @var \Styde\Html\Theme
     */
    protected $theme;

    /**
     * The Laravel's translator class, with this object the field renderer
     * will search for attribute names to use them as labels.
     *
     * @var \Illuminate\Translation\Translator
     */
    protected $lang;

    /**
     * Default templates for each input type. You can set these in the config file.
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
     * Creates a new Field Renderer. This is similar to a factory class,
     * but this one generates HTML instead of objects.
     *
     * @param \Styde\Html\FormBuilder $form
     * @param \Styde\Html\Theme $theme
     * @param \Illuminate\Translation\Translator $lang
     * @return \Styde\Html\FieldRenderer
     */
    public function __construct(FormBuilder $form, Theme $theme, Lang $lang)
    {
        $this->form = $form;
        $this->theme = $theme;
        $this->lang = $lang;
    }

    /**
     * Set the default templates for each input type. You can set these values in the config file.
     *
     * @param array $templates
     * @return void
     */
    public function setTemplates(array $templates)
    {
        $this->templates = $templates;
    }

    /**
     * Set the current session
     * @param \Illuminate\Contracts\Session\Session $session
     * @return void
     */
    public function setSessionStore(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Render a Field object.
     *
     * @param \Styde\Html\Fields\Field $field
     * @return \Illuminate\Contracts\Support\Htmlable
     */
    public function render(Field $field)
    {
        return $this->theme->render($this->getTemplate($field), array_merge($field->data, [
                'htmlName' => $this->getHtmlName($field->name),
                'id' => $id = $this->getHtmlId($field->name, $field->attributes),
                'label' => $this->getLabel($field),
                'input' => $this->buildControl($field),
                'errors' => $errors = $this->getControlErrors($id),
                'hasErrors' => !empty($errors),
                'required' => $this->getRequired($field->attributes),
                'helpText' => $field->helpText,
            ])
        );
    }

    /**
     * Build a control according to the $type (input, select, radios, etc).
     *
     * @param \Styde\Html\Fields\Field $field
     * @return string
     */
    protected function buildControl(Field $field)
    {
        switch ($type = $field->type) {
            case 'password':
            case 'file':
                return $this->form->$type($this->getHtmlName($field->name), $field->attributes);
            case 'select':
                return $this->form->select(
                    $this->getHtmlName($field->name),
                    $this->addEmptyOption(
                        $field->name, $this->getOptionsList($field->name, $field->getOptions()), $field->attributes
                    ),
                    $field->value,
                    $field->attributes
                );
            case 'radios':
            case 'checkboxes':
                return $this->form->$type(
                    $this->getHtmlName($field->name),
                    $this->getOptionsList($field->name, $field->getOptions()),
                    $field->value,
                    $field->attributes
                );
            case 'checkbox':
                return $this->form->checkbox(
                    $this->getHtmlName($field->name),
                    $field->getOptions() ?: 1,
                    $field->value,
                    $field->attributes
                );
            default:
                return $this->form->$type($this->getHtmlName($field->name), $field->value, $field->attributes);
        }
    }

    /**
     * Get the template that will be used to render the field.
     *
     * @param Field $field
     * @return string
     */
    protected function getTemplate(Field $field): string
    {
        return $field->template ?: "@fields.{$this->getDefaultTemplate($field->type)}";
    }

    /**
     * Get the field label that will be passed to the template.
     *
     * @param Field $field
     * @return string|\Illuminate\Support\HtmlString
     */
    protected function getLabel(Field $field)
    {
        return $field->label ?: $this->getDefaultLabel($field->name);
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
     * Attempt to get the option list from the model. Otherwise it will return an empty array.
     *
     * The model needs to be set and have a method with the following convention:
     * attribute -> get[Attribute]Options, i.e.:
     * user_id -> getUserIdOptions()
     *
     * @param $name
     * @return array
     */
    protected function getOptionsFromModel($name)
    {
        $model = $this->form->getModel();

        if (is_null($model)) {
            return [];
        }

        $method = 'get'.Str::studly($name).'Options';

        if (method_exists($model, $method)) {
            return $model->$method();
        }

        return [];
    }

    /**
     * Adds an empty option for select inputs if the option list is not empty.
     *
     * You can pass the empty option's text as the "empty" key in the attribute's array.
     * You can also set this as a lang's key (see the getEmptyOption method below).
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
     * Get the default template for the field, based on it's type, if no template is set
     * in the configuration for a particular type, the template "default" will be used.
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
     * Get the HTML name for the input control. You can use dots to specify arrays:
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
     * Get whether a field is required or not.
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
     * Use the translator component to search for a translation, i.e. name => validation.attributes.name.
     * If this is not found, generate a label based on the field's name.
     *
     * @param  string $name
     * @return string
     */
    protected function getDefaultLabel($name)
    {
        $attribute = "validation.attributes.{$name}";

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
}
