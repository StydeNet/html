<?php

namespace Styde\Html\FormModel;

use Styde\Html\FormBuilder;

class Button
{
    use HasAttributes;

    /**
     * @var FormBuilder
     */
    protected $formBuilder;
    protected $type;
    protected $text;
    protected $attributes;

    public function __construct(FormBuilder $formBuilder, $type, $text, array $attributes = array())
    {
        $this->formBuilder = $formBuilder;

        $this->type = $type;
        $this->text = $text;
        $this->attributes = $attributes;
    }

    public function render()
    {
        return $this->formBuilder->button(
            $this->text,
            array_merge($this->attributes, ['type' => $this->type])
        );
    }

}