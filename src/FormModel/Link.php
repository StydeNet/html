<?php

namespace Styde\Html\FormModel;

use Styde\Html\HtmlBuilder;

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

    public function __construct(HtmlBuilder $htmlBuilder, $url, $title, array $attributes = array(), $secure = false)
    {
        $this->htmlBuilder = $htmlBuilder;

        $this->url = $url;
        $this->title = $title;
        $this->attributes = $attributes;
        $this->secure = $secure;
    }

    public function __toString()
    {
        return (string) $this->render();
    }

    public function secure($secure = true)
    {
        $this->secure = $secure;
    }

    /**
     * @return \Illuminate\Support\HtmlString
     */
    public function render()
    {
        return $this->htmlBuilder->link($this->url, $this->title, $this->attributes, $this->secure);
    }

}