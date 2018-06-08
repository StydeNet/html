<?php

namespace Styde\Html\FormModel;

use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;

trait ValidationRules
{
    /**
     * @var array
     */
    protected $rules = [];
    
    /**
     * @var array
     */
    protected $fieldsWithRules = [
        'email' => 'email',
        'date'  => 'date',
        'url'   => 'url',
        'file'  => 'file',
        'image' => 'image',
        'number' => 'numeric'
    ];

    public function setRule($rule) 
    {
        $this->rules[] = $rule;

        return $this;
    }

    public function getValidationRules()
    {
        $this->loadRuleIn();

        if ($this->table) {
            $this->setRule(Rule::exists($this->table, $this->tableId)->where($this->query));
        }

        return $this->rules;
    }

    protected function loadRuleIn()
    {
        return empty($this->options) ?: $this->setRule(Rule::in(array_keys($this->options)));
    }

    protected function addRuleByFieldType($type)
    {
        if (array_key_exists($type, $this->fieldsWithRules)) {
            $this->setRule($this->fieldsWithRules[$type]);
        }
    }

    protected function addRulesOfAttributes()
    {
        foreach ($this->attributes as $key => $value) {
            if (method_exists($this, $value)) {
                $this->$value(); continue;
            }

            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }
    }

    public function addRule($value)
    {
        $this->rules[] = $value;

        return $this;
    }

    public function disableRules(...$rules)
    {
        foreach ($this->rules as $key => $rule) {
            if ($pos = strpos($rule, ':')) {
                if (in_array(substr($rule, 0, $pos), $rules)) {
                    unset($this->rules[$key]);
                }
            }
        }

        $this->rules = empty($rules) ? [] : array_diff($this->rules, $rules);

        return $this;
    }

    public function min($value)
    {
        return $this->setRule("min:$value");
    }

    public function max($value)
    {
        return $this->setRule("max:$value");
    }

    public function size($value)
    {
        return $this->setRule("size:$value");
    }

    public function required()
    {
        $this->disableRules('nullable');

        return $this->setRule('required');
    }

    public function nullable()
    {
        return $this->setRule('nullable');
    }
}