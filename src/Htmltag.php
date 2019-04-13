<?php

namespace Styde\Html;

use InvalidArgumentException;
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

        if (is_array($content)) {
            array_walk($content, [$this, 'add']);
        } elseif (is_string($content)) {
            $this->add(new TextElement($content));
        } elseif ($content) {
            $this->add($content);
        }
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

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->content[$name])) {
            return $this->content[$name];
        }
        return parent::__get($name);
    }
}
