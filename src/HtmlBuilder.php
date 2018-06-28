<?php

namespace Styde\Html;

use Illuminate\Support\HtmlString;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Routing\UrlGenerator;

class HtmlBuilder
{
    use Macroable {
        __call as macroCall;
    }

    /**
     * The URL generator instance.
     *
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     */
    protected $url;

    /**
     * The View Factory instance.
     *
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;

    /**
     * Create a new HTML builder instance.
     *
     * @param \Illuminate\Contracts\Routing\UrlGenerator $url
     * @param \Illuminate\Contracts\View\Factory $view
     */
    public function __construct(UrlGenerator $url = null, Factory $view)
    {
        $this->url = $url;
        $this->view = $view;
    }

    /**
     * Builds an HTML class attribute dynamically.
     *
     * @param array $classes
     * @param bool $addClassAttribute
     * @return string
     */
    public function classes(array $classes, $addClassAttribute = true)
    {
        $html = '';

        foreach ($classes as $name => $bool) {
            if (is_int($name)) {
                $html .= "{$bool} ";
                continue;
            }

            if ($bool) {
                $html .= "{$name} ";
            }
        }

        if (empty($html)) {
            return '';
        }

        $html = trim($html);

        if ($addClassAttribute) {
            $html = ' class="'.$html.'"';
        }

        return new HtmlString($html);
    }

    /**
     * Generate an html tag.
     *
     * @param string $tag
     * @param string|array $content
     * @param array $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function tag($tag, $content = '', array $attributes = [])
    {
        if (is_array($content)) {
            $attributes = $content;
            $content = '';
        }

        if ($this->isVoidElement($tag)) {
            return new VoidTag($tag, $attributes);
        } else {
            return new Htmltag($tag, $content, $attributes);
        }
    }

    /**
     * It checks if it is an void element
     *
     * @param string $tag
     * @return bool
     */
    public function isVoidElement($tag)
    {
        return in_array($tag, [
            'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input',
            'keygen', 'link', 'menuitem', 'meta', 'param', 'source', 'track', 'wbr'
        ]);
    }

    /**
     * Generate an HTML link.
     *
     * @param string $url
     * @param string $title
     * @param array $attributes
     * @param bool $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function link($url, $title = null, $attributes = [], $secure = null)
    {
        $attributes['href'] = $this->url->to($url, [], $secure);

        return new Htmltag('a', $title ?: $url, $attributes);
    }

    /**
     * Generate an HTTPS HTML link.
     *
     * @param string $url
     * @param string $title
     * @param array $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function secureLink($url, $title = null, $attributes = [])
    {
        return $this->link($url, $title, $attributes, true);
    }

    /**
     * Generate an HTML link from a route
     *
     * @param  string $url
     * @param  string $title
     * @param  array $parameters
     * @param  array $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function linkRoute($url, $title = null, $parameters = [], $attributes = [])
    {
        return $this->link($this->url->route($url, $parameters), $title, $attributes);
    }

    /**
     * Generate an HTML link from an asset
     *
     * @param  string $url
     * @param  string $title
     * @param  array $attributes
     * @param  bool $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function linkAsset($url, $title = null, $attributes = [], $secure = null)
    {
        return $this->link($this->url->asset($url, $secure), $title, $attributes);
    }

    /**
     * Generate an HTTPS HTML link from an asset
     *
     * @param  string $url
     * @param  string $title
     * @param  array $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function linkSecureAsset($url, $title = null, $attributes = [])
    {
        return $this->linkAsset($url, $title, $attributes, true);
    }

    /**
     * Generate an CSS stylesheet link
     *
     * @param  string $url
     * @param  array  $attributes
     * @param  bool $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function style($url, $attributes = [], $secure = null)
    {
        $defaults = ['type' => 'text/css', 'rel' => 'stylesheet'];

        $attributes = array_merge($defaults, $attributes);

        $attributes['href'] = $this->url->asset($url, $secure);

        return new VoidTag('link', $attributes);
    }

    /**
     * Generate a Javascript link
     *
     * @param  string $url
     * @param  array  $attributes
     * @param  bool $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function script($url, $attributes = [], $secure = null)
    {
        $attributes['src'] = $this->url->asset($url, $secure);

        return new Htmltag('script', null, $attributes);
    }

    /**
     * @param $method
     * @param array $parameters
     * @return HtmlString|mixed
     */
    public function __call($method, array $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        return $this->tag($method, $parameters[0] ?? '', $parameters[1] ?? []);
    }
}
