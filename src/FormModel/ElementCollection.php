<?php

namespace Styde\Html\FormModel;

use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Support\Htmlable;

class ElementCollection
{
    protected $elements = [];

    /**
     * Add an element to the elements array.
     *
     * @param Htmlable $element
     * @param null $key
     *
     * @return FieldBuilder
     */
    public function add(Htmlable $element, $key = null)
    {
        if ($key) {
            return $this->elements[$key] = $element;
        } else {
            return $this->elements[] = $element;
        }
    }

    /**
     * Returns true if there are no elements in the collection, false otherwise.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->elements);
    }

    /**
     * Get the all the elements.
     *
     * @return array
     */
    public function all()
    {
        return $this->elements;
    }

    /**
     * Filter the elements by the given function.
     *
     * @param callable $callable
     * @return array
     */
    public function filter(callable $callable)
    {
        return array_filter($this->elements, $callable);
    }

    /**
     * Render all elements in the collection.
     *
     * @return string
     */
    public function render()
    {
        return new HtmlString(array_reduce($this->all(), function ($result, $element) {
            return $result.$element->render();
        }), '');
    }

    /**
     * Convert the element collection to string by rendering all its elements.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Get a field by name.
     *
     * @param  string $name
     *
     * @return FieldBuilder
     */
    public function get($name)
    {
        return $this->elements[$name];
    }

    /**
     * Get a field by name.
     *
     * @param  string $name
     *
     * @return FieldBuilder
     */
    public function __get($name)
    {
        return $this->get($name);
    }
}
