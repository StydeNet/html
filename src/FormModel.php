<?php

namespace Styde\Html;

use BadMethodCallException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Styde\Html\Facades\Html;
use Illuminate\Support\HtmlString;
use Styde\Html\Fields\FieldBuilder;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Support\Htmlable;
use Styde\Html\FormModel\ElementCollection;
use Styde\Html\FormModel\Concerns\HasFields;
use Styde\Html\FormModel\Concerns\HasButtons;

class FormModel implements Htmlable
{
    use HasFields, HasButtons, Macroable;

    /**
     * @var \Styde\Html\FormBuilder
     */
    protected $formBuilder;

    /**
     * @var \Styde\Html\HtmlBuilder
     */
    private $htmlBuilder;

    /**
     * @var \Styde\Html\FormFieldBuilder
     */
    private $fieldBuilder;

    /**
     * @var \Styde\Html\Theme
     */
    protected $theme;

    /**
     * @var \Styde\Html\Form
     */
    public $form;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $model;

    /**
     * @var \Styde\Html\FormModel\ElementCollection
     */
    public $fields;

    /**
     * @var \Styde\Html\FormModel\ElementCollection
     */
    public $buttons;

    /**
     * @var string
     */
    public $method = 'post';

    /**
     * @var string
     */
    public $customTemplate;

    /**
     * Form Model constructor.
     *
     * @param HtmlBuilder $htmlBuilder
     * @param FormBuilder $formBuilder
     * @param FormFieldBuilder $fieldBuilder
     * @param Theme $theme
     * @internal param ElementCollection $fields
     * @internal param ElementCollection $buttons
     */
    public function __construct(HtmlBuilder $htmlBuilder, FormBuilder $formBuilder, FormFieldBuilder $fieldBuilder, Theme $theme)
    {
        $this->formBuilder = $formBuilder;
        $this->htmlBuilder = $htmlBuilder;
        $this->fieldBuilder = $fieldBuilder;
        $this->theme = $theme;

        $this->fields = new ElementCollection;
        $this->buttons = new ElementCollection;
    }

    /**
     * Link the form to an specific route.
     *
     * @param string $name
     * @param array $parameters
     * @param bool $absolute
     * @return Form
     */
    public function route($name, $parameters = [], $absolute = true)
    {
        $this->runSetup();

        return $this->form->route($name, $parameters, $absolute);
    }

    /**
     * Indicate that the form should accept files.
     * @return null
     */
    public function acceptFiles()
    {
        $this->runSetup();

        $this->form->withFiles();
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

        if ($this->method() == 'post') {
            $this->creationSetup();
        } elseif ($this->method() == 'put') {
            $this->updateSetup();
        } else {
            $this->setup();
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
     * Setup common form attributes, fields and buttons.
     *
     * @return void
     */
    public function setup()
    {
        //...
    }

    /**
     * Setup form attributes, fields and buttons for creation.
     *
     * @return void
     */
    public function creationSetup()
    {
        $this->setup();
    }

    /**
     * Setup form attributes, form fields and buttons for update.
     *
     * @return void
     */
    public function updateSetup()
    {
        $this->setup();
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
     * Set the novalidate attribute for a form, so developers can
     * skip HTML5 validation, in order to test backend validation
     * in a local or development environment.
     *
     * @param boolean $value
     * @return $this
     */
    public function novalidate($value = true)
    {
        $this->runSetup();

        $this->form->novalidate($value);

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
     * Render all form into HTML.
     *
     * @param string|null $customTemplate
     * @return string
     */
    public function render($customTemplate = null)
    {
        $this->runSetup();

        return $this->theme->render($customTemplate ?: $this->customTemplate ?: '@form', [
            'form' => $this->form,
            'fields' => $this->fields,
            'buttons' => $this->buttons,
        ]);
    }

    /**
     * Get all Javascript scripts.
     *
     * @return array
     */
    public function scripts()
    {
        $this->runSetup();

        $scripts = [];

        foreach ($this->getFields() as $name => $builder) {
            $scripts = array_merge($scripts, $builder->getField()->scripts);
        }

        return array_values(array_unique($scripts));
    }

    /**
     * Render scripts into HTML.
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function renderScripts()
    {
        return new HtmlString(array_reduce($this->scripts(), function ($result, $script) {
            return $result . Html::script($script);
        }, ''));
    }

    /**
     * Get all CSS styles.
     *
     * @return array
     */
    public function styles()
    {
        $this->runSetup();

        $styles = [];

        foreach ($this->getFields() as $name => $builder) {
            $styles = array_merge($styles, $builder->getField()->styles);
        }

        return array_values(array_unique($styles));
    }

    /**
     * Render CSS styles into HTML.
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function renderStyles()
    {
        return new HtmlString(array_reduce($this->styles(), function ($result, $style) {
            return $result . Html::style($style);
        }, ''));
    }

    /**
     * Validate the request with the validation rules specified.
     *
     * @param Request|null $request
     * @return array
     * @throws ValidationException
     */
    public function validate(Request $request = null)
    {
        if ($request == null) {
            $request = request();
        }

        try {
            $data = $request->validate($this->getValidationRules());
        } catch (ValidationException $validationException) {
            $this->failedValidation($request);

            throw $validationException;
        }

        array_walk($data, function (&$value, $name) {
            if ($transformer = $this->fields->get($name)->getField()->transformer) {
                $value = $transformer->fromRequest($value);
            }
        });

        return $data;
    }

    /**
     * Callback method that will be called if the validation fails.
     *
     * @param Request $request
     */
    protected function failedValidation(Request $request)
    {
        //
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

        foreach ($this->fields->all() as $name => $builder) {
            if ($builder instanceof FieldBuilder && $builder->included) {
                $rules[$name] = $builder->getField()->getValidationRules();
            }
        }

        return $rules;
    }

    /**
     * Dynamically handle calls to the form model.
     *
     * @param  string $method
     * @param  array $parameters
     *
     * @return mixed
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters = [])
    {
        throw new BadMethodCallException(sprintf(
            'Call to undefined method %s::%s()', static::class, $method
        ));
    }

    /**
     * Get a field by name.
     *
     * @param  string $name
     *
     * @return \Styde\Html\Fields\FieldBuilder
     */
    public function __get($name)
    {
        return $this->fields->get($name);
    }
}
