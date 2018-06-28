<?php

namespace Styde\Html;

use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Support\Htmlable;

class Htmltag extends BaseTag
{

    /**
     * Attributes of the tag
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
    public function __construct($tag, $content = '', array $attributes = [])
    {
        parent::__construct($tag, $attributes);

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
        return new HtmlString(
             '<'.$this->tag.$this->renderAttributes().'>'
                 .$this->renderContent()
            .'</'.$this->tag.'>'
        );
    }

    /**
     * Open tag
     *
     * @return HtmlString
     */
    public function open()
    {
        return new HtmlString('<'.$this->tag.$this->renderAttributes().'>');
    }

    /**
     * Close tag
     *
     * @return HtmlString
     */
    public function close()
    {
        return new HtmlString('</'.$this->tag.'>');
    }

    /**
     * @return string
     */
    public function renderContent()
    {
        $result = '';

        foreach ((array) $this->content as $content) {
            $result .= e($content);
        }

        return $result;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset ($this->content[$name])) {
            return $this->content[$name];
        }

        throw new \InvalidArgumentException("The property $name does not exist in this [{$this->tag}] element");
    }
}
