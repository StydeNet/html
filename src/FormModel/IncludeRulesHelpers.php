<?php

namespace Styde\Html\FormModel;

trait IncludeRulesHelpers
{
    /**
     * @return mixed
     */
    public function accepted()
    {
        return $this->setRule('accepted');
    }

    /**
     * @return mixed
     */
    public function activeUrl()
    {
        return $this->setRule('active_url');
    }

    /**
     * @param string $date
     * @return mixed
     */
    public function after(string $date)
    {
        return $this->setRule("after:$date");
    }

    /**
     * @param string $date
     * @return mixed
     */
    public function afterOrEqual(string $date)
    {
        return $this->setRule("after_or_equal:$date");
    }

    /**
     * @return mixed
     */
    public function alpha()
    {
        return $this->setRule('alpha');
    }

    /**
     * @return mixed
     */
    public function alphaDash()
    {
        return $this->setRule('alpha_dash');
    }

    /**
     * @return mixed
     */
    public function alphaNum()
    {
        return $this->setRule('alpha_num');
    }

    /**
     * @return mixed
     */
    public function array()
    {
        return $this->setRule('array');
    }

    /**
     * @return mixed
     */
    public function image()
    {
        return $this->setRule('image');
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
    public function maxlength($value)
    {
        $this->setAttribute('maxlength', $value);

        return $this->setRule("max:$value");
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
    public function minlength($value)
    {
        $this->setAttribute('minlength', $value);

        return $this->setRule("min:$value");
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
     * @param $value
     * @return Field
     */
    public function pattern($value)
    {
        $this->setAttribute('pattern', $value);

        return $this->setRule("regex:/$value/");
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
     * @param array $values
     * @return Field
     */
    public function requiredWithoutAll(...$values)
    {
        $value = implode(',', $values);

        return $this->setRule("required_without_all:$value");
    }

    /**
     * @param $field
     * @return mixed
     */
    public function same($field)
    {
        return $this->setRule("same:$field");
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
}
