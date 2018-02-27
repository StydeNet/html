<?php

namespace Styde\Html;

use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Support\Htmlable;

class Htmltag extends BaseTag
{
    protected $content = [];

    public function __construct($tag, $content = '', array $attributes = [])
    {
        parent::__construct($tag, $attributes);

        $this->content = $content;
    }

    public function add(Htmlable $child)
    {
        $this->content[] = $child;
    }

    public function render()
    {
        return new HtmlString(
             '<'.$this->tag.$this->renderAttributes().'>'
                 .$this->renderContent()
            .'</'.$this->tag.'>'
        );
    }

    public function open()
    {
        return new HtmlString('<'.$this->tag.$this->renderAttributes().'>');
    }

    public function close()
    {
        return new HtmlString('</'.$this->tag.'>');
    }

    public function renderContent()
    {
        $result = '';

        foreach ((array) $this->content as $content) {
            $result .= e($content);
        }

        return $result;
    }

    public function __get($name)
    {
        if (isset ($this->content[$name])) {
            return $this->content[$name];
        }

        throw new \InvalidArgumentException("The property $name does not exist in this [{$this->tag}] element");
    }
}
