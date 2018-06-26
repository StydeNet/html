<?php

namespace Styde\Html;

use Illuminate\Contracts\Support\Htmlable;

abstract class BaseTag implements Htmlable
{
    /**
     * @var string
     */
    protected $tag;

    /**
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
     * Set a new attribute
     *
     * @param string $name
     * @param bool $value
     * @return $this
     */
    public function attr($name, $value = true)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * Set a new attribute with all the classes
     *
     * @param array $classes
     * @return BaseTag
     */
    public function classes($classes)
    {
        return $this->attr('class', app(HtmlBuilder::class)->classes((array) $classes, false));
    }

    /**
     * Render all the attributes in the tag
     *
     * @return string
     */
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

    /**
     * @param string $name
     * @return mixed|string
     */
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

        if ($name == 'value' && $this->tag == 'option') {
            return $name.'=""';
        }

        return '';
    }

    /**
     * @param string $value
     * @return string
     */
    public function escape($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
    }

    /**
     * @param string $method
     * @param array $parameters
     * @return BaseTag
     */
    public function __call($method, array $parameters)
    {
        return $this->attr($method, $parameters[0] ?? true);
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        return (string) $this->render();
    }

    /**
     * @return mixed
     */
    abstract public function render();

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toHtml();
    }
}
