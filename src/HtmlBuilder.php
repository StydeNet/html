<?php

namespace Styde\Html;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\HtmlString;

class HtmlBuilder
{
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
     * Generate a HTML link.
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

    public function isVoidElement($tag)
    {
        return in_array($tag, [
            'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input',
            'keygen', 'link', 'menuitem', 'meta', 'param', 'source', 'track', 'wbr'
        ]);
    }

    public function __call($method, array $parameters)
    {
        return $this->tag($method, $parameters[0] ?? '', $parameters[1] ?? []);
    }
}
