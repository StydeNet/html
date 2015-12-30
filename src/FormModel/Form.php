<?php

namespace Styde\Html\FormModel;

use Styde\Html\FormBuilder;
use Styde\Html\Theme;

class Form
{
    use HasAttributes;

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
     * @var array
     */
    protected $attributes = [];
    /**
     * @var array
     */
    protected $buttons = [];
    /**
     * @var ButtonCollection
     */
    private $buttonCollection;

    public function __construct(
        FormBuilder $formBuilder,
        FieldCollection $fieldCollection,
        ButtonCollection $buttonCollection,
        Theme $theme
    ) {
        $this->formBuilder = $formBuilder;
        $this->fieldCollection = $fieldCollection;
        $this->buttonCollection = $buttonCollection;
        $this->theme = $theme;

        $this->setup($fieldCollection, $buttonCollection);
    }

    public function fields()
    {
        return $this->fieldCollection;
    }

    public function buttons()
    {
        return $this->buttonCollection;
    }

    /**
     * You can use this method to extend the form model and
     * then add fields and buttons.
     *
     * @param  \Styde\Html\FieldCollection  $fields
     * @param  \Styde\Html\ButtonCollection $buttons
     */
    protected function setup($fields, $buttons)
    {
    }

    public function add($name, $type = 'text')
    {
        return $this->fieldCollection->add($name, $type);
    }

    public function model($model)
    {
        $this->model = $model;
        return $this;
    }

    public function route()
    {
        return $this->attributes('route', func_get_args());
    }

    public function url($url)
    {
        return $this->attributes('url', $url);
    }

    public function method($method)
    {
        return $this->attributes('method', $method);
    }

    public function classes($classes)
    {
        return $this->attributes('class', $classes);
    }

    public function withFiles()
    {
        return $this->attributes('enctype', 'multipart/form-data');
    }

    public function open($extraAttributes = array())
    {
        $attributes = array_merge($this->attributes, $extraAttributes);

        return $this->model == null ?
            $this->formBuilder->open($attributes)
            : $this->formBuilder->model($this->model, $attributes);
    }

    public function close()
    {
        return $this->formBuilder->close();
    }

    public function render($customTemplate = null)
    {
        return $this->theme->render($customTemplate, [
            'form'    => $this,
            'fields'  => $this->fieldCollection,
            'buttons' => $this->buttonCollection,
        ], 'form');
    }

}