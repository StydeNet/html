<?php

namespace Styde\Html\Menu\Item;

use Styde\Html\Menu\Item;

class RawUrl extends Item
{
    public $url;
    public $text;

    public function __construct(string $url, string $text)
    {
        $this->url = $url;

        parent::__construct($text);
    }

    public function url()
    {
        return $this->url;
    }
}