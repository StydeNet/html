<?php

namespace Styde\Html\FormModel;

use Styde\Html\Form\HiddenInput;

class HiddenField extends Field
{
    /**
     * HiddenField constructor.
     *
     * @param \Styde\Html\FieldBuilder $name
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
