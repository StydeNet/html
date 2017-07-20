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
        // Render a single tag.
        if ($this->content === false) {
            return $this->open();
        }

        // Render a paired tag.
        return $this->open().$this->renderText($this->content).$this->close();
    }

    public function open()
    {
        return '<'.$this->tag.$this->renderAttributes().'>';
    }

    public function close()
    {
        return '</'.$this->tag.'>';
    }

    /**
     * Render the HTML attributes
     */
    public function renderAttributes()
    {
        $result = '';

        foreach ($this->attributes as $name => $value)
        {
            if ($attribute = $this->renderAttribute($name, $value)) {
                $result .= " $attribute";
            }
        }

        return $result;
    }

    /**
     * Render an individual attribute.
     *
     * @param mixed $name
     * @param mixed $value
     * @return string|null
     */
    protected function renderAttribute($name, $value)
    {
        if (is_numeric($name)) {
            return $value;
        }

        if ($value === true) {
            return $name;
        }

        if ($value !== false) {
            return $name.'="'.$this->renderText($value).'"';
        }
    }

    public function renderText($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
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