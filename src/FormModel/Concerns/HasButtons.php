<?php

namespace Styde\Html\FormModel\Concerns;

trait HasButtons
{
    /**
     * Add a submit button.
     *
     * @param  string $text
     * @param  array  $attributes
     *
     * @return \Styde\Html\Htmltag
     */
    public function submit($text, $attributes = array())
    {
        return $this->addButton('submit', $text, $attributes);
    }

    /**
     * Add a button.
     *
     * @param  string $text
     * @param  array  $attributes
     *
     * @return \Styde\Html\Htmltag
     */
    public function button($text, $attributes = array())
    {
        return $this->addButton('button', $text, $attributes);
    }

    /**
     * Add a reset button.
     *
     * @param  string $text
     * @param  array $attributes
     *
     * @return \Styde\Html\Htmltag
     */
    public function reset($text, array $attributes = array())
    {
        return $this->addButton('reset', $text, $attributes);
    }

    /**
     * Add a button.
     *
     * @param  string $type
     * @param  string $text
     * @param  array  $attributes
     *
     * @return \Styde\Html\Htmltag
     */
    public function addButton($type, $text, array $attributes = array())
    {
        $attributes['type'] = $type;

        return $this->buttons->add($this->formBuilder->button($text, $attributes));
    }

    /**
     * Add a link.
     *
     * @param  string $url
     * @param  string $title
     * @param  array  $attributes
     * @param  bool  $secure
     *
     * @return \Styde\Html\Htmltag
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
