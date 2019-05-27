<?php

namespace Styde\Html\Fields;

trait ValidationRules
{
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
     * Adds a new rule to the field.
     *
     * @param $rule
     * @return $this
     */
    public function withRule($rule)
    {
        $this->field->addRule($rule);

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
            $this->field->addRule($rule);
        }

        return $this;
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
            $this->field->addRule($this->fieldsWithRules[$type]);
        }
    }

    /**
     * Add all rules of attributes
     */
    protected function addRulesOfAttributes()
    {
        foreach ($this->field->attributes as $key => $value) {
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
        $this->field->removeRule($rule);

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
            $this->field->removeAllRules();
            return $this;
        }

        if (! is_array($rules)) {
            $rules = func_get_args();
        }

        foreach ($rules as $rule) {
            $this->field->removeRule($rule);
        }

        return $this;
    }
}
