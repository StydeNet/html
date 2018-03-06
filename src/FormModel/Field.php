<?php

namespace Styde\Html\FormModel;

use Styde\Html\FieldBuilder;
use Styde\Html\HandlesAccess;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Htmlable;

class Field implements Htmlable
{
    use HasAttributes, HandlesAccess;

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

    public function __construct(FieldBuilder $fieldBuilder, $name, $type = 'text')
    {
        $this->fieldBuilder = $fieldBuilder;

        $this->name = $name;
        $this->type = $type;
    }

    public function __toString()
    {
        return $this->render();
    }

    public function required($required = true)
    {
        if ($required) {
            $this->attributes['required'] = true;
        } else {
            unset($this->attributes['required']);
        }
        return $this;
    }

    public function label($label)
    {
        $this->label = $label;
        return $this;
    }

    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    public function template($template)
    {
        $this->template = $template;
        return $this;
    }

    public function extra($values, $value = true)
    {
        if (is_array($values)) {
            $this->extra = array_merge($this->extra, $values);
        } else {
            $this->extra[$values] = $value;
        }
        return $this;
    }

    public function options($options)
    {
        $this->options = $options;
        return $this;
    }

    public function from($table, $text, $id = 'id', $query = null)
    {
        $this->table = $table;
        $this->tableText = $text;
        $this->tableId = $id;
        $this->query = $query;

        return $this;
    }
    
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

    public function render()
    {
        if ($this->included) {
            return $this->fieldBuilder->render($this);
        }
    }

    public function toHtml()
    {
        return $this->render();
    }

    public function getValidationRules()
    {
        $rules = [];

        if ($this->hasAttribute('required')) {
            $rules[] = 'required';
        }

        if (in_array($this->getType(), ['email', 'url'])) {
            $rules[] = $this->getType();
        }

        if (! empty ($this->options)) {
            $rules[] = Rule::in(array_keys($this->options));

            if (! in_array('required', $rules)) {
                $rules[] = 'nullable';
            }
        }

        if ($this->table) {
            $rules[] = Rule::exists($this->table, $this->tableId)->where($this->query);
        }

        return $rules;
    }
}
