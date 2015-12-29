<?php

namespace Styde\Html\FormModel;

trait HasAttributes
{

    public function id($id)
    {
        $this->attributes['id'] = $id;
    }

    public function classes($classes)
    {
        $this->attributes['class'] = $classes;
    }

    public function attr($attributes, $value = null)
    {
        $this->attributes($attributes, $value);
    }

    public function attributes($attributes, $value = null)
    {
        if (is_array($attributes)) {
            $this->attributes = array_merge($this->attributes, $attributes);
        } else {
            $this->attributes[$attributes] = $value;
        }
    }

}