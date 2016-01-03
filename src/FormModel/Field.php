<?php

namespace Styde\Html\FormModel;

use Styde\Html\FieldBuilder;

class Field
{
    use HasAttributes;

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
     * @var mixed
     */
    protected $value;
    /**
     * @var string
     */
    protected $label;
    /**
     * @var template
     */
    protected $template;
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

    public function __toString()
    {
        return $this->render();
    }

    public function required($required = true)
    {
        if ($required) {
            $this->attributes['required'] = true;
        } else {
            unset($this->attributes['required']);
        }
        return $this;
    }

    public function label($label)
    {
        $this->label = $label;
        return $this;
    }

    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    public function template($template)
    {
        $this->template = $template;
        return $this;
    }

    public function extra($values, $value = true)
    {
        if (is_array($values)) {
            $this->extra = array_merge($this->extra, $values);
        } else {
            $this->extra[$values] = $value;
        }
        return $this;
    }

    public function options($options)
    {
        $this->options = $options;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getExtra()
    {
        return $this->extra;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function render()
    {
        return $this->fieldBuilder->render($this);
    }

}