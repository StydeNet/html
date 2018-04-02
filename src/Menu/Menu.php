<?php

namespace Styde\Html\Menu;

use Illuminate\Contracts\Support\Htmlable;

class Menu implements Htmlable
{
    /**
     * @var \Styde\Html\Theme
     */
    protected $theme;

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
     * Renders the menu as an HTML string.
     *
     * @return string
     */
    public function toHtml()
    {
        $data = [
            'items' => $this->items,
            'class' => 'nav',
        ];

        return $this->theme->render($this->template, $data, 'menu');
    }
}