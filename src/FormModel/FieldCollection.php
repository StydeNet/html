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