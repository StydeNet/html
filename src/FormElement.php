<?php

namespace Styde\Html;

use Illuminate\Support\HtmlString;
use Styde\Html\Facades\Form;
use Styde\Html\Form\HiddenInput;

class FormElement extends HtmlElement
{
    /**
     * FormElement constructor.
     *
     * @param string $method
     * @param array $attributes
     */
    public function __construct($method, array $attributes = [])
    {
        $children = [];

        if ($method != 'get') {
            $children['_token'] = Form::hidden('_token', csrf_token());
        }

        if (in_array($method, ['put', 'patch', 'delete'])) {
            $children['_method'] = Form::hidden('_method', $method);

            $method = 'post';
        }

        $attributes['method'] = $method;

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
