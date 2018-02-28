<?php

namespace Styde\Html\Menu\Item;

use Styde\Html\Menu\Item;
use Illuminate\Contracts\Routing\UrlGenerator;

class Url extends Item
{
    /**
     * Path URL
     *
     * @var string
     */
    public $path;

    /**
     * Dynamic parameters of URL
     *
     * @var array
     */
    public $parameters;

    /**
     * The URL can be secure (HTTPS)
     *
     * @var boolean
     */
    public $secure;

    /**
     * Create a new URL menu item
     *
     * @param string  $path
     * @param string  $text
     * @param array   $parameters
     * @param boolean $secure
     */
    public function __construct(string $path, string $text, array $parameters = [], $secure = false)
    {
        parent::__construct($text);

        $this->path = $path;
        $this->secure = $secure;
        $this->parameters = $parameters;
    }

    /**
     * Add parameters to the URL menu item
     *
     * @param  array
     * @return \Styde\Html\Menu\Item\Url $this
     */
    public function parameters(array $value)
    {
        $this->parameters = $value;
    }

    /**
     * Set secure URL for the item
     *
     * @param  array
     * @return \Styde\Html\Menu\Item\Url $this
     */
    public function secure($value = true)
    {
        $this->secure = $value;
    }

    /**
     * Get the URL for the item
     *
     * @return \Illuminate\Routing\UrlGenerator
     */
    public function url()
    {
        return app(UrlGenerator::class)->to($this->path, $this->parameters, $this->secure);
    }
}
