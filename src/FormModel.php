<?php

namespace Styde\Html;

use Illuminate\Support\HtmlString;
use Styde\Html\FormModel\FieldCollection;
use Styde\Html\FormModel\ButtonCollection;
use Illuminate\Contracts\Support\Htmlable;

abstract class FormModel implements Htmlable
{
    /**
     * @var \Styde\Html\FormBuilder
     */
    protected $formBuilder;

    /**
     * @var \Styde\Html\Theme
     */
    protected $theme;

    /**
     * @var array|\Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var \Styde\Html\FormElement
     */
    public $form;

    /**
     * @var \Styde\Html\FormModel\FieldCollection
     */
    public $fields;

    /**
     * @var \Styde\Html\FormModel\ButtonCollection
     */
    public $buttons;

    public $method = 'post';

    public $customTemplate;

    public function __construct(FormBuilder $formBuilder, FieldCollection $fields, ButtonCollection $buttons, Theme $theme)
    {
        $this->formBuilder = $formBuilder;
        $this->theme = $theme;

        $this->form = $formBuilder->make($this->method());
        $this->fields = $fields;
        $this->buttons = $buttons;

        app()->call([$this, 'setup']);
    }

    /**
     * @return string
     */
    public function method()
    {
        return $this->method;
    }

    /**
     * Setup the form attributes, adds the fields and buttons.
     */
    abstract public function setup();

    public function useTemplate($template)
    {
        $this->customTemplate = $template;

        return $this;
    }

    public function model($model)
    {
        $this->model = $model;
        return $this;
    }

    public function toHtml()
    {
        return $this->render();
    }

    public function render($customTemplate = null)
    {
        return $this->theme->render($customTemplate ?: $this->customTemplate, [
            'form'    => $this->form,
            'fields'  => $this->fields,
            'buttons' => $this->buttons,
        ], 'form');
    }
}
