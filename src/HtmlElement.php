<?php

namespace Styde\Html;

class HtmlElement
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
     * @param string $content
     * @param array $attributes
     */
    public function __construct($tag, $content, array $attributes = [])
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
        return '<'.$this->tag.$this->renderAttributes().'>'.$this->content.'</'.$this->tag.'>';
    }

    /**
     * Render the HTML attributes
     */
    public function renderAttributes()
    {
        $result = '';

        foreach ($this->attributes as $name => $value)
        {
            $result .= $this->renderAttribute($name, $value);
        }

        return $result;
    }

    protected function renderAttribute($name, $value)
    {
        if (is_numeric($name)) {
            return ' '.$value;
        }

        if ($value === true) {
            return ' '.$name;
        }

        if ($value === false) {
            return '';
        }

        return ' '.$name.'="'.e($value).'"';
    }

    public function __call($method, array $parameters)
    {
        return $this->attr($method, $parameters[0] ?? true);
    }

    public function __toString()
    {
        return $this->render();
    }
}