<?php

namespace Styde\Html\FormModel;

use Styde\Html\FieldBuilder;

class Field
{
    /**
     * @var \Styde\Html\FieldBuilder
     */
    protected $fieldBuilder;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $type;
    /**
     * @var array
     */
    protected $attributes = array();
    /**
     * @var array
     */
    protected $extra = array();
    /**
     * @var array
     */
    protected $options = array();

    public function __construct(FieldBuilder $fieldBuilder, $name, $type = 'text')
    {
        $this->fieldBuilder = $fieldBuilder;

        $this->name = $name;
        $this->type = $type;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function required($required = true)
    {
        if ($required) {
            $this->attributes['required'] = true;
        } else {
            unset($this->attributes['required']);
        }
    }

    public function label($label)
    {
        $this->attributes['label'] = $label;
    }

    public function classes($classes)
    {
        $this->attributes['class'] = $classes;
    }

    public function template($template)
    {
        $this->attributes['template'] = $template;
    }

    public function attr($attributes, $value = null)
    {
        $this->attributes($attributes, $value);
    }

    public function attributes($attributes, $value = null)
    {
        if (is_array($attributes)) {
            $this->attributes = array_merge($this->attributes, $attributes);
        } else {
            $this->attributes[$attributes] = $value;
        }
    }

    public function extra(array $values, $value = null)
    {
        if (is_array($values)) {
            $this->extra = array_merge($this->extra, $values);
        } else {
            $this->extra[$values] = $value;
        }
    }

    public function options(array $options = array())
    {
        $this->options = $options;
        return $this;
    }

    public function render()
    {
        return $this->fieldBuilder->build(
            $this->type,
            $this->name,
            null,
            $this->attributes,
            $this->extra,
            $this->options
        );
    }

}