<?php

namespace Styde\Html\Menu;

use Closure;
use Styde\Html\Facades\Html;

abstract class Item
{
    public $text;
    public $parameters;
    public $secure;
    public $id;
    public $class = '';
    public $active = false;
    public $submenu;
    public $items = [];

    public function __construct(string $text, array $parameters = [], bool $secure = true)
    {
        $this->text = $text;
        $this->parameters = $parameters;
        $this->secure = $secure;
    }

    public abstract function url();

    public function parameters(array $value)
    {
        $this->parameters = $value;

        return $this;
    }

    public function secure(bool $value = true)
    {
        $this->secure = $value;

        return $this;
    }

    public function classes($classes)
    {
        $this->class = Html::classes((array) $classes, false);

        return $this;
    }

    public function active(bool $value = true)
    {
        $this->active = $value;

        return $this;
    }

    public function submenu(Closure $setup)
    {
        $this->submenu = $setup;

        return $this;
    }
}