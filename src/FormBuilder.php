<?php

namespace Styde\Html;

use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\Routing\UrlGenerator;

class FormBuilder
{
    /**
     * Whether to deactivate or not the HTML5 validation (in order to test
     * backend validation).
     *
     * @var bool $novalidate
     */
    protected $novalidate = false;

    /**
     * The CSRF token used by the form builder.
     *
     * @var string
     */
    protected $csrfToken;

    /**
     * The Theme object in charge of rendering the right view for this theme
     *
     * @var \Styde\Html\Theme
     */
    protected $theme;

    /**
     * Creates a new Form Builder class. This extends from the Collective
     * Form Builder but adds a couple of extra functions.
     *
     * @param \Illuminate\Contracts\Routing\UrlGenerator $url
     * @param string $csrfToken
     * @param \Styde\Html\Theme $theme
     */
    public function __construct(UrlGenerator $url, Theme $theme, $csrfToken)
    {
        $this->theme = $theme;
        $this->csrfToken = $csrfToken;
        $this->view = $theme->getView();
    }

    /**
     * Set the session store implementation.
     *
     * @param \Illuminate\Contracts\Session\Session $session
     *
     * @return $this
     */
    public function setSessionStore(Session $session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * Get the protected model attribute
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Allows user to set the novalidate option for every form generated with
     * the form open method, so developers can skip HTML5 validation, in order
     * to test backend validation in a local or development environment.
     *
     * @param boolean $value
     * @return null
     */
    public function novalidate($value = true)
    {
        $this->novalidate = $value;
    }

    /**
     * Open up a new HTML form and pass the optional novalidate option.
     * This methods relies on the original Form::open method of the Laravel
     * Collective component.
     *
     * @param array $attributes
     *
     * @return string
     */
    public function open(array $attributes = array())
    {
        if ($this->novalidate) {
            $attributes['novalidate'] = true;
        }

        return (new HtmlElement('form', '', $attributes))->open();
    }

    /**
     * Create a form label element.
     *
     * @param string $name
     * @param string $content
     * @param array $attributes
     *
     * @return \Styde\Html\HtmlElement
     */
    public function label($name, $content = '', $attributes = [])
    {
        if (empty ($content)) {
            $content = ucwords(str_replace('_', ' ', $name));
        }

        return new HtmlElement('label', $content, $attributes);
    }

    /**
     * Create a text input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $attributes
     *
     * @return \Styde\Html\HtmlElement
     */
    public function text(string $name, $value = null, $attributes = [])
    {
        return $this->input('text', $name, $value, $attributes);
    }

    /**
     * Create a form input field.
     *
     * @param string $type
     * @param string $name
     * @param string $value
     * @param array  $attributes
     *
     * @return \Styde\Html\HtmlElement
     */
    public function input($type, $name, $value = null, $attributes = [])
    {
        return new HtmlElement('input', false, array_merge(compact('type', 'name', 'value'), $attributes));
    }

    /**
     * Create a textarea input field.
     *
     * @param string $type
     * @param string $name
     * @param string $value
     * @param array  $attributes
     *
     * @return \Styde\Html\HtmlElement
     */
    public function textarea($type, $name, $value = null, $attributes = [])
    {
        return new HtmlElement('input', false, array_merge(compact('type', 'name', 'value'), $attributes));
    }

    /**
     * Create a select box field.
     *
     * @param string $name
     * @param array $list
     * @param string $selected
     * @param array $attributes
     *
     * @return \Styde\Html\HtmlElement
     */
    public function select($name, $list = [], $selected = null, array $attributes = [])
    {
        return new HtmlElement('select', '', array_merge(compact('name'), $attributes));
    }

    /**
     * Create a time input field.
     *
     * @param string $name
     * @param string $value
     * @param array  $options
     *
     * @return \Styde\Html\HtmlElement
     */
    public function time($name, $value = null, $options = array())
    {
        return $this->input('time', $name, $value, $options);
    }

    /**
     * Create a button element.
     *
     * @param string $text
     * @param array $attributes
     *
     * @return \Styde\Html\HtmlElement
     */
    public function button($text = null, $attributes = [])
    {
        return new HtmlElement('button', $text, array_merge(['type' => 'button'], $attributes));
    }

    /**
     * Create a radio button input field.
     *
     * @param  string $name
     * @param  mixed  $value
     * @param  bool   $checked
     * @param  array  $attributes
     *
     * @return \Styde\Html\HtmlElement
     */
    public function radio($name, $value = null, $checked = false, $attributes = [])
    {
        $attributes = array_merge([
            'type' => 'radio',
            'name' => $name,
            'value' => $value,
            'checked' => $checked,
        ], $attributes);

        return new HtmlElement('input', false, $attributes);
    }

    /**
     * Create a checkbox input field.
     *
     * @param  string $name
     * @param  mixed  $value
     * @param  bool   $checked
     * @param  array  $attributes
     *
     * @return \Styde\Html\HtmlElement
     */
    public function checkbox($name, $value = 1, $checked = null, $attributes = [])
    {
        $attributes = array_merge([
            'type' => 'checkbox',
            'name' => $name,
            'value' => $value,
            'checked' => $checked,
        ], $attributes);

        return new HtmlElement('input', false, $attributes);
    }

    /**
     * Create a list of radios.
     *
     * @param string $name
     * @param array  $options
     * @param string $checked
     * @param array  $attributes
     *
     * @return string
     */
    public function radios($name, $options = array(), $checked = null, $attributes = array())
    {
        $checked = $this->getValueAttribute($name, $checked);

        $defaultTemplate = in_array('inline', $attributes) ? 'forms.radios-inline' : 'forms.radios';

        $template = $attributes['template'] ?? null;

        unset ($attributes['inline'], $attributes['template']);

        $labels = [];

        foreach ($options as $value => $text) {
            $id = $name.'_'.Str::slug($value);

            $radio = $this->radio($name, $value, $checked == $value, compact('id'));

            $labels[] = new HtmlElement('label', compact('text', 'radio'));
        }

        return $this->theme->render($template, compact('name', 'labels'), $defaultTemplate);
    }

    /**
     * Create a list of checkboxes.
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
        $selected = $this->getValueAttribute($name, $selected);

        if (is_null($selected)) {
            $selected = array();
        }

        $defaultTemplate = in_array('inline', $attributes)
            ? 'forms.checkboxes-inline'
            : 'forms.checkboxes';

        $template = isset($attributes['template'])
            ? $attributes['template']
            : null;

        $checkboxes = [];

        foreach ($options as $value => $label) {
            $checkboxes[] = [
                'name'    => $name.'[]',
                'value'   => $value,
                'label'   => $label,
                'checked' => is_array($selected) && in_array($value, $selected),
                'id'      => $name.'_'.Str::slug($value)
            ];
        }

        unset ($attributes['inline'], $attributes['template']);

        return $this->theme->render(
            $template,
            compact('name', 'checkboxes', 'attributes'),
            $defaultTemplate
        );
    }

    public function getValueAttribute($name, $value)
    {
        return $value;
    }
}
