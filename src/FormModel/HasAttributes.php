<?php

namespace Styde\Html\FormModel;

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
        return $this->attributes('id', $id);
    }

    /**
     * Set attribute class
     *
     * @param array $classes
     * @return HasAttributes
     */
    public function classes($classes)
    {
        return $this->attributes('class', $classes);
    }

    /**
     * Set a new attribute in the field
     *
     * @param $name
     * @param null $value
     */
    protected function setAttribute($name, $value = null)
    {
        if (! in_array($name, $this->attributes)) {
            if (! $value) {
                $this->attributes[] = $name;
            } else {
                $this->attributes[$name] = $value;
            }
        }
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
            $this->attributes = array_merge($this->attributes, $attributes);
        } else {
            $this->setAttribute($attributes, $value);
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
        return array_key_exists($name, $this->attributes);
    }

    /**
     * @param string|array $attributes
     * @param bool $value
     * @return HasAttributes
     */
    public function attributes($attributes, $value = true)
    {
        return $this->attr($attributes, $value);
    }

}