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
        $this->setRuleIn();

        return array_map(function ($rule) {
            if ($rule instanceof \Illuminate\Contracts\Validation\Rule) {
                return $rule;
            }

            return (string) $rule;
        }, $this->rules);
    }

    /**
     * Adds a new rule to the field.
     *
     * @param $rule
     * @return $this
     */
    public function withRule($rule)
    {
        return $this->setRule($rule);
    }

    /**
     * Set a new rule
     *
     * @param string $rule
     * @return $this
     */
    protected function setRule($rule)
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * Set a Rule In if have values in the property options
     */
    protected function setRuleIn()
    {
        empty($this->options) ?: $this->setRule(Rule::in(array_keys($this->options)));
    }

    /**
     * Set Rule Exists if have property table
     */
    protected function setRuleExists()
    {
        (! $this->table) ?: $this->setRule(Rule::exists($this->table, $this->tableId)->where($this->query));
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
            $this->setRule($this->fieldsWithRules[$type]);
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
     * You can deactivate all rules or specify them.
     *
     * @param mixed ...$rules
     * @return $this
     */
    public function disableRules(...$rules)
    {
        // TODO: improve this logic

        if (empty ($rules)) {
            $this->rules = [];
            return $this;
        }

        $customRules = [];
        $oldRules = [];

        foreach ($this->rules as $rule) {
            if (is_object($rule)) {
                $customRules[] = $rule;
            } else {
                $oldRules[] = $rule;
            }
        }

        $rules = is_array($rules[0]) ? $rules[0] : $rules;

        foreach ($oldRules as $key => $rule) {
            if ($pos = strpos($rule, ':')) {
                if (in_array(substr($rule, 0, $pos), $rules)) {
                    unset($oldRules[$key]);
                }
            }
        }

        $this->rules = array_merge(array_diff($oldRules, $rules), $customRules);

        return $this;
    }
}
