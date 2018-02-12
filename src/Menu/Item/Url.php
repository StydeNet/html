<?php

namespace Styde\Html\Menu\Item;

use Styde\Html\Menu\Item;
use Illuminate\Contracts\Routing\UrlGenerator;

class Url extends Item
{
    public $path;
    public $parameters;
    public $secure;

    public function __construct(string $path, string $text, array $parameters = [], $secure = null)
    {
        parent::__construct($text);

        $this->path = $path;
        $this->secure = $secure;
        $this->parameters = $parameters;
    }

    public function parameters(array $value)
    {
        $this->parameters = $value;
    }

    public function secure($value = true)
    {
        $this->secure = $value;
    }

    public function url()
    {
        return app(UrlGenerator::class)->to($this->path, $this->parameters, $this->secure);
    }
}
