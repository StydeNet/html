<?php

namespace Styde\Html\Menu;

use Closure;
use Styde\Html\Theme;
use Styde\Html\Access\VerifyAccess;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Translation\Translator as Lang;
use Illuminate\Contracts\Config\Repository as Config;

class MenuGenerator
{
    use VerifyAccess;

    /**
     * Laravel or custom implementation to generate the URLs and routes
     *
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     */
    protected $url;

    /**
     * Class used to render the menu according to the current's theme.
     *
     * @var \Styde\Html\Theme
     */
    protected $theme;

    /**
     * Store an optional custom active URL resolver.
     *
     * @var \Closure
     */
    protected $activeUrlResolver;

    /**
     * Creates a new menu generator object.
     * This class is a factory that will allow us to generate different menus.
     *
     * @param UrlGenerator $url
     * @param Theme $theme
     */
    public function __construct(UrlGenerator $url, Theme $theme)
    {
        $this->url = $url;
        $this->theme = $theme;
    }

    /**
     * Set a custom callback to resolve the logic to determine if a URL is active or not.
     *
     * @param Closure $closure
     */
    public function setActiveUrlResolver(Closure $closure)
    {
        $this->activeUrlResolver = $closure;
    }

    /**
     * Makes a new menu.
     *
     * As the first argument you can send an array of items or reference a
     * configuration key where you can store the items array.
     *
     * @param Closure $config
     * @param string $classes main CSS classes for the menu
     * @return Menu
     */
    public function make(Closure $config, $classes = '')
    {
        $menu = new Menu($this->url, $this->theme, $config);

        if ($classes != '') {
            $menu->setClass($classes);
        }

        if ($this->accessHandler != null) {
            $menu->setAccessHandler($this->accessHandler);
        }

        if ($this->activeUrlResolver != null) {
            $menu->setActiveUrlResolver($this->activeUrlResolver);
        }

        return $menu;
    }
}
