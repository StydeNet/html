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

    public function __construct($theme, array $items = [])
    {
        $this->theme = $theme;
        $this->items = $items;
    }
//
//    public function setTemplate($template)
//    {
//        return $this->template = $template;
//    }

    /**
     * Renders a new menu
     *
     * @param string|null $customTemplate
     * @return string the menu's HTML
     */
    public function render($customTemplate = null)
    {
        $data = [
            'items' => $this->items,
            'class' => 'nav',
        ];

        return $this->theme->render($customTemplate, $data, 'menu');
    }

    /**
     * Get content as a string of HTML.
     *
     * @return string
     */
    public function toHtml()
    {
        return $this->render();
    }
}