<?php

namespace Styde\Html;

use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\HtmlString;

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
     * @param string $content
     * @param array $attributes
     *
     * @return \Styde\Html\HtmlElement
     */
    public function label($content, $attributes = [])
    {
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
        return new HtmlElement('select', $this->options($list, $selected), array_merge(compact('name'), $attributes));
    }

    public function options($list, $selected, array $attributes = [])
    {
        $options = [];

        foreach ($list as $value => $text) {
            if (is_array($text)) {
                $options[] = $this->optionGroup($value, $text, $selected, $attributes);
            } else {
                $options[] = $this->option($text, $value, $selected, $attributes);
            }
        }

        return $options;
    }

    /**
     * Create an option group form element.
     *
     * @param  array  $list
     * @param  string $label
     * @param  string $selected
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function optionGroup($label, $list, $selected, array $attributes = [])
    {
        $options = [];

        foreach ($list as $value => $text) {
            $options[] = $this->option($text, $value, $selected, $attributes);
        }

        return new HtmlElement('optgroup', $options, compact('label') + $attributes);
    }

    /**
     * Create an option element
     * @param string $text
     * @param mixed $value
     * @param bool $selected
     * @param array $attributes
     *
     * @return \Styde\Html\HtmlElement
     */
    public function option($text, $value, $selected, array $attributes = [])
    {
        if (is_array($selected)) {
            $isSelected = in_array($value, $selected);
        } else {
            $isSelected = $value == $selected;
        }

        return new HtmlElement('option', $text, ['value' => $value, 'selected' => $isSelected] + $attributes);
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

        $radios = [];

        foreach ($options as $value => $text) {
            $id = $name.'_'.str_slug($value,'_');

            $radios[] = [
                $this->radio($name, $value, $checked == $value, ['id' => $id]),
                $this->label($text, ['for' => $id]),
            ];
        }

        return new HtmlString(
            $this->theme->render($template, compact('name', 'radios'), $defaultTemplate)
        );
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
            $id = $name.'_'.str_slug($value,'_');

            $checkboxes[] = [
                $this->checkbox($name.'[]', $value, in_array($value, $selected), ['id' => $id]),
                $this->label($label, ['for' => $id]),
            ];
        }

        return new HtmlString(
            $this->theme->render($template, compact('name', 'checkboxes', 'attributes'), $defaultTemplate)
        );
    }

    public function getValueAttribute($name, $value)
    {
        return $value;
    }
}
