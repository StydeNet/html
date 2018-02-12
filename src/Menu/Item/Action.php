<?php

namespace Styde\Html\Menu\Item;

use Styde\Html\Menu\Item;
use Illuminate\Contracts\Routing\UrlGenerator;

class Action extends Item
{
    public $action;
    public $parameters;
    public $secure;

    public function __construct(string $action, string $text, array $parameters = [], $secure = null)
    {
        parent::__construct($text);

        $this->action = $action;
        $this->parameters = $parameters;
        $this->secure = $secure;
    }

    public function parameters(array $value)
    {
        $this->parameters = $value;

        return $this;
    }

    public function secure($value = true)
    {
        $this->secure = $value;

        return $this;
    }

    public function url()
    {
        return app(UrlGenerator::class)->action($this->action, $this->parameters, $this->secure);
    }
}
