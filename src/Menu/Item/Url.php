<?php

namespace Styde\Html\Menu\Item;

use Styde\Html\Menu\Item;
use Illuminate\Contracts\Routing\UrlGenerator;

class Url extends Item
{
    public $path;
    public $text;
    public $parameters;
    public $secure;

    public function __construct(string $path, string $text, array $parameters, bool $secure)
    {
        $this->path = $path;
        $this->text = $text;
        $this->parameters = $parameters;
        $this->secure = $secure;
    }

    public function parameters(array $value)
    {
        $this->parameters = $value;
    }

    public function secure(bool $value = true)
    {
        $this->secure = $value;
    }

    public function url()
    {
        return app(UrlGenerator::class)->to($this->path, $this->parameters, $this->secure);
    }
}