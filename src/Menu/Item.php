<?php

namespace Styde\Html\Menu;

use Closure;
use Styde\Html\HandlesAccess;

class Item
{
    use HandlesAccess;

    public $text;
    public $url;
    public $id;
    public $class = '';
    public $active = false;
    public $included = true;
    public $submenu;
    public $items = [];
    public $extra = [];
    public $parent;

    public function __construct($url, $text)
    {
        $this->text = $text;
        $this->url = $url;
    }

    public function markAsActive(bool $value = true)
    {
        $this->active = $value;

        if ($this->parent) {
            $this->parent->markAsActive($value);
        }
    }

    public function classes($classes)
    {
        $this->class = Html::classes((array) $classes, false);

        return $this;
    }

    public function submenu(Closure $setup)
    {
        $this->submenu = $setup;

        return $this;
    }

    public function __call($method, array $parameters)
    {
        $this->extra[$method] = $parameters[0] ?? true;
    }

    public function getItem()
    {
        return $this;
    }

    public function __get($name)
    {
        return $this->extra[$name] ?? null;
    }
}
