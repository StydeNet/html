<?php

namespace Styde\Html;

use Illuminate\Contracts\Support\Htmlable;

abstract class BaseTag implements Htmlable
{
    use HandlesAccess;

    /**
     * The name of the tag.
     *
     * @var string
     */
    protected $tag;

    /**
     * The attributes of the tag.
     *
     * @var array
     */
    protected $attributes;

    /**
     * Create a new HTML tag.
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
     * Set a new attribute.
     *
     * @param string $name
     * @param bool|string $value
     * @return $this
     */
    public function attr($name, $value = true)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * Removes an attribute.
     *
     * @param string $name
     * @return $this
     */
    public function removeAttr($name)
    {
        unset($this->attributes[$name]);
        $this->attributes = array_diff_key($this->attributes, ['required']);

        return $this;
    }

    /**
     * Set a new attribute with all the classes.
     *
     * @param array $classes
     * @return BaseTag
     */
    public function classes($classes)
    {
        return $this->attr('class', app(HtmlBuilder::class)->classes((array) $classes, false));
    }

    /**
     * Render all the attributes in the tag.
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
     * Render the open tag with the attributes.
     *
     * @return string
     */
    public function renderOpenTag()
    {
        return '<'.$this->tag.$this->renderAttributes().'>';
    }

    /**
     * Get a rendered attribute of the tag.
     *
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
     * Encode HTML special characters in a string.
     *
     * @param string $value
     * @return string
     */
    public function escape($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
    }

    /**
     *  Handle dynamic calls to add tag attributes.
     *
     * @param string $method
     * @param array $parameters
     * @return BaseTag
     */
    public function __call($method, array $parameters)
    {
        return $this->attr($method, $parameters[0] ?? true);
    }

    /**
     * Render the tag into HTML.
     *
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
     * Get the HTML representation of the tag.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toHtml();
    }

    /**
     * Get an attribute of the tag.
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        throw new \InvalidArgumentException("The property $name does not exist in this [{$this->tag}] element");
    }
}
