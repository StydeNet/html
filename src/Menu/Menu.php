<?php

namespace Styde\Html\Menu;

use Closure;
use Illuminate\Contracts\Support\Htmlable;
use Styde\Html\Str;
use Styde\Html\Theme;
use Styde\Html\Access\VerifyAccess;
use Styde\Html\Access\AccessHandlerSetter;
use Illuminate\Translation\Translator as Lang;
use Illuminate\Contracts\Routing\UrlGenerator as Url;

class Menu implements Htmlable
{
    use VerifyAccess {
        checkAccess as traitCheckAccess;
    }

    /**
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     */
    protected $url;

    /**
     * @var \Styde\Html\Theme
     */
    protected $theme;

    /**
     * @var \Illuminate\Translation\Translator
     */
    protected $lang;

    /**
     * Default CSS class(es) for the menu
     *
     * @var string
     */
    protected $class = 'nav';

    /**
     * Default CSS class(es) for the active item(s)
     *
     * @var string
     */
    protected $activeClass = 'active';

    /**
     * Default CSS class(es) for the sub-menus
     *
     * @var string
     */
    protected $dropDownClass = 'dropdown';

    /**
     * Menu configuration callback.
     *
     * @var \Closure
     */
    protected $config;

    /**
     * Current item's id (active menu item), it will be obtained after the menu
     * is rendered.
     *
     * @var string
     */
    protected $currentId;

    /**
     * Current URL (this will be taken from the Url::current method by default)
     *
     * @var string
     */
    protected $currentUrl;

    /**
     * Allow dynamic parameters for routes and actions.
     *
     * @var array
     */
    protected $params = array();

    /**
     * Store an optional custom active URL resolver.
     *
     * @var \Closure
     */
    protected $activeUrlResolver;

    /**
     * Creates a new menu.
     *
     * A menu will be typically created from the Menu generator class through
     * the Menu facade (Menu::make).
     *
     * @param Url $url
     * @param Theme $theme
     * @param $config
     */
    public function __construct(URL $url, Theme $theme, $config)
    {
        $this->url = $url;
        $this->theme = $theme;
        $this->config = $config;
        $this->currentUrl = $this->url->current();
        $this->baseUrl = $this->url->to('');
    }

    /**
     * Call the render method if someone attempts to print the Menu::make method
     *
     * Example: {!! Menu::make('items') !!}
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Set the optional translator component
     *
     * @param Lang $lang
     * @return $this
     */
    public function setLang(Lang $lang)
    {
        $this->lang = $lang;
        return $this;
    }

    /**
     * Set the dynamic parameters for the routes and URLs
     *
     * @param array $values
     * @return \Styde\Html\Menu\Menu $this
     */
    public function setParams(array $values = array())
    {
        $this->params = $values;
        return $this;
    }

    /**
     * Set a dynamic parameter for the routes and URLs
     *
     * @param $key
     * @param $value
     * @return \Styde\Html\Menu\Menu $this
     */
    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
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
     * Set the CSS class(es) for the active item
     *
     * @param $value
     * @return \Styde\Html\Menu\Menu $this
     */
    public function setActiveClass($value)
    {
        $this->activeClass = $value;
        return $this;
    }

    /**
     * Set the CSS class(es) for the items with sub-menus
     *
     * @param $value
     * @return \Styde\Html\Menu\Menu $this
     */
    public function setDropDownClass($value)
    {
        $this->dropDownClass = $value;
        return $this;
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
     * Set the current URL
     *
     * @param $value
     */
    public function setCurrentUrl($value)
    {
        $this->currentUrl = $value;
    }

    /**
     * Set the base URL
     *
     * @param $value
     */
    public function setBaseUrl($value)
    {
        $this->baseUrl = $value;
    }

    /**
     * Allow us to get the ID of the active item.
     *
     * This will be available only after the menu is rendered.
     *
     * @return string
     */
    public function getCurrentId()
    {
        return $this->currentId;
    }

    /**
     * Generate and get the array of menu items but won't render the menu
     *
     * @return array
     */
    public function getItems()
    {
        return $this->buildItems($this->config);
    }

    public function checkAccess(array $options)
    {
        if ($this->accessHandler==null) {
            return true;
        }

        foreach (['allows', 'check', 'denies'] as $gateOption) {
            if (isset($options[$gateOption]) && is_array($options[$gateOption])) {
                $options[$gateOption] = $this->replaceDynamicParameters($options[$gateOption]);
            }
        }

        return $this->traitCheckAccess($options);
    }

    /**
     * Generate the items for a menu or sub-menu.
     *
     * This method will called itself if an item has a 'submenu' key.
     *
     * @param Closure $config
     * @param \Styde\Html\Menu\Item|null $parentItem
     * @return array
     */
    protected function buildItems($config, $parentItem = null)
    {
        $items = new ItemCollection($config);

        foreach ($items as $item) {
            if ($this->isActive($item)) {
                $this->markAsActive($item, $parentItem);
                $this->currentId = $item->id;
            }

            if ($item->submenu != null) {
                $item->items = $this->buildItems($item->submenu, $item);
            }
        }

        return $items;
    }

    /**
     * Checks whether this is the current URL or not
     *
     * @param \Styde\Html\Menu\Item $item
     * @return bool
     */
    protected function isActive($item)
    {
        // Do we have a custom resolver? If so, use it:
        if($activeUrlResolver = $this->activeUrlResolver) {
            return $activeUrlResolver($item);
        }

        // Otherwise use the default resolver:
        if ($item->url() != $this->baseUrl) {
            return strpos($this->currentUrl, $item->url()) === 0;
        }

        return $this->currentUrl === $this->baseUrl;
    }

    /**
     * Mark an item an it's optional parent item as active.
     *
     * @param \Styde\Html\Menu\Item $item
     * @param \Styde\Html\Menu\Item|null $parent
     */
    protected function markAsActive($item, $parent = null)
    {
        $item->active(true);

        if ($parent != null) {
            $parent->active(true);
        }
    }

    /**
     * Renders a new menu
     *
     * @param string|null $customTemplate
     * @return string the menu's HTML
     */
    public function render($customTemplate = null)
    {
        return $this->theme->render($customTemplate, [
            'items' => $this->getItems(),
            'class' => $this->class
        ], 'menu');
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
