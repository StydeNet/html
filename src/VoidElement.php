<?php

namespace Styde\Html;

use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Support\Htmlable;

class VoidElement implements Htmlable
{
    /**
     * The name of the HTML tag.
     *
     * @var string
     */
    protected $tag;

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
     * @param array $attributes
     */
    public function __construct($tag, array $attributes = [])
    {
        $this->tag = $tag;
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
        return new HtmlString('<'.$this->tag.$this->renderAttributes().'>');
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
        return $this->toHtml();
    }

    /**
     * Get content as a string of HTML.
     *
     * @return string
     */
    public function toHtml()
    {
        return (string) $this->render();
    }
}
