<?php

namespace Styde\Html;

use Illuminate\Contracts\Routing\UrlGenerator;
use Collective\Html\FormBuilder as CollectiveFormBuilder;

class FormBuilder extends CollectiveFormBuilder
{
    /**
     * Whether to deactivate or not the HTML5 validation (in order to test
     * backend validation).
     *
     * @var bool $novalidate
     */
    protected $novalidate = false;
    /**
     * The Theme object in charge of rendering the right view for this theme
     *
     * @var \Styde\Html\Theme
     */
    protected $theme;

    /**
     * Creates a new Form Builder class. This extends from the Collective
     * Form Builder but adds a couple of extra functions.
     *
     * @param \Styde\Html\HtmlBuilder $html
     * @param \Illuminate\Contracts\Routing\UrlGenerator $url
     * @param string $csrfToken
     * @param \Styde\Html\Theme $theme
     */
    public function __construct(HtmlBuilder $html, UrlGenerator $url, $csrfToken, Theme $theme)
    {
        parent::__construct($html, $url, $theme->getView(), $csrfToken);

        $this->theme = $theme;
    }

    /**
     * Allows user to set the novalidate option for every form generated with
     * the form open method, so developers can skin HTML5 validation, in order
     * to test backend validation in a local or development environment.
     *
     * @param null $value
     * @return bool|null
     */
    public function novalidate($value = null)
    {
        if ($value !== null) {
            $this->novalidate = $value;
        }

        return $this->novalidate;
    }

    /**
     * Open up a new HTML form and pass the optional novalidate option.
     * This methods relies on the original Form::open method of the Laravel
     * Collective component.
     *
     * @param array $options
     *
     * @return string
     */
    public function open(array $options = array())
    {
        if ($this->novalidate()) {
            $options[] = 'novalidate';
        }

        return parent::open($options);
    }

    /**
     * Get the protected model attribute
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Create a time input field.
     *
     * @param string $name
     * @param string $value
     * @param array  $options
     *
     * @return string
     */
    public function time($name, $value = null, $options = array())
    {
        return $this->input('time', $name, $value, $options);
    }

    /**
     * Create a list of radios.
     *
     * This function is very similar to Form::select but it generates a
     * collection of radios instead of options.
     *
     * i.e. Form::radios('status', ['a' => 'Active', 'i' => 'Inactive'])
     *
     * You can pass 'inline' as a value of the attribute's array, to set the
     * radios as inline (they'll be rendered with the 'radios-inline' template).
     *
     * @param string $name
     * @param array  $options
     * @param string $selected
     * @param array  $attributes
     * @param bool   $hasErrors
     *
     * @return string
     */
    public function radios($name, $options = array(), $selected = null, $attributes = array(), $hasErrors = false)
    {
        $selected = $this->getValueAttribute($name, $selected);

        $defaultTemplate = in_array('inline', $attributes)
            ? 'forms.radios-inline'
            : 'forms.radios';

        $template = isset($attributes['template'])
            ? $attributes['template']
            : null;

        $radios = [];

        foreach ($options as $value => $label) {
            $radios[] = [
                'name'     => $name,
                'value'    => $value,
                'label'    => $label,
                'selected' => $selected == $value,
                'id'       => $name.'_'.Str::slug($value),
            ];
        }

        unset ($attributes['inline'], $attributes['template']);

        return $this->theme->render(
            $template,
            compact('name', 'radios', 'attributes', 'classes', 'hasErrors'),
            $defaultTemplate
        );
    }

    /**
     * Create a list of checkboxes.
     *
     * This function is similar to Form::select, but it generates a collection
     * of checkboxes instead of options.
     *
     * i.e. Form::checkboxes('status', ['a' => 'Active', 'i' => 'Inactive']);
     *
     * You can pass 'inline' as a value of the attribute's array, to set the
     * checkboxes as inline (they'll be rendered using the 'checkboxes-inline'
     * template).
     *
     * @param string $name
     * @param array  $options
     * @param string $selected
     * @param array  $attributes
     * @param bool   $hasErrors
     *
     * @return string
     */
    public function checkboxes($name, $options = array(), $selected = null, $attributes = array(), $hasErrors = false)
    {
        $selected = $this->getValueAttribute($name, $selected);

        if (is_null($selected)) {
            $selected = array();
        }

        $defaultTemplate = in_array('inline', $attributes)
            ? 'forms.checkboxes-inline'
            : 'forms.checkboxes';

        $template = isset($attributes['template'])
            ? $attributes['template']
            : null;

        $checkboxes = [];

        foreach ($options as $value => $label) {
            $checkboxes[] = [
                'name'    => $name.'[]',
                'value'   => $value,
                'label'   => $label,
                'checked' => is_array($selected) && in_array($value, $selected),
                'id'      => $name.'_'.Str::slug($value)
            ];
        }

        unset ($attributes['inline'], $attributes['template']);

        return $this->theme->render(
            $template,
            compact('name', 'checkboxes', 'attributes', 'hasErrors'),
            $defaultTemplate
        );
    }
}
