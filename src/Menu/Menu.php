<?php

namespace Styde\Html\Menu;

use Closure;
use Styde\Html\Str;
use Styde\Html\Theme;
use Styde\Html\Access\VerifyAccess;
use Styde\Html\Access\AccessHandlerSetter;
use Illuminate\Translation\Translator as Lang;
use Illuminate\Contracts\Routing\UrlGenerator as Url;

class Menu
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
     * List of menu items
     *
     * @var array
     */
    protected $items;
    /**
     * Current item's id (active menu item), it will be obtained after the menu
     * is rendered.
     *
     * @var string
     */
    protected $currentId;
    /**
     * Whether all URLs should be secure (https) or not (http) by default
     *
     * @var bool
     */
    protected $defaultSecure = false;
    /**
     * Active URL (this will be taken from the Url::current method by default)
     *
     * @var string
     */
    protected $activeUrl;
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
     * @param $items
     */
    public function __construct(URL $url, Theme $theme, $items)
    {
        $this->url = $url;
        $this->theme = $theme;
        $this->items = $items;
        $this->activeUrl = $this->url->current();
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
     * Set whether all URLs should be secure (https) by default or not (http)
     *
     * @param $value
     * @return \Styde\Html\Menu\Menu $this
     */
    public function setDefaultSecure($value)
    {
        $this->defaultSecure = $value;
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
    public function setActiveUrl($value)
    {
        $this->activeUrl = $value;
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
     * Renders a new menu
     *
     * @param string|null $customTemplate
     * @return string the menu's HTML
     */
    public function render($customTemplate = null)
    {
        $items = $this->generateItems($this->items);

        return $this->theme->render(
            $customTemplate,
            ['items' => $items, 'class' => $this->class],
            'menu'
        );
    }

    /**
     * Generate and get the array of menu items but won't render the menu
     *
     * @return array
     */
    public function getItems()
    {
        return $this->generateItems($this->items);
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
     * @param array $items
     * @return array
     */
    protected function generateItems($items)
    {
        foreach ($items as $id => &$values) {
            $values = $this->setDefaultValues($id, $values);

            if (!$this->checkAccess($values)) {
                unset($items[$id]);
                continue;
            }

            $values['title'] = $this->getTitle($id, $values['title']);

            $values['url'] = $this->generateUrl($values);

            if (isset($values['submenu'])) {
                $values['submenu'] = $this->generateItems($values['submenu']);
            }

            if ($this->isActiveUrl($values)) {
                $values['active'] = true;
                $this->currentId = $id;
            } elseif (isset ($values['submenu'])) {
                // Check if there is an active item in the submenu, if
                // so it'll mark the current item as active as well.
                foreach ($values['submenu'] as $subitem) {
                    if ($subitem['active']) {
                        $values['active'] = true;
                        break;
                    }
                }
            }

            if ($values['active']) {
                $values['class'] .= ' '.$this->activeClass;
            }

            if ($values['submenu']) {
                $values['class'] .= ' '.$this->dropDownClass;
            }

            $values['class'] = trim($values['class']);

            unset(
                $values['callback'], $values['logged'], $values['roles'], $values['secure'],
                $values['params'], $values['route'], $values['action'], $values['full_url'],
                $values['allows'], $values['check'], $values['denies'], $values['exact']
            );
        }

        return $items;
    }

    /**
     * Merge the default values for a menu item
     *
     * @param $id
     * @param array $values
     * @return array
     */
    protected function setDefaultValues($id, array $values)
    {
        return array_merge([
            'class'   => '',
            'submenu' => null,
            'id'      => $id,
            'active'  => false
        ], $values);
    }

    /**
     * Checks whether this is the current URL or not
     *
     * @param array $values
     * @return bool
     */
    protected function isActiveUrl(array $values)
    {
        // Do we have a custom resolver? If so, use it:
        if($activeUrlResolver = $this->activeUrlResolver) {
            return $activeUrlResolver($values);
        }

        // If the current URL is the base URL or the exact attribute is set to true, then check for the exact URL
        if ($values['exact'] ?? false || $values['url'] == $this->baseUrl) {
            return $this->activeUrl === $values['url'];
        }

        // Otherwise use the default resolver:
        return strpos($this->activeUrl, $values['url']) === 0;
    }

    /**
     * Returns the menu's title. The title is determined following this order:
     *
     * 1. If a title is set then it will be returned and used as the menu title.
     * 2. If a translator is set this function will rely on the translateTitle
     * method (see below).
     * 3. Otherwise it will transform the item $key string to title format.
     *
     * @param $key
     * @param $title
     * @return string
     */
    protected function getTitle($key, &$title)
    {
        if (isset($title)) {
            return $title;
        }

        if(!is_null($this->lang)) {
            return $this->translateTitle($key);
        }

        return Str::title($key);
    }

    /**
     * Translates and return a title for a menu item.
     *
     * This method will attempt to find a "menu.key_item" through the translator
     * component. If no translation is found for this item, it will attempt to
     * transform the item $key string to a title readable format.
     *
     * @param $key
     * @return string
     */
    protected function translateTitle($key)
    {
        $translation = $this->lang->get('menu.'.$key);

        if ($translation != 'menu.'.$key) {
            return $translation;
        }

        return Str::title($key);
    }

    /**
     * Retrieve a route or action name and its parameters
     *
     * If $params is a string, then it returns it as the name of the route or
     * action and the parameters will be an empty array.
     *
     * If it is an array then it takes the first element as the name of the
     * route or action and the other elements as the parameters.
     *
     * Then it will try to replace any dynamic parameters (relying on the
     * replaceDynamicParameters method, see below)
     *
     * Finally it will return an array where the first value will be the name of
     * the route or action and the second value will be the array of parameters.
     *
     * @param $params
     * @return array
     */
    protected function getRouteAndParameters($params)
    {
        if (is_string($params)) {
            return [$params, []];
        }

        return [
            // The first position in the array is the route or action name
            array_shift($params),
            // After that they are parameters and they could be dynamic
            $this->replaceDynamicParameters($params)
        ];
    }

    /**
     * Allows variable or dynamic parameters for all the menu's routes and URLs
     *
     * Just precede the parameter's name with ":"
     * For example: :user_id
     *
     * This method will cycle through all the parameters and replace the dynamic
     * ones with their corresponding values stored through the setParams and
     * setParam methods,
     *
     * If a dynamic value is not found the literal value will be returned.
     *
     * @param array $params
     * @return array
     */
    protected function replaceDynamicParameters(array $params)
    {
        foreach ($params as &$param) {
            if (strpos($param, ':') !== 0) {
                continue;
            }
            $name = substr($param, 1);
            if (isset($this->params[$name])) {
                $param = $this->params[$name];
            }
        }

        return $params;
    }

    /**
     * Generates the menu item URL, using any of the following options, in order:
     *
     * If you pass a 'full_url' key within the item configuration, in that case
     * it will return it as the URL with no additional action.
     *
     * If you pass a 'url' key then it will call the Url::to method to complete
     * the base URL, you can also specify a 'secure' key to indicate whether
     * this URL should be secure or not. Otherwise the defaultSecure option will
     * be used.
     *
     * If you pass a 'route' key then it will call Url::route
     *
     * If you pass an 'action' it will call the Url::action method instead.
     *
     * If you need to pass parameters for the url, route or action, just specify
     * an array where the first position will be the url, route or action name
     * and the rest of the array will contain the parameters. You can specify
     * dynamic parameters (see methods above).
     *
     * If none of these options are found then this function will simple return
     * a placeholder (#).
     *
     * @param $values
     * @return mixed
     */
    protected function generateUrl($values)
    {
        if (isset($values['full_url'])) {
            return $values['full_url'];
        }

        if (isset($values['url'])) {
            list($url, $params) = $this->getRouteAndParameters($values['url']);
            $secure = isset($values['secure']) ? $values['secure'] : $this->defaultSecure;
            return $this->url->to($url, $params, $secure);
        }

        if (isset($values['route'])) {
            list($route, $params) = $this->getRouteAndParameters($values['route']);
            return $this->url->route($route, $params);
        }

        if (isset($values['action'])) {
            list($route, $params) = $this->getRouteAndParameters($values['action']);
            return $this->url->action($route, $params);
        }

        return '#';
    }

}
