<?php

namespace Styde\Html;

use  Illuminate\Support\Arr;
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
        return new HtmlString('<'.$this->tag.$this->renderAttributes().'>');
    }
}
