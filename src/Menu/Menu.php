<?php

namespace Styde\Html\Menu;

use Closure;
use Illuminate\Contracts\Support\Htmlable;

class Menu implements Htmlable
{
    /**
     * @var \Styde\Html\Theme
     */
    protected $theme;

    /**
     * Default CSS class(es) for the menu
     *
     * @var string
     */
    protected $class = 'nav';

    /**
     * @var array
     */
    public $items;

    /**
     * @var string
     */
    public $template;

    public function __construct($theme, array $items = [])
    {
        $this->theme = $theme;
        $this->items = $items;
    }

    /**
     * Set a custom template.
     *
     * @param string $template
     * @return $this
     */
    public function template($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Set the main CSS class(es) for this menu.
     * If you want to pass more than one CSS class divide them with spaces.
     *
     * @param string $value
     * @return \Styde\Html\Menu\Menu $this
     */
    public function setClass($value)
    {
        $this->class = $value;
        return $this;
    }

    /**
     * Renders the menu as an HTML string.
     *
     * @return string
     */
    public function toHtml()
    {
        $data = [
            'items' => $this->items,
            'class' => $this->class,
        ];

        return $this->theme->render($this->template, $data, 'menu');
    }
}
