<?php

namespace Styde\Html\Fields;

use Styde\Html\Form\HiddenInput;

class HiddenFieldBuilder extends FieldBuilder
{
    /**
     * HiddenField constructor.
     *
     * @param \Styde\Html\FormFieldBuilder $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->type = 'hidden';
    }

    public function render()
    {
        return (string) (new HiddenInput($this->name, $this->value));
    }
}
