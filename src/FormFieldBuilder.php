<?php

namespace Styde\Html;

use Styde\Html\Facades\Form;
use Styde\Html\Fields\FieldBuilder;
use Illuminate\Support\Traits\Macroable;

class FormFieldBuilder
{
    use Macroable {
        Macroable::__call as macroCall;
    }

    /**
     * Dynamically handle calls to the field builder. The method's name will be used as the input type
     *
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if ($this->hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        return call_user_func_array([$this, 'build'], array_merge([$method], $parameters));
    }

    /**
     * Create a form input field.
     *
     * @param string $type
     * @param string $name
     * @param string $value
     * @param array $attributes
     * @param array $extra
     *
     * @return string
     */
    public function input($type, $name, $value = null, array $attributes = [], array $extra = [])
    {
        return $this->swapAndBuild($type, $name, $value, $attributes, $extra);
    }

    /**
     * Create a text input field.
     *
     * @param string $name
     * @param string $value
     * @param array $attributes
     * @param array $extra
     * @return string
     */
    public function text($name, $value = null, array $attributes = [], array $extra = [])
    {
        return $this->swapAndBuild('text', $name, $value, $attributes, $extra);
    }

    /**
     * Create a password input field.
     *
     * @param string $name
     * @param array  $attributes
     * @param array  $extra
     * @return string
     */
    public function password($name, array $attributes = [], array $extra = [])
    {
        return $this->build('password', $name, '', $attributes, $extra);
    }

    /**
     * Create a hidden input field.
     *
     * @param string $name
     * @param string $value
     * @param array $attributes
     * @return string
     */
    public function hidden($name, $value = null, array $attributes = [])
    {
        return Form::input('hidden', $name, $value, $attributes);
    }

    /**
     * Create an e-mail input field.
     *
     * @param string $name
     * @param string $value
     * @param array $attributes
     * @param array $extra
     * @return string
     */
    public function email($name, $value = null, array $attributes = [], array $extra = [])
    {
        return $this->swapAndBuild('email', $name, $value, $attributes, $extra);
    }

    /**
     * Create a URL input field.
     *
     * @param string $name
     * @param string $value
     * @param array $attributes
     * @param array $extra
     * @return string
     */
    public function url($name, $value = null, array $attributes = [], array $extra = [])
    {
        return $this->swapAndBuild('url', $name, $value, $attributes, $extra);
    }

    /**
     * Create a file input field.
     *
     * @param string $name
     * @param array $attributes
     * @param array $extra
     * @return string
     */
    public function file($name, array $attributes = [], array $extra = [])
    {
        return $this->build('file', $name, null, $attributes, $extra);
    }

    /**
     * Create a textarea input field.
     *
     * @param string $name
     * @param string $value
     * @param array $attributes
     * @param array $extra
     * @return string
     */
    public function textarea($name, $value = null, array $attributes = [], array $extra = [])
    {
        return $this->swapAndBuild('textarea', $name, $value, $attributes, $extra);
    }

    /**
     * Create a radios field.
     *
     * @param string $name
     * @param array $options
     * @param string $selected
     * @param array $attributes
     * @param array $extra
     * @return string
     */
    public function radios($name, $options = array(), $selected = null, array $attributes = [], array $extra = [])
    {
        return $this->swapAndBuild('radios', $name, $selected, $attributes, $extra, $options);
    }

    /**
     * Create a select box field.
     *
     * @param string $name
     * @param array $options
     * @param string $selected
     * @param array $attributes
     * @param array $extra
     * @return string
     */
    public function select($name, $options = [], $selected = null, array $attributes = [], array $extra = [])
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
     * Create a multiple select field.
     *
     * @param $name
     * @param array $options
     * @param array $selected
     * @param array $attributes
     * @param array $extra
     * @return string
     */
    public function selectMultiple($name, $options = [], $selected = null, array $attributes = [], array $extra = [])
    {
        $attributes[] = 'multiple';
        return $this->build('select', $name, $selected, $attributes, $extra, $options);
    }

    /**
     * Create a checkboxes field.
     *
     * @param string $name
     * @param array $options
     * @param string $selected
     * @param array $attributes
     * @param array $extra
     * @return string
     */
    public function checkboxes($name, array $options = [], $selected = null, array $attributes = [], array $extra = [])
    {
        return $this->build('checkboxes', $name, $selected, $attributes, $extra, $options);
    }

    /**
     * Create a checkbox input field.
     *
     * @param string $name
     * @param mixed $value
     * @param null $selected
     * @param array $attributes
     * @param array $extra
     * @return string
     */
    public function checkbox($name, $value = 1, $selected = null, array $attributes = [], array $extra = [])
    {
        return $this->swapAndBuild('checkbox', $name, $selected, $attributes, $extra, $value);
    }

    /**
     * Swap values ($value and $attributes) if necessary, then call build.
     *
     * @param string $type
     * @param string $name
     * @param mixed|null $value
     * @param array $attributes
     * @param array|null $options
     * @param array $extra
     * @return string
     */
    protected function swapAndBuild($type, $name, $value = null, array $attributes = array(), array $extra = array(), $options = null)
    {
        /**
         * Swap values so programmers can skip the $value argument and pass the $attributes array directly.
         */
        if (is_array($value)) {
            $extra = $attributes;
            $attributes = $value;
            $value = null;
        }

        return $this->build($type, $name, $value, $attributes, $extra, $options);
    }

    /**
     * Build and render a field.
     *
     * @param string $type
     * @param string $name
     * @param mixed $value
     * @param array $attributes
     * @param array $extra
     * @param array|null $options
     * @return string
     */
    public function build($type, $name, $value = null, array $attributes = [], array $extra = [], $options = null)
    {
        $field = new FieldBuilder($name, $type);

        if (isset($attributes['required']) && ! $attributes['required']) {
            unset($attributes['required']);
        }

        $this->setCustomAttributes($attributes, $field);

        $field->value($value)
            ->attr($attributes)
            ->with($extra);

        if (is_array($options) && !empty($options)) {
            $field->options($options);
        }

        return $field;
    }

    /**
     * Set custom attributes in the field using the FieldBuilder fluent methods.
     *
     * @param $attributes
     * @param $field
     */
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
}
