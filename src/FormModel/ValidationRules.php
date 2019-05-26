<?php

namespace Styde\Html\FormModel;

use Illuminate\Validation\Rule;

trait ValidationRules
{
    /**
     * @var array
     */
    protected $rules = [];
    
    /**
     * Fields with rules included
     *
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

    protected $attributesWithoutRules = [
        'id'
    ];

    /**
     * Get all rules of validation
     *
     * @return array
     */
    public function getValidationRules()
    {
        return array_values(array_map(function ($rule) {
            if ($rule instanceof \Illuminate\Contracts\Validation\Rule) {
                return $rule;
            }

            return (string) $rule;
        }, $this->rules));
    }

    /**
     * Adds a new rule to the field.
     *
     * @param $rule
     * @return $this
     */
    public function withRule($rule)
    {
        return $this->addRule($rule);

        return $this;
    }

    /**
     * Add new rules to the field
     *
     * @param mixed $rules
     * @return $this
     */
    public function withRules($rules)
    {
        if (! is_array($rules)) {
            $rules = func_get_args();
        }

        foreach ($rules as $rule) {
            $this->addRule($rule);
        }

        return $this;
    }

    /**
     * Add a new rule to the field
     *
     * @param string $rule
     * @return $this
     */
    protected function addRule($rule)
    {
        if (is_object($rule)) {
            $key = get_class($rule);
        } else {
            $key = explode(':', $rule)[0];
        }

        $this->rules[$key] = $rule;

        return $this;
    }

    /**
     * Set a Rule In if have values in the property options
     */
    protected function setRuleIn()
    {
        empty($this->options) ?: $this->addRule(Rule::in(array_keys($this->options)));
    }

    /**
     * Set Rule Exists if have property table
     */
    protected function setRuleExists()
    {
        (! $this->table) ?: $this->addRule(Rule::exists($this->table, $this->tableId)->where($this->query));
    }


    /**
     * A new rule is added if the field type is
     * in the fieldsWithRules array.
     *
     * @param string $type
     */
    protected function addRuleByFieldType($type)
    {
        if (array_key_exists($type, $this->fieldsWithRules)) {
            $this->addRule($this->fieldsWithRules[$type]);
        }
    }

    /**
     * Add all rules of attributes
     */
    protected function addRulesOfAttributes()
    {
        foreach ($this->attributes as $key => $value) {
            if (method_exists($this, $value)) {
                $this->$value();
            }

            if (method_exists($this, $key) && !in_array($key, $this->attributesWithoutRules)) {
                $this->$key($value);
            }
        }
    }

    /**
     * Remove a rule from the field.
     * @param $rule
     * @return $this
     */
    public function withoutRule($rule)
    {
        $this->removeRule($rule);

        return $this;
    }

    /**
     * Remove all rules from the field or some of them.
     *
     * @param mixed $rules
     * @return $this
     */
    public function withoutRules($rules = [])
    {
        if (empty ($rules)) {
            $this->rules = [];
            return $this;
        }

        if (! is_array($rules)) {
            $rules = func_get_args();
        }

        foreach ($rules as $rule) {
            $this->removeRule($rule);
        }

        return $this;
    }

    /**
     * Remove a rule from the field.
     * @param $rule
     */
    protected function removeRule($rule)
    {
        unset ($this->rules[$rule]);
    }
}
