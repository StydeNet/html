<?php

namespace Styde\Html\Menu\Item;

use Styde\Html\Menu\Item;
use Illuminate\Contracts\Routing\UrlGenerator;

class Route extends Item
{
    /**
     * The route name for generate the URL
     *
     * @var string
     */
    public $route;

    /**
     * Dynamic parameters for the route when needed
     *
     * @var array
     */
    public $parameters;

    /**
     * Create a new menu item for a Route
     *
     * @param string $action
     * @param string $text
     * @param array  $parameters
     */
    public function __construct(string $route, string $text, array $parameters = [])
    {
        parent::__construct($text);

        $this->route = $route;
        $this->parameters = $parameters;
    }

    /**
     * Add parameters to the route menu item
     *
     * @param  array
     * @return \Styde\Html\Menu\Item\Route $this
     */
    public function parameters(array $value)
    {
        $this->parameters = $value;

        return $this;
    }

    /**
     * Get the URL for the route item
     *
     * @return \Illuminate\Routing\UrlGenerator
     */
    public function url()
    {
        return app(UrlGenerator::class)->route($this->route, $this->parameters);
    }
}
