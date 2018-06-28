<?php

namespace Styde\Html\Menu;

use Closure;

trait BuildsItems
{
    /**
     * Add a raw URL menu item
     *
     * @param  string $url
     * @param  string $text
     * @return \Styde\Html\Menu\Item
     */
    public function raw(string $url, string $text)
    {
        return $this->add($url, $text);
    }

    /**
     * Add an URL menu item
     *
     * @param  string  $path
     * @param  [type]  $text
     * @param  array   $extra
     * @param  boolean $secure
     * @return \Styde\Html\Menu\Item
     */
    public function url(string $path, $text, $extra = [], $secure = false)
    {
        return $this->add($this->url->to($path, $extra, $secure), $text);
    }

    /**
     * Add a secure URL menu item
     *
     * @param  string $path
     * @param  [type] $text
     * @param  array  $extra
     * @return \Styde\Html\Menu\Item
     */
    public function secureUrl(string $path, $text, $extra = [])
    {
        return $this->url($path, $text, $extra, true);
    }

    /**
     * Add a menu item for a Route
     *
     * @param  string $route
     * @param  [type] $text
     * @param  array  $parameters
     * @return \Styde\Html\Menu\Item
     */
    public function route(string $route, $text, $parameters = [])
    {
        return $this->add($this->url->route($route, $parameters), $text);
    }

    /**
     * Add a menu item for a controller action
     *
     * @param  string $action
     * @param  [type] $text
     * @param  array  $parameters
     * @return \Styde\Html\Menu\Item
     */
    public function action(string $action, $text, $parameters = [])
    {
        return $this->add($this->url->action($action, $parameters), $text);
    }

    /**
     * Add a placeholder menu item
     *
     * @param  [type] $text
     * @return \Styde\Html\Menu\Item
     */
    public function placeholder($text)
    {
        return $this->add('#', $text);
    }

    /**
     * Add a submenu
     *
     * @param  [type]  $text
     * @param  Closure $setup
     * @return \Styde\Html\Menu\Item
     */
    public function submenu($text, Closure $setup)
    {
        return $this->add('#', $text)->submenu($setup);
    }
}
