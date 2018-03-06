<?php

namespace Styde\Html\Menu;

use Closure;
use Styde\Html\Facades\Html;
use Styde\Html\HandlesAccess;

abstract class Item
{
    use HandlesAccess;

    public $text;
    public $secure;
    public $id;
    public $class = '';
    public $active = false;
    public $submenu;
    public $items = [];
    public $extra = [];
    public $parent;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public abstract function url();

    public function classes($classes)
    {
        $this->class = Html::classes((array) $classes, false);

        return $this;
    }

    public function markAsActive(bool $value = true)
    {
        $this->active = $value;

        if ($this->parent) {
            $this->parent->markAsActive($value);
        }

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

    public function __get($name)
    {
        return $this->extra[$name] ?? null;
    }
}
