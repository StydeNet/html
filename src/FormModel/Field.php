<?php

namespace Styde\Html\FormModel;

use Styde\Html\FieldBuilder;
use Styde\Html\HandlesAccess;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Support\Htmlable;

class Field implements Htmlable
{
    use HasAttributes, HandlesAccess, ValidationRules, IncludeRulesHelpers;

    /**
     * @var \Styde\Html\FieldBuilder
     */
    protected $fieldBuilder;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $type;
    /**
     * @var mixed
     */
    public $value;
    /**
     * @var string
     */
    public $label;
    /**
     * @var string
     */
    public $helpText;
    /**
     * @var template
     */
    public $template;
    /**
     * @var array
     */
    public $attributes = [];
    /**
     * @var array
     */
    public $extra = [];
    /**
     * @var array
     */
    public $styles = [];
    /**
     * @var array
     */
    public $scripts = [];

    /**
     * @var array
     */
    protected $options = [];

    protected $table;

    protected $tableText;

    protected $tableId;

    protected $query;

    /**
     * Field constructor.
     * @param FieldBuilder $fieldBuilder
     * @param $name
     * @param string $type
     */
    public function __construct(FieldBuilder $fieldBuilder, $name, $type = 'text')
    {
        $this->fieldBuilder = $fieldBuilder;
        $this->name = $name;
        $this->type = $type;

        $this->addRuleByFieldType($type);
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
        $this->label = $label;

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
        $this->label = new HtmlString($html);

        return $this;
    }

    /**
     * @param $helpText
     * @return $this
     */
    public function helpText($helpText)
    {
        $this->helpText = $helpText;

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
        $this->helpText = new HtmlString($html);

        return $this;
    }
    /**
     * @param $value
     * @return $this
     */
    public function value($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Add a custom field template and optionally pass extra vars to it.
     *
     * @param $template
     * @param array $vars
     * @return $this
     */
    public function template($template, $vars = [])
    {
        $this->template = $template;
        $this->with($vars);

        return $this;
    }

    public function style($style)
    {
        $this->styles[] = $style;

        return $this;
    }

    public function script($script)
    {
        $this->scripts[] = $script;

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
            $this->extra = array_merge($this->extra, $values);
        } else {
            $this->extra[$values] = $value;
        }
        return $this;
    }

    /**
     * @param $options
     * @return $this
     */
    public function options(array $options)
    {
        $this->options = $options;

        $this->withRule(Rule::in(array_keys($options)));

        return $this;
    }

    /**
     * @param $table
     * @param $text
     * @param string $id
     * @param null $query
     * @return $this
     */
    public function from($table, $text, $id = 'id', $query = null)
    {
        $this->table = $table;
        $this->tableText = $text;
        $this->tableId = $id;
        $this->query = $query;

        $this->setRuleExists();

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        if ($this->table) {
            $query = DB::table($this->table);

            if ($this->query) {
                call_user_func($this->query, $query);
            }

            return $query->pluck($this->tableText, $this->tableId)->all();
        }

        return $this->options;
    }

    /**
     * @return string
     */
    public function render()
    {
        if ($this->included) {
            return $this->fieldBuilder->render($this);
        }
    }

    /**
     * @param $value
     * @return $this
     */
    public function placeholder($value)
    {
        $this->setAttribute('placeholder', $value);

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
