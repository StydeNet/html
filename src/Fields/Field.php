<?php

namespace Styde\Html\Fields;

use Closure;

class Field
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type;

    /**
     * @var mixed
     */
    public $value;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $helpText;

    /**
     * @var template
     */
    public $template;

    /**
     * @var array
     */
    public $attributes = [];

    /**
     * @var array
     */
    public $data = [];

    /**
     * @var array
     */
    public $styles = [];

    /**
     * @var array
     */
    public $scripts = [];

    /**
     * @var array|\Closure
     */
    public $options = [];

    /**
     * @var array
     */
    public $rules = [];

    /**
     * @var bool
     */
    public $controlOnly = false;

    /**
     * @var \Styde\Html\Transformer
     */
    public $transformer;

    /**
     * Field constructor.
     *
     * @param $name
     * @param string $type
     */
    public function __construct($name, $type = 'text')
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * Add a custom template to the field and optionally pass extra data to it.
     *
     * @param $template
     * @param array $vars
     *
     * @return $this
     */
    public function setTemplate($template, $vars = [])
    {
        $this->template = $template;
        $this->mergeData($vars);

        return $this;
    }

    /**
     * Set a new attribute in the field
     *
     * @param $name
     * @param null $value
     */
    public function setAttribute($name, $value = null)
    {
        if (! in_array($name, $this->attributes)) {
            if (! $value) {
                $this->attributes[] = $name;
            } else {
                $this->attributes[$name] = $value;
            }
        }
    }

    /**
     * Merge new attributes to the field attributes.
     *
     * @param array $attributes
     */
    public function mergeAttributes(array $attributes)
    {
        $this->attributes = array_merge($this->attributes, $attributes);
    }

    /**
     * Check if have attribute
     *
     * @param $name
     * @return bool
     */
    public function hasAttribute($name)
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * Add extra data for the field's template.
     *
     * @param $key
     * @param $value
     */
    public function setData($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Merge extra data for the field's template.
     *
     * @param array $data
     */
    public function mergeData(array $data)
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * Get the options from the field.
     *
     * @return array
     */
    public function getOptions()
    {
        if ($this->options instanceof Closure) {
            $options = $this->options;
            $this->options = $options();
        }

        return $this->options;
    }

    /**
     * Add a new rule to the field
     *
     * @param string $rule
     */
    public function addRule($rule)
    {
        if (is_object($rule)) {
            $key = get_class($rule);
        } else {
            $key = explode(':', $rule)[0];
        }

        $this->rules[$key] = $rule;
    }

    /**
     * Remove a rule from the field.
     *
     * @param $rule
     */
    public function removeRule($rule)
    {
        unset ($this->rules[$rule]);
    }

    /**
     * Remove all the rules from the field.
     *
     */
    public function removeAllRules()
    {
        $this->rules = [];
    }

    /**
     * Return the value that should be displayed in the form control.
     *
     * @return mixed
     */
    public function displayValue()
    {
        if ($this->transformer) {
            return $this->transformer->forDisplay($this->value);
        }

        return $this->value;
    }

    /**
     * Get all the validation rules of this field.
     *
     * @return array
     */
    public function getValidationRules()
    {
        return array_values($this->rules);
    }
}
