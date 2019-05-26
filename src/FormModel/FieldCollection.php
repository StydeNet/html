<?php

namespace Styde\Html\FormModel;

use Styde\Html\FieldBuilder;
use Illuminate\Support\Traits\Macroable;

class FieldCollection extends ElementCollection
{
    use Macroable;

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
        return $this->add(new Field($name, $type), $name);
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
