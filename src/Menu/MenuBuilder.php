<?php

namespace Styde\Html\Menu;

use Closure;
use Styde\Html\Theme;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Routing\UrlGenerator;

class MenuBuilder implements Htmlable
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
    public $parent;

    /**
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     */
    public $url;

    protected $currentMenu;

    public function __construct(UrlGenerator $url, Theme $theme, Closure $activeUrlResolver)
    {
        $this->url = $url;
        $this->theme = $theme;
        $this->activeUrlResolver = $activeUrlResolver;
    }

    public function build($config, $parent = null)
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

    public function raw(string $url, string $text)
    {
        return $this->add($url, $text);
    }

    public function url(string $path, $text, $extra = [], $secure = false)
    {
        return $this->add($this->url->to($path, $extra, $secure), $text);
    }

    public function secureUrl(string $path, $text, $extra = [])
    {
        return $this->url($path, $text, $extra, true);
    }

    public function route(string $route, $text, $parameters = [])
    {
        return $this->add($this->url->route($route, $parameters), $text);
    }

    public function action(string $action, $text, $parameters = [])
    {
        return $this->add($this->url->action($action, $parameters), $text);
    }

    public function placeholder($text)
    {
        return $this->add('#', $text);
    }

    public function submenu($text, Closure $setup)
    {
        return $this->add('#', $text)->submenu($setup);
    }

    /**
     * Add a menu item.
     *
     * @param string $url
     * @param string $text
     * @return Item
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
