<?php

namespace Styde\Html\Fields;

use Styde\Html\HandlesAccess;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Support\Htmlable;

class FieldBuilder implements Htmlable
{
    use HasAttributes, HandlesAccess, ValidationRules, IncludeRulesHelpers;

    /**
     * @var \Styde\Html\Fields\Field
     */
    protected $field;

    /**
     * Field constructor.
     *
     * @param $name
     * @param string $type
     */
    public function __construct($name, $type = 'text')
    {
        $this->field = new Field($name, $type);

        $this->addRuleByFieldType($type);
    }

    public function getField()
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @param $label
     * @return $this
     */
    public function label($label)
    {
        $this->field->label = $label;

        return $this;
    }

    /**
     * Add a label that contains HTML (be careful because it won't be escaped).
     *
     * @param $html
     * @return $this
     */
    public function rawLabel($html)
    {
        $this->field->label = new HtmlString($html);

        return $this;
    }

    /**
     * @param $helpText
     * @return $this
     */
    public function helpText($helpText)
    {
        $this->field->helpText = $helpText;

        return $this;
    }

    /**
     * Add a help Text that contains HTML (be careful because it won't be escaped).
     *
     * @param $html
     * @return $this
     */
    public function rawHelpText($html)
    {
        $this->field->helpText = new HtmlString($html);

        return $this;
    }

    /**
     * Sets the value in the field.
     *
     * @param $value
     * @return $this
     */
    public function value($value)
    {
        $this->field->value = $value;

        return $this;
    }

    /**
     * Add a custom field template and optionally pass extra vars to it.
     *
     * @param $template
     * @param array $vars
     *
     * @return $this
     */
    public function template($template, $vars = [])
    {
        $this->field->setTemplate($template, $vars);

        return $this;
    }

    /**
     * Indicate the field should be rendered as a single control only
     * (input, select, etc.) and not with its full field template.
     *
     * @return $this
     */
    public function controlOnly()
    {
        $this->field->controlOnly = true;

        return $this;
    }

    /**
     * Indicate the field should be rendered as a full field template.
     *
     * @return $this
     */
    public function fullField()
    {
        $this->field->controlOnly = false;

        return $this;
    }

    public function style($style)
    {
        $this->field->styles[] = $style;

        return $this;
    }

    public function script($script)
    {
        $this->field->scripts[] = $script;

        return $this;
    }

    /**
     * Add extra variables to the field template
     *
     * @param $values
     * @param bool $value
     * @return $this
     */
    public function with($values, $value = true)
    {
        if (is_array($values)) {
            $this->field->mergeData($values);
        } else {
            $this->field->setData($values, $value);
        }

        return $this;
    }

    /**
     * Set the options and the in rule in the field.
     *
     * @param $options
     * @return $this
     */
    public function options(array $options)
    {
        $this->field->options = $options;
        $this->field->addRule(Rule::in(array_keys($options)));

        return $this;
    }

    /**
     * Set dynamic options and the exists rule in the field.
     *
     * @param $table
     * @param $text
     * @param string $id
     * @param null $customQuery
     * @return $this
     */
    public function from($table, $text, $id = 'id', $customQuery = null)
    {
        $this->field->options = function () use ($table, $customQuery, $text, $id) {
            $q = DB::table($table);

            if ($customQuery) {
                call_user_func($customQuery, $q);
            }

            return $q->pluck($text, $id)->all();
        };

        $this->field->addRule(Rule::exists($table, $id)->where($customQuery));

        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        if (!$this->included) {
            return '';
        }

        return app('field.renderer')->render($this->field);
    }

    /**
     * Set the placeholder attribute in the field.
     *
     * @param $value
     * @return $this
     */
    public function placeholder($value)
    {
        $this->field->setAttribute('placeholder', $value);

        return $this;
    }

    /**
     * Render field with all attributes
     *
     * @return string
     */
    public function toHtml()
    {
        return $this->render();
    }
}
