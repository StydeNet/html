<?php

namespace Styde\Html\Menu\Item;

use Styde\Html\Menu\Item;
use Illuminate\Contracts\Routing\UrlGenerator;

class Action extends Item
{
    /**
     * The controller action name for generate the URL
     *
     * @var string
     */
    public $action;

    /**
     * Dynamic parameters for the controller action when needed
     *
     * @var array
     */
    public $parameters;

    /**
     * Create a new menu item for a controller action
     *
     * @param string $action
     * @param string $text
     * @param array  $parameters
     */
    public function __construct(string $action, string $text, array $parameters = [])
    {
        parent::__construct($text);

        $this->action = $action;
        $this->parameters = $parameters;
    }

    /**
     * Add parameters to the controller action menu item
     *
     * @param  array
     * @return \Styde\Html\Menu\Item\Action $this
     */
    public function parameters(array $value)
    {
        $this->parameters = $value;

        return $this;
    }

    /**
     * Get the URL for the controller action item
     *
     * @return \Illuminate\Routing\UrlGenerator
     */
    public function url()
    {
        return app(UrlGenerator::class)->action($this->action, $this->parameters);
    }
}
