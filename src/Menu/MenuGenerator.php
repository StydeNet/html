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
     * Laravel or custom implementation to retrieve the menu items from a
     * configuration file.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;
    /**
     * Class used to render the menu according to the current's theme.
     *
     * @var \Styde\Html\Theme
     */
    protected $theme;
    /**
     * Optional class used to translate the messages, in case i18n is needed.
     * This can be change in the configuration file (config/html.php)
     * With the option translate_texts (true or false)
     *
     * @var \Illuminate\Translation\Translator
     */
    protected $lang;

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
     * @param Config $config
     * @param Theme $theme
     */
    public function __construct(UrlGenerator $url, Config $config, Theme $theme)
    {
        $this->url = $url;
        $this->config = $config;
        $this->theme = $theme;
    }

    /**
     * Set the translator object
     *
     * @param Lang $lang
     */
    public function setLang(Lang $lang)
    {
        $this->lang = $lang;
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
     * @param array|string $items array of items or a config file key
     * @param string|null $classes main CSS classes for the menu
     *
     * @return \Styde\Html\Menu\Menu
     */
    public function make($items, $classes = null)
    {
        if (is_string($items)) {
            $items = $this->config->get($items);
        }

        $menu = new Menu($this->url, $this->theme, $items);

        if (!is_null($this->lang)) {
            $menu->setLang($this->lang);
        }

        if (!is_null($this->accessHandler)) {
            $menu->setAccessHandler($this->accessHandler);
        }

        if ($classes != null) {
            $menu->setClass($classes);
        }

        if ($this->activeUrlResolver != null) {
            $menu->setActiveUrlResolver($this->activeUrlResolver);
        }

        return $menu;
    }
}
