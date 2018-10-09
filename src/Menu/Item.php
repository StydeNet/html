<?php

namespace Styde\Html\Menu;

use Closure;
use Styde\Html\Facades\Html;
use Styde\Html\HandlesAccess;

class Item
{
    use HandlesAccess;

    /**
     * Text for the item
     *
     * @var string
     */
    public $text;

    /**
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     */
    public $url;

    public $id;
    /**
     * Additional CSS classes for items
     *
     * @var string
     */
    public $class = '';

    /**
     * @var boolean
     */
    public $active = false;

    /**
     * Store if the item will be visible
     *
     * @var boolean
     */
    public $included = true;

    /**
     * Store the submenu configuration
     *
     * @var \Closure
     */
    public $submenu;

    /**
     * List the item belong to the submenu
     *
     * @var array
     */
    public $items = [];

    /**
     * Dynamic parameters for an item
     *
     * @var array
     */
    public $extra = [];

    /**
     * Parent item
     *
     * @var \Styde\Html\Menu\Item
     */
    public $parent;

    /**
     * Create a menu item
     *
     * @param \Illuminate\Contracts\Routing\UrlGenerator $url
     * @param string $text
     */
    public function __construct($url, $text)
    {
        $this->text = $text;
        $this->url = $url;
    }

    /**
     * Mark a item as active in the menu
     *
     * @param  bool|boolean $value
     * @return \Styde\Html\Menu\Item
     */
    public function markAsActive(bool $value = true)
    {
        $this->active = $value;

        if ($this->parent) {
            $this->parent->markAsActive($value);
        }

        return $this;
    }

    /**
     * Set the class(es) for an item
     *
     * @param  string $classes
     * @return \Styde\Html\Menu\Item
     */
    public function classes($classes)
    {
        $this->class = Html::classes((array) $classes, false);

        return $this;
    }
    /**
     * Add a submenu for an item
     *
     * @param  Closure $setup
     * @return \Styde\Html\Menu\Item
     */
    public function submenu(Closure $setup)
    {
        $this->submenu = $setup;

        return $this;
    }

    /**
     * Get an extra attribute with parameters
     *
     * @param  string $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, array $parameters)
    {
        $this->extra[$method] = $parameters[0] ?? true;

        return $this;
    }

    /**
     * @return \Styde\Html\Menu\Item
     */
    public function getItem()
    {
        return $this;
    }

    /**
     * Get a extra attribute for the item
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->extra[$name] ?? null;
    }
}
