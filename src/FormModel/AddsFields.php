<?php

namespace Styde\Html\FormModel;

trait AddsFields
{
    /**
     * Add a text field.
     *
     * @param  string $name
     *
     * @return \Styde\Html\FormModel\Field
     */
    public function text($name)
    {
        return $this->fields->add($name, 'text');
    }

    /**
     * Add a textarea field.
     *
     * @param  string $name
     *
     * @return \Styde\Html\FormModel\Field
     */
    public function textarea($name)
    {
        return $this->fields->add($name, 'textarea');
    }
    /**
     * Add a email field.
     *
     * @param  string $name
     *
     * @return \Styde\Html\FormModel\Field
     */
    public function email($name)
    {
        return $this->fields->add($name, 'email');
    }

    /**
     * Add a password field.
     *
     * @param  string $name
     *
     * @return \Styde\Html\FormModel\Field
     */
    public function password($name)
    {
        return $this->fields->add($name, 'password');
    }

    /**
     * Add a checkbox field.
     *
     * @param  string  $name
     * @param  mixed   $value
     *
     * @return \Styde\Html\FormModel\Field
     */
    public function checkbox($name)
    {
        return $this->fields->add($name, 'checkbox');
    }

    /**
     * Add a select box field with options $options.
     *
     * @param  string $name
     * @param  array  $options
     *
     * @return \Styde\Html\FormModel\Field
     */
    public function select($name, array $options = array())
    {
        return $this->fields->add($name, 'select')->options($options);
    }

    /**
     * Add radios with options given.
     *
     * @param  string $name
     * @param  array  $options
     *
     * @return \Styde\Html\FormModel\Field
     */
    public function radios($name, array $options = array())
    {
        return $this->fields->add($name, 'radios')->options($options);
    }

    /**
     * Add checkboxes with options given.
     *
     * @param  string $name
     * @param  array  $options
     *
     * @return \Styde\Html\FormModel\Field
     */
    public function checkboxes($name, array $options = array())
    {
        return $this->fields->add($name, 'checkboxes')->options($options);
    }

    /**
     * Add a url field.
     *
     * @param  string $name
     *
     * @return \Styde\Html\FormModel\Field
     */
    public function url($name)
    {
        return $this->fields->add($name, 'url');
    }

    /**
     * Add a file field.
     *
     * @param  string $name
     *
     * @return \Styde\Html\FormModel\Field
     */
    public function file($name)
    {
        return $this->fields->add($name, 'file');
    }
}
