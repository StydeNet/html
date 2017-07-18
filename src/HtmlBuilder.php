<?php

namespace Styde\Html;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Routing\UrlGenerator;

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
     *
     * @return string
     */
    public function classes(array $classes)
    {
        $html = '';

        foreach ($classes as $name => $bool) {
            if (is_int($name)) {
                $name = $bool;
                $bool = true;
            }

            if ($bool) {
                $html .= $name.' ';
            }
        }

        if (!empty($html)) {
            return ' class="'.trim($html).'"';
        }

        return '';
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

        return new HtmlElement($tag, $content, $attributes);
    }

    public function __call($method, array $parameters)
    {
        return $this->tag($method, $parameters[0] ?? '', $parameters[1] ?? []);
    }
}
