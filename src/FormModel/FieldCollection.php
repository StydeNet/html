<?php

namespace Styde\Html\FormModel;

use Styde\Html\FieldBuilder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Traits\Macroable;

class FieldCollection
{
    use Macroable;

    /**
     * @var \Styde\Html\FieldBuilder
     */
    protected $fieldBuilder;
    
    /**
     *  List of the fields form.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Creates a new FieldCollection class.
     *
     * @param FieldBuilder $fieldBuilder
     */
    public function __construct(FieldBuilder $fieldBuilder)
    {
        $this->fieldBuilder = $fieldBuilder;
    }

    /**
     * Returns true , false otherwise.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->fields);
    }

    /**
     * Dynamically handle calls to the field builder.
     *
     * @param  string $method
     * @param  array $params
     *
     * @return \Styde\Html\FieldBuilder
     */
    public function __call($method, $params)
    {
        return $this->add($params[0], $method);
    }

    /**
     * Call the render method.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
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
        return $this->fields[$name];
    }

    /**
     * Add a text field.
     *
     * @param  string $name
     *
     * @return Field
     */
    public function text($name)
    {
        return $this->add($name, 'text');
    }

    /**
     * Add a textarea field.
     *
     * @param  string $name
     *
     * @return Field
     */
    public function textarea($name)
    {
        return $this->add($name, 'textarea');
    }
    /**
     * Add a email field.
     *
     * @param  string $name
     *
     * @return Field
     */
    public function email($name)
    {
        return $this->add($name, 'email');
    }

    /**
     * Add a password field.
     *
     * @param  string $name
     *
     * @return Field
     */
    public function password($name)
    {
        return $this->add($name, 'password');
    }

    /**
     * Add a checkbox field.
     *
     * @param  string  $name
     * @param  mixed   $value
     *
     * @return Field
     */
    public function checkbox($name, $value = 1)
    {
        return $this->add($name, 'checkbox')->options($value);
    }

    /**
     * Add a select box field with options $options.
     *
     * @param  string $name
     * @param  array  $options
     *
     * @return Field
     */
    public function select($name, array $options = array())
    {
        return $this->add($name, 'select')->options($options);
    }

    /**
     * Add radios with options given.
     *
     * @param  string $name
     * @param  array  $options
     *
     * @return Field
     */
    public function radios($name, array $options = array())
    {
        return $this->add($name, 'radios')->options($options);
    }

    /**
     * Add checkboxes with options given.
     *
     * @param  string $name
     * @param  array  $options
     *
     * @return Field
     */
    public function checkboxes($name, array $options = array())
    {
        return $this->add($name, 'checkboxes')->options($options);
    }

    /**
     * Add a url field.
     *
     * @param  string $name
     *
     * @return Field
     */
    public function url($name)
    {
        return $this->add($name, 'url');
    }

    /**
     * Add a file field.
     *
     * @param  string $name
     *
     * @return Field
     */
    public function file($name)
    {
        return $this->add($name, 'file');
    }

    /**
     * Add a field.
     *
     * @param string $name
     * @param string $type
     *
     * @return Field
     */
    public function add($name, $type = 'text')
    {
        return $this->fields[$name] = new Field($this->fieldBuilder, $name, $type);
    }

    /**
     * Get the fields array.
     *
     * @return array
     */
    public function all()
    {
        return $this->fields;
    }

    /**
     * Render all the fields.
     *
     * @return string
     */
    public function render()
    {
        $html = '';

        foreach ($this->all() as $field) {
            $html .= $field->render();
        }

        return new HtmlString($html);
    }
}
