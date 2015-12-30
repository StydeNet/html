<?php

namespace Styde\Html\FormModel;

use Styde\Html\FieldBuilder;

class FieldCollection
{
    /**
     * @var \Styde\Html\FieldBuilder
     */
    protected $fieldBuilder;
    /**
     * @var array
     */
    protected $fields = [];

    public function __construct(FieldBuilder $fieldBuilder)
    {
        $this->fieldBuilder = $fieldBuilder;
    }

    public function __toString()
    {
        return $this->render();
    }

    public function __get($name)
    {
        return $this->fields[$name];
    }

    public function text($name)
    {
        return $this->add($name, 'text');
    }

    public function email($name)
    {
        return $this->add($name, 'email');
    }

    public function password($name)
    {
        return $this->add($name, 'password');
    }

    public function checkbox($name, $value = 1)
    {
        return $this->add($name, 'checkbox')->options($value);
    }

    public function select($name, array $options = array())
    {
        return $this->add($name, 'select')->options($options);
    }

    public function radios($name, array $options = array())
    {
        return $this->add($name, 'radios')->options($options);
    }

    public function checkboxes($name, array $options = array())
    {
        return $this->add($name, 'checkboxes')->options($options);
    }

    public function add($name, $type = 'text')
    {
        return $this->fields[$name] = new Field($this->fieldBuilder, $name, $type);
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function render()
    {
        $html = '';

        foreach ($this->getFields() as $field) {
            $html .= $field->render();
        }

        return $html;
    }

}