<?php

namespace Styde\Html;

trait FallbackToParent
{
    protected $parent;

    public function __construct($parent)
    {
        $this->setParent($parent);
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function __call($method, $parameters)
    {
        return $this->parent->$method(...$parameters);
    }
}
