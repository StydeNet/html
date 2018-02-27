<?php

namespace Styde\Html\Form;

use Styde\Html\VoidTag;

class Input extends VoidTag
{
    public function __construct($type, $name, $value = null, array $attributes = [])
    {
        parent::__construct('input', array_merge(compact('type', 'name', 'value'), $attributes));
    }
}
