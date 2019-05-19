<?php

namespace Styde\Html\FormModel;

trait AddsButtons
{
    /**
     * Add a submit button.
     *
     * @param  $text
     * @param  array  $attributes
     * @return Button
     */
    public function submit($text, $attributes = array())
    {
        return $this->add('submit', $text, $attributes);
    }

    /**
     * Add a button.
     *
     * @param  $text
     * @param  array  $attributes
     * @return Button
     */
    public function button($text, $attributes = array())
    {
        return $this->add('button', $text, $attributes);
    }

    /**
     * Add a reset button.
     *
     * @param  $text
     * @param  array $attributes
     * @return Button
     */
    public function reset($text, array $attributes = array())
    {
        return $this->add('reset', $text, $attributes);
    }
}
