<?php

namespace Styde\Html\Menu\Item;

use Styde\Html\Menu\Item;

class RawUrl extends Item
{
    public $url;
    public $text;

    public function __construct(string $url, string $text)
    {
        parent::__construct($text);

        $this->url = $url;
    }

    public function url()
    {
        return $this->url;
    }
}