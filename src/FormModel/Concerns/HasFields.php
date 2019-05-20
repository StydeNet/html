<?php

namespace Styde\Html\FormModel\Concerns;

use Styde\Html\Facades\Html;

trait HasFields
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
        return $this->fields->addField($name, 'text');
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
        return $this->fields->addField($name, 'textarea');
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
        return $this->fields->addField($name, 'email');
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
        return $this->fields->addField($name, 'password');
    }

    /**
     * Add a checkbox field.
     *
     * @param  string  $name
     *
     * @return \Styde\Html\FormModel\Field
     */
    public function checkbox($name)
    {
        return $this->fields->addField($name, 'checkbox');
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
        return $this->fields->addField($name, 'select')->options($options);
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
        return $this->fields->addField($name, 'radios')->options($options);
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
        return $this->fields->addField($name, 'checkboxes')->options($options);
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
        return $this->fields->addField($name, 'url');
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
        return $this->fields->addField($name, 'file');
    }

    /**
     * Add an HTML element to the field collection.
     *
     * @param $tag
     * @param string $content
     * @param array $attributes
     * @return HtmlString
     *
     */
    public function tag($tag, $content = '', array $attributes = [])
    {
        return $this->fields->add(Html::tag($tag, $content, $attributes));
    }

    /**
     * Render all the elements in the fields collection.
     *
     * @return string
     */
    public function renderFields()
    {
        return $this->fields->render();
    }
}
