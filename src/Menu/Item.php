<?php

namespace Styde\Html\Menu;

class Item
{
    public $text;
    public $url;
    public $id;
    public $class = '';
    public $active = false;
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

    public function __get($name)
    {
        return $this->extra[$name] ?? null;
    }
}
