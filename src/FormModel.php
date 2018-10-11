<?php

namespace Styde\Html;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Htmlable;
use Styde\Html\FormModel\{FieldCollection, ButtonCollection};

class FormModel implements Htmlable
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
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var \Styde\Html\Form
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

    /**
     * Form Model constructor.
     *
     * @param FormBuilder $formBuilder
     * @param FieldCollection $fields
     * @param ButtonCollection $buttons
     * @param Theme $theme
     */
    public function __construct(FormBuilder $formBuilder, FieldCollection $fields, ButtonCollection $buttons, Theme $theme)
    {
        $this->formBuilder = $formBuilder;
        $this->fields = $fields;
        $this->buttons = $buttons;

        $this->theme = $theme;
    }

    /**
     * Set the form method as post.
     *
     * @return $this
     */
    public function forCreation()
    {
        $this->method = 'post';
        return $this;
    }

    /**
     * Set the form method as put.
     *
     * @return $this
     */
    public function forUpdate()
    {
        $this->method = 'put';
        return $this;
    }

    /**
     * Run the setup.
     *
     * @return void
     */
    protected function runSetup()
    {
        if ($this->form) {
            return;
        }

        $this->form = $this->formBuilder->make($this->method());

        $this->setup();

        if ($this->method() == 'post') {
            return $this->creationSetup();
        }

        if ($this->method() == 'put') {
            return $this->updateSetup();
        }
    }

    /**
     * Get Method
     *
     * @return string
     */
    public function method()
    {
        return $this->method;
    }

    /**
     * Setup the common form attributes, fields and buttons.
     *
     * @return void
     */
    public function setup()
    {
        //...
    }

    /**
     * Setup the form attributes, fields and buttons for creation.
     * Called after setup, common fields will be available here.
     *
     * @return void
     */
    public function creationSetup()
    {
        //...
    }

    /**
     * Setup the form attributes, form fields and buttons for update.
     * Called after setup, common fields will be available here.
     *
     * @return void
     */
    public function updateSetup()
    {
        //...
    }

    /**
     * Set a new custom template
     *
     * @param string $template
     * @return $this
     */
    public function template($template)
    {
        $this->customTemplate = $template;

        return $this;
    }

    /**
     * Set a new Model
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return $this
     */
    public function model($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Render all form to Html
     *
     * @return string
     */
    public function toHtml()
    {
        return $this->render();
    }

    /**
     * @param string|null $customTemplate
     * @return string
     */
    public function render($customTemplate = null)
    {
        $this->runSetup();

        return $this->theme->render($customTemplate ?: $this->customTemplate, [
            'form' => $this->form,
            'fields' => $this->fields,
            'buttons' => $this->buttons,
        ], 'form');
    }

    /**
     * Validate the request with the validation rules specified
     *
     * @param Request|null $request
     * @return mixed
     */
    public function validate(Request $request = null)
    {
        return ($request ?: request())->validate($this->getValidationRules());
    }

    /**
     * Get all rules of validation
     *
     * @return array
     */
    public function getValidationRules()
    {
        $this->runSetup();

        $rules = [];

        foreach ($this->fields->all() as $name => $field) {
            if ($field->included) {
                $rules[$name] = $field->getValidationRules();
            }
        }

        return $rules;
    }

    public function __call($method, $parameters = [])
    {
        if (method_exists($this->form, $method)) {
            return $this->form->$method(...$parameters);
        }

        if (method_exists($this->buttons, $method)) {
            return $this->buttons->$method(...$parameters);
        }

        return $this->fields->$method(...$parameters);
    }

    /**
     * Get a field by name.
     *
     * @param  string $name
     *
     * @return Field
     */
    public function __get($name)
    {
        return $this->fields->$name;
    }
}
