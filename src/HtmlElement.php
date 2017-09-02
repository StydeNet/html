<?php

namespace Styde\Html;

use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Support\Htmlable;

class HtmlElement implements Htmlable
{
    /**
     * The name of the HTML tag.
     *
     * @var string
     */
    protected $tag;

    /**
     * The content of the HTML element.
     *
     * @var string
     */
    protected $content;

    /**
     * The HTML attributes.
     *
     * @var array
     */
    protected $attributes;

    /**
     * HtmlElement constructor.
     *
     * @param string $tag
     * @param string|array $content
     * @param array $attributes
     */
    public function __construct($tag, $content = '', array $attributes = [])
    {
        $this->tag = $tag;
        $this->content = $content;
        $this->attributes = $attributes;
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

    /**
     * Render the HTML element.
     *
     * @return string
     */
    public function render()
    {
        // Render a single tag.
        if ($this->content === false) {
            return $this->open();
        }

        // Render a paired tag.
        return new HtmlString($this->renderOpenTag().$this->renderContent().$this->renderCloseTag());
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

    public function renderAttributes()
    {
        $result = '';

        foreach ($this->attributes as $name => $value) {
            if ($attribute = $this->renderAttribute($name)) {
                $result .= " {$attribute}";
            }
        }

        return $result;
    }

    protected function renderAttribute($name)
    {
        $value = $this->attributes[$name];

        if (is_numeric($name)) {
            return $value;
        }

        if ($value === true) {
            return $name;
        }

        if ($value) {
            return $name.'="'.$this->escape($value).'"';
        }

        return '';
    }

    public function escape($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
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

    public function __call($method, array $parameters)
    {
        return $this->attr($method, $parameters[0] ?? true);
    }

    public function __get($name)
    {
        if (isset ($this->content[$name])) {
            return $this->content[$name];
        }

        throw new \InvalidArgumentException("The property $name does not exist in this [{$this->tag}] element");
    }

    public function __toString()
    {
        return $this->render();
    }

    /**
     * Get content as a string of HTML.
     *
     * @return string
     */
    public function toHtml()
    {
        return $this->render();
    }
}