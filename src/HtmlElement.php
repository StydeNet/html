<?php

namespace Styde\Html;

use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Support\Htmlable;

class HtmlElement extends VoidElement
{
    /**
     * The content of the HTML element.
     *
     * @var string
     */
    protected $content = [];

    /**
     * HtmlElement constructor.
     *
     * @param string $tag
     * @param string|array $content
     * @param array $attributes
     */
    public function __construct($tag, $content = '', array $attributes = [])
    {
        parent::__construct($tag, $attributes);

        $this->content = $content;
    }

    /**
     * Add a child to the HTML element.
     *
     * @param  \Styde\Html\HtmlElement $child
     * @return $this
     */
    public function add(HtmlElement $child)
    {
        $this->content[] = $child;
    }

    /**
     * Assign an attribute to the HTML element.
     *
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function attr($name, $value = true)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    public function classes($classes)
    {
        return $this->attr('class', app(HtmlBuilder::class)->classes((array) $classes, false));
    }

    /**
     * Render the HTML element.
     *
     * @return string
     */
    public function render()
    {
        // Render a paired tag.
        return new HtmlString($this->renderOpenTag() . $this->renderContent() . $this->renderCloseTag());
    }

    public function open()
    {
        return new HtmlString($this->renderOpenTag());
    }

    public function close()
    {
        return new HtmlString($this->renderCloseTag());
    }

    protected function renderOpenTag()
    {
        return '<'.$this->tag.$this->renderAttributes().'>';
    }

    public function renderContent()
    {
        $result = '';

        foreach ((array) $this->content as $content) {
            $result .= e($content);
        }

        return $result;
    }

    protected function renderCloseTag()
    {
        return '</'.$this->tag.'>';
    }
}
