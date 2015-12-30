<?php

namespace Styde\Html\FormModel;

trait HasAttributes
{

    public function id($id)
    {
        return $this->attributes('id', $id);
    }

    public function classes($classes)
    {
        return $this->attributes('class', $classes);
    }

    public function attr($attributes, $value = null)
    {
        return $this->attributes($attributes, $value);
    }

    public function attributes($attributes, $value = true)
    {
        if (is_array($attributes)) {
            $this->attributes = array_merge($this->attributes, $attributes);
        } else {
            $this->attributes[$attributes] = $value;
        }
        return $this;
    }

}