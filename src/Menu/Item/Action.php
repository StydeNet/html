<?php

namespace Styde\Html\Menu\Item;

use Styde\Html\Menu\Item;
use Illuminate\Contracts\Routing\UrlGenerator;

class Action extends Item
{
    public $action;

    public function __construct(string $action, string $text, array $parameters = [], bool $secure = true)
    {
        $this->action = $action;

        parent::__construct($text, $parameters, $secure);
    }

    public function url()
    {
        return app(UrlGenerator::class)->action($this->action, $this->parameters, $this->secure);
    }
}