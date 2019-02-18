<?php

namespace Styde\Html;

use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Support\Htmlable;

class VoidTag extends BaseTag implements Htmlable
{
    /**
     * Returns a new void element
     *
     * @return HtmlString|mixed
     */
    public function render()
    {
        if ($this->included) {
            return new HtmlString($this->renderOpenTag());
        }
    }
}
