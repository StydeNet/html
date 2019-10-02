<?php

namespace Styde\Html;

use Styde\Html\Form\Input;
use Styde\Html\Form\HiddenInput;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Session\Session;

class FormBuilder
{
    use Macroable {
        __call as macroCall;
    }
    /**
     * The current model instance for the form.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $currentModel;

    /**
     * The current form instance.
     *
     * @var \Styde\Html\Form
     */
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

    /**
     * The session store instance.
     *
     * @var \Illuminate\Contracts\Session\Session
     */
    protected $session;

    /**
     * Creates a new Form Builder class.
     *
     * @param \Styde\Html\Theme $theme
     * @param \Illuminate\Contracts\Session\Session $session
     */
    public function __construct(Theme $theme, Session $session)
    {
        $this->theme = $theme;
        $this->session = $session;
    }

    /**
     * Get the current model instance for the form.
     */
    public function getModel()
    {
        return $this->currentModel;
    }

    /**
     * Set a model instance for the form.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function setCurrentModel($model)
    {
        $this->currentModel = $model;
    }

    /**
     * Remove the model instance for the form
     */
    public function clearCurrentModel()
    {
        $this->currentModel = null;
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
     * @param string $method
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

        return $this->currentForm = new Form($children, $attributes);
    }

    /**
     * Makes a new get Form Element.
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
     * Makes a new post Form Element.
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
     * Makes a new put Form Element.
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
     * Makes a new delete Form Element.
     *
     * @param array $attributes
     *
     * @return \Styde\Html\Form
     */
    public function delete(array $attributes = [])
    {
        return $this->make('delete', $attributes);
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
     * @return \Styde\Html\VoidTag
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
     * @return \Styde\Html\VoidTag
     */
    public function text(string $name, $value = null, $attributes = [])
    {
        return $this->input('text', $name, $value, $attributes);
    }

    /**
     * Create a password input field.
     *
     * @param  string $name
     * @param  array  $attributes
     *
     * @return \Styde\Html\VoidTag
     */
    public function password(string $name, $attributes = [])
    {
        return new Input('password', $name, null, $attributes);
    }

    /**
     * Create a file input field.
     *
     * @param  string $name
     * @param  array  $attributes
     *
     * @return \Styde\Html\VoidTag
     */
    public function file(string $name, $attributes = [])
    {
        return new Input('file', $name, null, $attributes);
    }

    /**
     * Create a hidden input field.
     *
     * @param string $name
     * @param string $value
     * @param array  $attributes
     *
     * @return \Styde\Html\VoidTag
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
        return new Htmltag(
            'textarea',
            $this->getValueAttribute($name, $value),
            array_merge(compact('name'), $attributes)
        );
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

    /**
     * Create the options for a select element.
     *
     * @param  array $list
     * @param  string $selected
     * @param  array  $attributes
     *
     * @return array
     */
    protected function options($list, $selected, array $attributes = [])
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
     * @return \Styde\Html\Htmltag
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
     * Create an option element.
     *
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
     * @return \Styde\Html\VoidTag
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
     * @return \Styde\Html\VoidTag
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
     * @return \Styde\Html\VoidTag
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
     * @return \Illuminate\Support\HtmlString
     */
    public function radios($name, $options = [], $checked = null, $attributes = [])
    {
        $checked = $this->getValueAttribute($name, $checked);

        if (empty($attributes['template'])) {
            $template = in_array('inline', $attributes) ? '@forms.radios-inline' : '@forms.radios';
        } else {
            $template = $attributes['template'];
        }

        $radios = [];

        foreach ($options as $value => $text) {
            $id = $name.'_'.Str::slug($value, '_');

            $radios[] = [
                $this->radio($name, $value, $checked == $value, ['id' => $id]),
                $this->label($text, ['for' => $id]),
            ];
        }

        return new HtmlString(
            $this->theme->render($template, compact('name', 'radios'))
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
     * @return \Illuminate\Support\HtmlString
     */
    public function checkboxes($name, $options = array(), $selected = null, $attributes = [])
    {
        $selected = $this->getValueAttribute($name, $selected);

        if (is_null($selected)) {
            $selected = [];
        }

        if (empty($attributes['template'])) {
            $template = in_array('inline', $attributes) ? '@forms.checkboxes-inline' : '@forms.checkboxes';
        } else {
            $template = $attributes['template'];
        }

        $checkboxes = [];

        foreach ($options as $value => $label) {
            $id = $name.'_'.Str::slug($value, '_');

            $checkboxes[] = [
                $this->checkbox($name.'[]', $value, in_array($value, $selected), ['id' => $id]),
                $this->label($label, ['for' => $id]),
            ];
        }

        return new HtmlString(
            $this->theme->render($template, compact('name', 'checkboxes', 'attributes'))
        );
    }

    /**
     * Get the value attribute of a field.
     *
     * @param  string $name
     * @param  string $value
     *
     * @return mixed
     */
    public function getValueAttribute($name, $value = null)
    {
        if ($this->session->hasOldInput()) {
            return $this->session->getOldInput($name);
        }

        if ($value !== null) {
            return $value;
        }

        return $this->currentModel[$name] ?? null;
    }

    /**
     * Handle dynamic calls to the form
     *
     * @param  string $method
     * @param  array $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        return $this->input($method, $parameters[0], $parameters[1] ?? null, $parameters[2] ?? []);
    }
}
