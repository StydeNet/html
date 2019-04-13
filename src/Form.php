<?php

namespace Styde\Html;

use Styde\Html\Form\HiddenInput;
use Illuminate\Support\HtmlString;
use Styde\Html\Facades\Form as FormBuilder;

class Form extends Htmltag
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    private $model;

    /**
     * Creates a new Form class.
     *
     * @param $tag
     * @param array $children
     * @param array $attributes
     */
    public function __construct($tag, $children = [], array $attributes = [])
    {
        parent::__construct('form', $children, $attributes);
    }

    /**
     * Add the action attribute to the tag form with the value of a specified route
     *
     * @param string $name
     * @param array $parameters
     * @param bool $absolute
     * @return Form
     */
    public function route($name, $parameters = [], $absolute = true)
    {
        return $this->attr('action', app('url')->route($name, $parameters, $absolute));
    }

    /**
     * Set a new Model Class
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function model($model)
    {
        $this->model = $model;
    }

    /**
     * Add the necessary attribute to allow files
     *
     * @return Form
     */
    public function withFiles()
    {
        return $this->attr('enctype', 'multipart/form-data');
    }

    /**
     * Open the form tag
     *
     * @return HtmlString
     */
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

    /**
     * Close the form tag
     *
     * @return HtmlString
     */
    public function close()
    {
        if ($this->model) {
            FormBuilder::clearCurrentModel();
        }

        return parent::close();
    }

    /**
     * Render the inputs with the hidden attribute
     *
     * @return string
     */
    protected function renderHiddenFields()
    {
        return array_reduce($this->content, function ($result, $child) {
            if ($child instanceof HiddenInput) {
                $result .= $child->render();
            }

            return $result;
        }, '');
    }
}
