<?php

namespace Styde\Html\FormModel;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Traits\Macroable;
use Styde\Html\{HtmlBuilder, FormBuilder};

class ButtonCollection
{
    use Macroable;

    /**
     * @var  \Styde\Html\FormBuilder
     */
    protected $formBuilder;
    /**
     * @var  \Styde\Html\HtmlBuilder
     */
    private $htmlBuilder;
    /**
     * @var  array
     */
    protected $buttons = [];

    /**
     * ButtonCollection constructor.
     *
     * @param  \Styde\Html\FormBuilder  $formBuilder
     * @param  \Styde\Html\HtmlBuilder  $htmlBuilder
     */
    public function __construct(FormBuilder $formBuilder, HtmlBuilder $htmlBuilder)
    {
        $this->formBuilder = $formBuilder;
        $this->htmlBuilder = $htmlBuilder;
    }

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

    /**
     * Add a button.
     *
     * @param  $type
     * @param  $text
     * @param  array  $attributes
     * @return Button
     */
    public function add($type, $text, array $attributes = array())
    {
        $attributes['type'] = $type;

        $this->buttons[] = $button = $this->formBuilder->button($text, $attributes);

        return $button;
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
        return $this->buttons[] = $this->htmlBuilder->link($url, $title, $attributes, $secure);
    }

    /**
     * Render all the buttons and links.
     *
     * @return string
     */
    public function render()
    {
        $html = '';

        foreach ($this->buttons as $button) {
            $html .= $button->render();
        }

        return new HtmlString($html);
    }

}