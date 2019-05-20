<?php

namespace Styde\Html\FormModel;

use Styde\Html\FieldBuilder;
use Illuminate\Support\Traits\Macroable;

class FieldCollection extends ElementCollection
{
    use Macroable;

    /**
     * @var \Styde\Html\FieldBuilder
     */
    protected $fieldBuilder;

    /**
     * Creates a new FieldCollection class.
     *
     * @param \Styde\Html\FieldBuilder $fieldBuilder
     */
    public function __construct(FieldBuilder $fieldBuilder)
    {
        $this->fieldBuilder = $fieldBuilder;
    }

    /**
     * Add a field to the elements array.
     *
     * @param string $name
     * @param string $type
     *
     * @return \Styde\Html\FormModel\Field
     */
    public function addField($name, $type = 'text')
    {
        return $this->add(new Field($this->fieldBuilder, $name, $type), $name);
    }

    /**
     * Get all the fields from the elements array.
     *
     * @return array
     */
    public function onlyFields()
    {
        return $this->filter(function ($field) {
            return $field instanceof Field;
        });
    }
}
