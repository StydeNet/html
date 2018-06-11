<?php

namespace Styde\Html\FormModel;

use Styde\Html\FieldBuilder;
use Styde\Html\HandlesAccess;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Htmlable;

class Field implements Htmlable
{
    use HasAttributes, HandlesAccess, ValidationRules;

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
     * @param $value
     * @return $this
     */
    public function value($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param $template
     * @return $this
     */
    public function template($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @param $values
     * @param bool $value
     * @return $this
     */
    public function extra($values, $value = true)
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
    public function options($options)
    {
        $this->options = $options;

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
    public function getType()
    {
        return $this->type;
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
     * @param $value
     * @return Field
     */
    public function minlength($value)
    {
        $this->setAttribute('minlength', $value);

        return $this->setRule("min:$value");
    }

    /**
     * @param $value
     * @return Field
     */
    public function maxlength($value)
    {
        $this->setAttribute('maxlength', $value);

        return $this->setRule("max:$value");
    }

    /**
     * @param $value
     * @return Field
     */
    public function pattern($value)
    {
        $this->setAttribute('pattern', $value);

        return $this->setRule("regex:/$value/");
    }

    /**
     * @param $value
     * @return Field
     */
    public function min($value)
    {
        $this->setAttribute('min', $value);

        return $this->setRule("min:$value");
    }

    /**
     * @param $value
     * @return Field
     */
    public function max($value)
    {
        $this->setAttribute('max', $value);

        return $this->setRule("max:$value");
    }

    /**
     * @param $value
     * @return Field
     */
    public function size($value)
    {
        $this->setAttribute('size', $value);

        return $this->setRule("size:$value");
    }

    /**
     * @return Field
     */
    public function required()
    {
        $this->setAttribute('required');

        $this->disableRules('nullable');

        return $this->setRule('required');
    }

    /**
     * @param $column
     * @param $value
     * @return Field
     */
    public function requiredIf($column, $value)
    {
        return $this->setRule("required_if:$column,$value");
    }

    /**
     * @param $column
     * @param $operator
     * @param null $value
     * @return Field
     */
    public function requiredUnless($column, $operator, $value = null)
    {
        if (! $value) {
            return $this->setRule("required_unless:$column,$operator");
        }

        return $this->setRule("required_unless:$column,$operator,$value");
    }

    /**
     * @param array $values
     * @return Field
     */
    public function requiredWith(...$values)
    {
        $value = implode(',', $values);

        return $this->setRule("required_with:$value");
    }

    /**
     * @param array $values
     * @return Field
     */
    public function requiredWithAll(...$values)
    {
        $value = implode(',', $values);

        return $this->setRule("required_with_all:$value");
    }

    /**
     * @param array $values
     * @return Field
     */
    public function requiredWithout(...$values)
    {
        $value = implode(',', $values);

        return $this->setRule("required_without:$value");
    }

    /**
     * @param $value
     * @return Field
     */
    public function requiredWithoutAll(...$values)
    {
        $value = implode(',', $values);

        return $this->setRule("required_without_all:$value");
    }

    /**
     * @return Field
     */
    public function nullable()
    {
        $this->disableRules('required');

        return $this->setRule('nullable');
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        return $this->render();
    }
}
