<?php

namespace Styde\Html\Menu;

use Closure;
use Styde\Html\Theme;
use Illuminate\Contracts\Routing\UrlGenerator;

class MenuBuilder
{
    use BuildsItems;

    /**
     * @var \Styde\Html\Theme
     */
    public $theme;

    /**
     * Store an optional custom active URL resolver.
     *
     * @var \Closure
     */
    protected $activeUrlResolver;

    public $items = [];

    /**
     * @var \Styde\Html\Menu\Item
     */
    public $parent;

    /**
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     */
    public $url;

    /**
     * @var \Styde\Html\Menu\Menu
     */
    protected $currentMenu;

    /**
     * Create a Menu Builder class
     *
     * @param \Illuminate\Contracts\Routing\UrlGenerator $url
     * @param \Styde\Html\Theme $theme
     * @param Closure $activeUrlResolver
     */
    public function __construct(UrlGenerator $url, Theme $theme, Closure $activeUrlResolver)
    {
        $this->url = $url;
        $this->theme = $theme;
        $this->activeUrlResolver = $activeUrlResolver;
    }

    /**
     * Build a Menu
     *
     * @param  Closure $config
     * @param  \Styde\Html\Menu\Item $parent
     * @return \Styde\Html\Menu\Menu
     */
    public function build(Closure $config, $parent = null)
    {
        $this->parent = $parent;

        $this->currentMenu = new Menu($this->theme);

        $config($this);

        $this->currentMenu->items = $this->getItems();

        return $this->currentMenu;
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
     * Add a menu item.
     *
     * @param string $url
     * @param string $text
     * @return \Styde\Html\Menu\Item
     */
    public function add(string $url, string $text): Item
    {
        $this->items[] = $item = new Item($url, $text);

        $item->parent = $this->parent;

        return $item;
    }

    /**
     * Generate and get the array of menu items but won't render the menu
     *
     * @return array
     */
    public function getItems()
    {
        return $this->buildItems();
    }

    /**
     * Generate the items for a menu or sub-menu.
     *
     * This method will called itself if an item has a 'submenu' key.
     *
     * @return array
     */
    public function buildItems()
    {
        $result = [];

        foreach ($this->items as $item) {
            if (! $item->included) {
                continue;
            }

            if ($this->isActive($item)) {
                $item->markAsActive();
            }

            if ($item->submenu != null) {
                $menuBuilder = new static($this->url, $this->theme, $this->activeUrlResolver);

                $menuBuilder->build($item->submenu, $item);

                $item->items = $menuBuilder->getItems();
            }

            $result[] = $item;
        }

        return $result;
    }

    /**
     * Checks whether this is the current URL or not
     *
     * @param \Styde\Html\Menu\Item $item
     * @return bool
     */
    protected function isActive(Item $item)
    {
        return ($this->activeUrlResolver)($item);
    }
}
