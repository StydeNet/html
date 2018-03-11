<?php

namespace Styde\Html;

use Styde\Html\Form\HiddenInput;
use Illuminate\Support\HtmlString;
use Styde\Html\Facades\Form as FormBuilder;

class Form extends Htmltag
{
    private $model;

    public function __construct($tag, $children = [], array $attributes = [])
    {
        parent::__construct('form', $children, $attributes);
    }

    public function route($name, $parameters = [], $absolute = true)
    {
        return $this->attr('action', app('url')->route($name, $parameters, $absolute));
    }

    public function model($model)
    {
        $this->model = $model;
    }

    public function withFiles()
    {
        return $this->attr('enctype', 'multipart/form-data');
    }

    public function open()
    {
        if ($this->model) {
            FormBuilder::setCurrentModel($this->model);
        }

        return new HtmlString(
            '<'.$this->tag.$this->renderAttributes().'>'
            .$this->renderHiddenFields()
        );
    }

    public function close()
    {
        if ($this->model) {
            FormBuilder::clearCurrentModel();
        }

        return parent::close();
    }

    protected function renderHiddenFields()
    {
        $html = '';

        foreach ($this->content as $child) {
            if ($child instanceof HiddenInput) {
                $html .= $child->render();
            }
        }

        return $html;
    }
}
