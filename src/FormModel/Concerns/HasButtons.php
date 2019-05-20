<?php

namespace Styde\Html\FormModel\Concerns;

trait HasButtons
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
        return $this->addButton('submit', $text, $attributes);
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
        return $this->addButton('button', $text, $attributes);
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
        return $this->addButton('reset', $text, $attributes);
    }

    /**
     * Add a button.
     *
     * @param  $type
     * @param  $text
     * @param  array  $attributes
     * @return Button
     */
    public function addButton($type, $text, array $attributes = array())
    {
        $attributes['type'] = $type;

        return $this->buttons->add($this->formBuilder->button($text, $attributes));
    }

    /**
     * Add a link.
     *
     * @param  $url
     * @param  $title
     * @param  array  $attributes
     * @param  bool  $secure
     * @return Link
     */
    public function link($url, $title = null, array $attributes = array(), $secure = false)
    {
        return $this->buttons->add($this->htmlBuilder->link($url, $title, $attributes, $secure));
    }

    /**
     * Render all the elements in the buttons collection.
     *
     * @return string
     */
    public function renderButtons()
    {
        return $this->buttons->render();
    }
}
