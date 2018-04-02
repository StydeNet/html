<?php

namespace Styde\Html\Menu;

use Closure;
use Styde\Html\Facades\Html;
use Styde\Html\HandlesAccess;

class ItemBuilder
{
    use HandlesAccess;

    public $item;

    public $id;
    public $class = '';
    public $active = false;
    public $submenu;
    public $items = [];
    public $extra = [];
    public $parent;

    public function __construct(string $url, string $text)
    {
        $this->item = new Item($url, $text);
    }

    public function classes($classes)
    {
        $this->item->class = Html::classes((array) $classes, false);

        return $this;
    }

    public function submenu(Closure $setup)
    {
        $this->submenu = $setup;

        return $this;
    }

    public function __call($method, array $parameters)
    {
        $this->item->extra[$method] = $parameters[0] ?? true;
    }

    public function getItem()
    {
        return $this->item;
    }
}
