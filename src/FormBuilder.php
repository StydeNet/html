<?php

namespace Styde\Html;

use Styde\Html\Form\Input;
use Styde\Html\Form\HiddenInput;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Session\Session;
use Illuminate\Contracts\Routing\UrlGenerator;

class FormBuilder
{
    use Macroable {
        __call as macroCall;
    }

    protected $currentModel;

    protected $currentForm;

    /**
     * Whether to deactivate or not the HTML5 validation (in order to test
     * backend validation).
     *
     * @var bool $novalidate
     */
    protected $novalidate = false;

    /**
     * The Theme object in charge of rendering the right view for this theme
     *
     * @var \Styde\Html\Theme
     */
    protected $theme;

    protected $session;

    /**
     * Creates a new Form Builder class.
     *
     * @param \Illuminate\Contracts\Routing\UrlGenerator $url
     * @param \Styde\Html\Theme $theme
     * @param \Illuminate\Contracts\Session\Session $session
     */
    public function __construct(UrlGenerator $url, Theme $theme, $session)
    {
        $this->theme = $theme;
        $this->session = $session;
        $this->view = $theme->getView();
    }

    /**
     * Get the protected model attribute
     */
    public function getModel()
    {
        return $this->currentModel;
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
     * Makes a new Form Element.
     *
     * @param $method
     * @param array $attributes
     *
     * @return \Styde\Html\Form
     */
    public function make($method, $attributes = [])
    {
        if ($this->novalidate) {
            $attributes['novalidate'] = true;
        }

        $children = [];

        if ($method != 'get') {
            $children['_token'] = $this->hidden('_token', $this->session->token());
        }

        if (in_array($method, ['put', 'patch', 'delete'])) {
            $children['_method'] = $this->hidden('_method', $method);

            $method = 'post';
        }

        $attributes['method'] = $method;

        return $this->currentForm = new Form($method, $children, $attributes);
    }

    /**
     * Makes a new Form Element.
     *
     * @param array $attributes
     *
     * @return \Styde\Html\Form
     */
    public function get(array $attributes = [])
    {
        return $this->make('get', $attributes);
    }

    /**
     * Makes a new Form Element.
     *
     * @param array $attributes
     *
     * @return \Styde\Html\Form
     */

    public function post(array $attributes = [])
    {
        return $this->make('post', $attributes);
    }

    /**
     * Makes a new Form Element.
     *
     * @param array $attributes
     *
     * @return \Styde\Html\Form
     */
    public function put(array $attributes = [])
    {
        return $this->make('put', $attributes);
    }

    /**
     * Makes a new Form Element.
     *
     * @param array $attributes
     *
     * @return \Styde\Html\Form
     */
    public function delete(array $attributes = [])
    {
        return $this->make('delete', $attributes);
    }

    public function setCurrentModel($model)
    {
        $this->currentModel = $model;
    }

    public function clearCurrentModel()
    {
        $this->currentModel = null;
    }

    /**
     * Make a new form and render the open tag.
     *
     * @param array $attributes
     *
     * @return string
     */
    public function open(array $attributes = array())
    {
        return $this->make('get', $attributes)->open();
    }

    /**
     * Close the current form.
     *
     * @return string
     */
    public function close()
    {
        return $this->currentForm->close();
    }

    /**
     * Create a form label element.
     *
     * @param string $content
     * @param array $attributes
     *
     * @return \Styde\Html\Htmltag
     */
    public function label($content, $attributes = [])
    {
        return new Htmltag('label', $content, $attributes);
    }

    /**
     * Create a form input field.
     *
     * @param string $type
     * @param string $name
     * @param string $value
     * @param array  $attributes
     *
     * @return \Styde\Html\Htmltag
     */
    public function input($type, $name, $value = null, $attributes = [])
    {
        return new Input($type, $name, $this->getValueAttribute($name, $value), $attributes);
    }

    /**
     * Create a text input field.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $attributes
     *
     * @return \Styde\Html\Htmltag
     */
    public function text(string $name, $value = null, $attributes = [])
    {
        return $this->input('text', $name, $value, $attributes);
    }

    /**
     * Create a hidden input field.
     *
     * @param string $name
     * @param string $value
     * @param array  $attributes
     *
     * @return \Styde\Html\Htmltag
     */
    public function hidden($name, $value = null, $attributes = [])
    {
        return new HiddenInput($name, $value, $attributes);
    }

    /**
     * Create a textarea form field.
     *
     * @param string $name
     * @param string $value
     * @param array  $attributes
     *
     * @return \Styde\Html\Htmltag
     */
    public function textarea($name, $value = null, $attributes = [])
    {
        return new Htmltag('textarea', $this->getValueAttribute($name, $value), array_merge(compact('type', 'name'), $attributes));
    }

    /**
     * Create a select box field.
     *
     * @param string $name
     * @param array $list
     * @param string $selected
     * @param array $attributes
     *
     * @return \Styde\Html\Htmltag
     */
    public function select($name, $list = [], $selected = null, array $attributes = [])
    {
        return new Htmltag(
            'select',
            $this->options($list, $this->getValueAttribute($name, $selected)),
            array_merge(compact('name'), $attributes)
        );
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

        return new Htmltag('optgroup', $options, compact('label') + $attributes);
    }

    /**
     * Create an option element
     * @param string $text
     * @param mixed $value
     * @param bool $selected
     * @param array $attributes
     *
     * @return \Styde\Html\Htmltag
     */
    public function option($text, $value, $selected, array $attributes = [])
    {
        if (is_array($selected)) {
            $isSelected = in_array($value, $selected);
        } else {
            $isSelected = $value == $selected;
        }

        return new Htmltag('option', $text, ['value' => $value, 'selected' => $isSelected] + $attributes);
    }

    /**
     * Create a time input field.
     *
     * @param string $name
     * @param string $value
     * @param array  $options
     *
     * @return \Styde\Html\Htmltag
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
     * @return \Styde\Html\Htmltag
     */
    public function button($text = null, $attributes = [])
    {
        return new Htmltag('button', $text, array_merge(['type' => 'button'], $attributes));
    }

    /**
     * Create a radio button input field.
     *
     * @param  string $name
     * @param  mixed  $value
     * @param  bool   $checked
     * @param  array  $attributes
     *
     * @return \Styde\Html\Htmltag
     */
    public function radio($name, $value = null, $checked = false, $attributes = [])
    {
        $attributes = array_merge(['checked' => $checked], $attributes);

        return new Input('radio', $name, $value, $attributes);
    }

    /**
     * Create a checkbox input field.
     *
     * @param  string $name
     * @param  mixed  $value
     * @param  bool   $checked
     * @param  array  $attributes
     *
     * @return \Styde\Html\Htmltag
     */
    public function checkbox($name, $value = 1, $checked = null, $attributes = [])
    {
        $attributes = array_merge(['checked' => $checked], $attributes);

        return new Input('checkbox', $name, $value, $attributes);
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

    public function getValueAttribute($name, $value = null)
    {
        $old = $this->session->getOldInput($name);

        if ($old !== null) {
            return $old;
        }

        if ($value !== null) {
            return $value;
        }

        return $this->currentModel[$name] ?? null;
    }

    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        return $this->input($method, $parameters[0], $parameters[1] ?? null, $parameters[2] ?? []);
    }
}
