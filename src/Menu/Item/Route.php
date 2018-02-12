<?php

namespace Styde\Html\Menu\Item;

use Styde\Html\Menu\Item;
use Illuminate\Contracts\Routing\UrlGenerator;

class Route extends Item
{
    public $route;
    public $text;
    public $parameters;

    public function __construct(string $route, string $text, array $parameters = [])
    {
        parent::__construct($text);

        $this->route = $route;
        $this->parameters = $parameters;
    }

    public function parameters(array $value)
    {
        $this->parameters = $value;

        return $this;
    }

    public function url()
    {
        return app(UrlGenerator::class)->route($this->route, $this->parameters);
    }
}
