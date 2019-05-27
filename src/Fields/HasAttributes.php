<?php

namespace Styde\Html\Fields;

trait HasAttributes
{
    /**
     * @param $method
     * @param array $params
     * @return HasAttributes
     */
    public function __call($method, array $params)
    {
        return $this->attr($method, isset($params[0]) ? $params[0] : true);
    }

    /**
     * Set attribute id
     *
     * @param $id
     * @return HasAttributes
     */
    public function id($id)
    {
        return $this->field->setAttribute('id', $id);
    }

    /**
     * Set attribute class
     *
     * @param array $classes
     * @return HasAttributes
     */
    public function classes($classes)
    {
        return $this->field->setAttribute('class', $classes);
    }

    /**
     * Set a new attribute in the field and set all rules of the attributes
     *
     * @param array|string $attributes
     * @param string|null $value
     * @return $this
     */
    public function attr($attributes, $value = null)
    {
        if (is_array($attributes)) {
            $this->field->mergeAttributes($attributes);
        } else {
            $this->field->setAttribute($attributes, $value);
        }

        $this->addRulesOfAttributes();

        return $this;
    }

    /**
     * Check if have attribute
     *
     * @param $name
     * @return bool
     */
    public function hasAttribute($name)
    {
        return $this->field->hasAttribute($name);
    }
}
