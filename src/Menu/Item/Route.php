<?php

namespace Styde\Html\Menu\Item;

use Styde\Html\Menu\Item;
use Illuminate\Contracts\Routing\UrlGenerator;

class Route extends Item
{
    public $route;
    public $text;
    public $parameters;
    public $secure;

    public function __construct(string $route, string $text, array $parameters = [])
    {
        $this->route = $route;

        parent::__construct($text, $parameters);
    }

    public function url()
    {
        return app(UrlGenerator::class)->route($this->route, $this->parameters, true);
    }
}