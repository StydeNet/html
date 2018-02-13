<?php

namespace Styde\Html\Form;

class HiddenInput extends Input
{
    public function __construct($name, $value, array $attributes = [])
    {
        parent::__construct('hidden', $name, $value, $attributes);
    }
}
