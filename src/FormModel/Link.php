<?php

namespace Styde\Html\FormModel;

use Styde\Html\HtmlBuilder;
use Styde\Html\Fields\HasAttributes;

class Link
{
    use HasAttributes;

    /**
     * @var \Styde\Html\HtmlBuilder
     */
    protected $htmlBuilder;
    private $text;
    private $url;
    /**
     * @var array
     */
    protected $attributes;
    protected $secure = false;

    /**
     * Link constructor.
     * @param HtmlBuilder $htmlBuilder
     * @param string $url
     * @param string $title
     * @param array $attributes
     * @param bool $secure
     */
    public function __construct(HtmlBuilder $htmlBuilder, $url, $title, array $attributes = array(), $secure = false)
    {
        $this->htmlBuilder = $htmlBuilder;

        $this->url = $url;
        $this->title = $title;
        $this->attributes = $attributes;
        $this->secure = $secure;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * @param bool $secure
     */
    public function secure($secure = true)
    {
        $this->secure = $secure;
    }

    /**
     * @return \Illuminate\Support\HtmlString
     */
    public function render()
    {
        return Html::link($this->url, $this->title, $this->attributes, $this->secure);
    }
}
