<?php

namespace Styde\Html;

use Styde\Html\Form\HiddenInput;
use Illuminate\Support\HtmlString;

class Form extends Htmltag
{
    public function __construct($tag, $children = [], array $attributes = [])
    {
        parent::__construct('form', $children, $attributes);
    }

    public function route($name, $parameters = [], $absolute = true)
    {
        return $this->attr('action', app('url')->route($name, $parameters, $absolute));
    }

    public function withFiles()
    {
        return $this->attr('enctype', 'multipart/form-data');
    }

    public function renderHiddenFields()
    {
        $html = '';

        foreach ($this->content as $child) {
            if ($child instanceof HiddenInput) {
                $html .= $child->render();
            }
        }

        return new HtmlString($html);
    }
}
