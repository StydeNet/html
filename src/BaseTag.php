<?php

namespace Styde\Html;

use Illuminate\Contracts\Support\Htmlable;

abstract class BaseTag implements Htmlable
{
    protected $tag;

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


    public function attr($name, $value = true)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    public function classes($classes)
    {
        return $this->attr('class', app(HtmlBuilder::class)->classes((array) $classes, false));
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

    public function toHtml()
    {
        return (string) $this->render();
    }

    abstract public function render();

    public function __toString()
    {
        return $this->toHtml();
    }
}
