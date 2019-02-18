<?php

namespace Styde\Html;

use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Support\Htmlable;

class TextElement extends HtmlString implements Htmlable
{
    public function __construct($text)
    {
        parent::__construct(e($text));
    }
    /**
     * Returns a new string
     *
     * @return HtmlString
     */
    public function render()
    {
        return $this->html;
    }
}
