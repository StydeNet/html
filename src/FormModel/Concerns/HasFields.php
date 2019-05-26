<?php

namespace Styde\Html\FormModel\Concerns;

use Styde\Html\Facades\Html;
use Styde\Html\FormModel\Field;
use Styde\Html\FormModel\HiddenField;

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
        return $this->addField($name, 'text');
    }

    /**
     * Add a number field.
     *
     * @param  string $name
     *
     * @return \Styde\Html\FormModel\Field
     */
    public function number($name)
    {
        return $this->addField($name, 'number');
    }

    /**
     * Add an integer field.
     *
     * @param  string $name
     *
     * @return \Styde\Html\FormModel\Field
     */
    public function integer($name)
    {
        return $this->addField($name, 'integer');
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
        return $this->addField($name, 'textarea');
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
        return $this->addField($name, 'email');
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
        return $this->addField($name, 'password');
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
        return $this->addField($name, 'checkbox');
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
        return $this->addField($name, 'select')->options($options);
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
        return $this->addField($name, 'radios')->options($options);
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
        return $this->addField($name, 'checkboxes')->options($options);
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
        return $this->addField($name, 'url');
    }

    /**
     * Add a file field.
     *
     * @param  string $name
     * @return \Styde\Html\FormModel\Field
     */
    public function file($name)
    {
        $this->acceptFiles();

        return $this->addField($name, 'file');
    }

    /**
     * Add a hidden field.
     *
     * @param  string $name
     * @return \Styde\Html\FormModel\Field
     */
    function hidden($name)
    {
        return $this->fields->add(new HiddenField($name), $name);
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
     * Add a field to the fields collection.
     *
     * @param string $name
     * @param string $type
     *
     * @return \Styde\Html\FormModel\Field
     */
    public function addField($name, $type = 'text')
    {
        return $this->fields->add(new Field($name, $type), $name);
    }

    /**
     * Get all the fields in the fields collection.
     *
     * @return array
     */
    public function getFields()
    {
        return $this->fields->filter(function ($field) {
            return $field instanceof Field;
        });
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
