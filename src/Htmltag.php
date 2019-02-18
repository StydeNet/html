<?php

namespace Styde\Html;

use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Support\Htmlable;

class Htmltag extends BaseTag
{

    /**
     * Content of the tag
     *
     * @var array|string
     */
    protected $content = [];

    /**
     * Htmltag constructor.
     *
     * @param string $tag
     * @param string $content
     * @param array $attributes
     */
    public function __construct($tag, $content = null, array $attributes = [])
    {
        parent::__construct($tag, $attributes);

        if (is_string($content)) {
            $content = [new TextElement($content)];
        }

        $this->content = $content;
    }

    /**
     * Add content
     *
     * @param Htmlable $child
     */
    public function add(Htmlable $child)
    {
        $this->content[] = $child;
    }

    /**
     * @return HtmlString
     */
    public function render()
    {
        if ($this->included) {
            return new HtmlString(
                $this->renderOpenTag()
                     .$this->renderContent()
                .$this->renderCloseTag()
            );
        }
    }

    /**
     * Open tag
     *
     * @return HtmlString
     */
    public function open()
    {
        if ($this->included) {
            return new HtmlString($this->renderOpenTag());
        }
    }

    /**
     * Close tag
     *
     * @return HtmlString
     */
    public function close()
    {
        if ($this->included) {
            return new HtmlString($this->renderCloseTag());
        }
    }

    /**
     * @return string
     */
    public function renderCloseTag()
    {
        return '</'.$this->tag.'>';
    }

    /**
     * @return string
     */
    public function renderContent()
    {
        $result = '';

        foreach ((array) $this->content as $content) {
            $result .= $content->render();
        }

        return $result;
    }
}
