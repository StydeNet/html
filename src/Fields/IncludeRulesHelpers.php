<?php

namespace Styde\Html\Fields;

use Illuminate\Validation\Rule;
use Styde\Html\Rules\Unique;

trait IncludeRulesHelpers
{
    /**
     * Add the accepted rule to the field.
     *
     * @return \Styde\Html\Fields\FieldBuilder
     */
    public function accepted()
    {
        $this->field->addRule('accepted');

        return $this;
    }

    /**
     * Add the active url rule to the field.
     *
     * @return \Styde\Html\Fields\FieldBuilder
     */
    public function activeUrl()
    {
        $this->field->addRule('active_url');

        return $this;
    }

    /**
     * Add the after rule to the field.
     *
     * @param string $value
     * @return \Styde\Html\Fields\FieldBuilder
     */
    public function after(string $value)
    {
        $this->field->addRule("after:{$value}");

        return $this;
    }

    /**
     * Add the after or equal rule to the field.
     *
     * @param string $value
     * @return \Styde\Html\Fields\FieldBuilder
     */
    public function afterOrEqual(string $value)
    {
        $this->field->addRule("after_or_equal:{$value}");

        return $this;
    }

    /**
     * Add the alpha rule to the field.
     *
     * @return \Styde\Html\Fields\FieldBuilder
     */
    public function alpha()
    {
        $this->field->addRule('alpha');

        return $this;
    }

    /**
     * Add the alpha dash rule to the field.
     *
     * @return \Styde\Html\Fields\FieldBuilder
     */
    public function alphaDash()
    {
        $this->field->addRule('alpha_dash');

        return $this;
    }

    /**
     * Add the alpha num rule to the field.
     *
     * @return \Styde\Html\Fields\FieldBuilder
     */
    public function alphaNum()
    {
        $this->field->addRule('alpha_num');

        return $this;
    }

    /**
     * Add the array rule to the field.
     *
     * @return \Styde\Html\Fields\FieldBuilder
     */
    public function array()
    {
        $this->field->addRule('array');

        return $this;
    }

    /**
     * @return mixed
     */
    public function bail()
    {
        $this->field->addRule('bail');

        return $this;
    }

    /**
     * @param string $date
     * @return mixed
     */
    public function before(string $date)
    {
        $this->field->addRule("before:$date");

        return $this;
    }

    /**
     * @param string $date
     * @return mixed
     */
    public function beforeOrEqual(string $date)
    {
        $this->field->addRule("before_or_equal:$date");

        return $this;
    }

    /**
     * @param int $min
     * @param int $max
     * @return mixed
     */
    public function between(int $min, int $max)
    {
        $this->field->addRule("between:$min,$max");

        return $this;
    }

    /**
     * @return mixed
     */
    public function boolean()
    {
        $this->field->addRule('boolean');

        return $this;
    }

    /**
     * @return mixed
     */
    public function confirmed()
    {
        $this->field->addRule('confirmed');

        return $this;
    }

    /**
     * @return mixed
     */
    public function date()
    {
        $this->field->addRule('date');

        return $this;
    }

    /**
     * @param string $date
     * @return mixed
     */
    public function dateEquals(string $date)
    {
        $this->field->addRule("date_equals:$date");

        return $this;
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function dateFormat(string $format)
    {
        $this->field->addRule("date_format:$format");

        return $this;
    }

    /**
     * @param string $field
     * @return mixed
     */
    public function different(string $field)
    {
        $this->field->addRule("different:$field");

        return $this;
    }

    /**
     * @param int $value
     * @return mixed
     */
    public function digits(int $value)
    {
        $this->field->addRule("digits:$value");

        return $this;
    }

    /**
     * @param int $min
     * @param int $max
     * @return mixed
     */
    public function digitsBetween(int $min, int $max)
    {
        $this->field->addRule("digits_between:$min,$max");

        return $this;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function dimensions(array $data)
    {
        $rule = 'dimensions:';

        foreach ($data as $key => $value) {
            $rule .= "$key=$value,";
        }

        $this->field->addRule(rtrim($rule, ','));

        return $this;
    }

    /**
     * @return mixed
     */
    public function distinct()
    {
        $this->field->addRule('distinct');

        return $this;
    }

    /**
     * @param null $strategies
     * @return mixed
     */
    public function email($strategies = null)
    {
        if ($strategies) {
            $this->field->addRule('email:'.implode((array) $strategies, ','));
        } else {
            $this->field->addRule('email');
        }

        return $this;
    }

    /**
     * @param string $table
     * @param string|null $column
     * @return mixed
     */
    public function exists(string $table, string $column = null)
    {
        if ($column) {
            $this->field->addRule("exists:$table,$column");
        } else {
            $this->field->addRule("exists:$table");
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function file()
    {
        $this->field->addRule('file');

        return $this;
    }

    /**
     * @return mixed
     */
    public function filled()
    {
        $this->field->addRule('filled');

        return $this;
    }

    /**
     * @param string $field
     * @return mixed
     */
    public function gt(string $field)
    {
        $this->field->addRule("gt:$field");

        return $this;
    }

    /**
     * @param string $field
     * @return mixed
     */
    public function gte(string $field)
    {
        $this->field->addRule("gte:$field");

        return $this;
    }

    /**
     * @return mixed
     */
    public function image()
    {
        $this->field->addRule('image');

        return $this;
    }

    /**
     * @param mixed ...$values
     * @return mixed
     */
    public function in(...$values)
    {
        if (isset($values[0]) && is_array($values[0])) {
            $this->field->addRule(Rule::in($values[0]));
        } else {
            $fields = implode(',', $values);

            $this->field->addRule("in:$fields");
        }

        return $this;
    }

    /**
     * @param string $field
     * @return mixed
     */
    public function inArray(string $field)
    {
        $this->field->addRule("in_array:$field");

        return $this;
    }

    /**
     * @return mixed
     */
    public function integer()
    {
        $this->field->addRule('integer');

        return $this;
    }

    /**
     * @return mixed
     */
    public function ip()
    {
        $this->field->addRule('ip');

        return $this;
    }

    /**
     * @return mixed
     */
    public function ipv4()
    {
        $this->field->addRule('ipv4');

        return $this;
    }

    /**
     * @return mixed
     */
    public function ipv6()
    {
        $this->field->addRule('ipv6');

        return $this;
    }

    /**
     * @return mixed
     */
    public function json()
    {
        $this->field->addRule('json');

        return $this;
    }

    /**
     * @param string $field
     * @return mixed
     */
    public function lt(string $field)
    {
        $this->field->addRule("lt:$field");

        return $this;
    }

    /**
     * @param string $field
     * @return mixed
     */
    public function lte(string $field)
    {
        $this->field->addRule("lte:$field");

        return $this;
    }

    /**
     * @param int $value
     * @return mixed
     */
    public function max(int $value)
    {
        $this->field->setAttribute('max', $value);
        $this->field->addRule("max:{$value}");

        return $this;
    }

    /**
     * @param int $value
     * @return mixed
     */
    public function maxlength(int $value)
    {
        $this->field->setAttribute('maxlength', $value);
        $this->field->addRule("max:{$value}");

        return $this;
    }

    /**
     * @param mixed ...$values
     * @return mixed
     */
    public function mimetypes(...$values)
    {
        $extensions = implode(',', $values);

        $this->field->addRule("mimetypes:$extensions");

        return $this;
    }

    /**
     * @param mixed ...$values
     * @return mixed
     */
    public function mimes(...$values)
    {
        $extensions = implode(',', $values);

        $this->field->addRule("mimes:$extensions");

        return $this;
    }

    /**
     * @param $value
     * @return FieldBuilder
     */
    public function min(int $value)
    {
        $this->field->setAttribute('minlength', $value);
        $this->field->addRule("min:{$value}");

        return $this;
    }

    /**
     * @param $value
     * @return FieldBuilder
     */
    public function minlength(int $value)
    {
        return $this->min($value);
    }

    /**
     * @param mixed ...$values
     * @return mixed
     */
    public function notIn(...$values)
    {
        if (isset($values[0]) && is_array($values[0])) {
            $this->field->addRule(Rule::notIn($values[0]));
        } else {
            $fields = implode(',', $values);
            $this->field->addRule("not_in:{$fields}");
        }

        return $this;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function notRegex($value)
    {
        $this->field->addRule("not_regex:/{$value}/");

        return $this;
    }

    /**
     * @return FieldBuilder
     */
    public function nullable()
    {
        $this->field->removeRule('required');
        $this->field->addRule('nullable');

        return $this;
    }

    /**
     * @return mixed
     */
    public function numeric()
    {
        $this->field->addRule('numeric');

        return $this;
    }

    /**
     * @param $value
     * @return FieldBuilder
     */
    public function pattern($value)
    {
        $this->field->setAttribute('pattern', $value);

        return $this->regex($value);
    }

    /**
     * @return mixed
     */
    public function present()
    {
        $this->field->addRule('present');

        return $this;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function regex($value)
    {
        $this->field->addRule("regex:/{$value}/");

        return $this;
    }

    /**
     * @return \Styde\Html\Fields\FieldBuilder
     */
    public function required()
    {
        $this->field->setAttribute('required');
        $this->field->removeRule('nullable');
        $this->field->addRule('required');

        return $this;
    }

    /**
     * ADd the required if rule to the field.
     *
     * @param $column
     * @param $value
     * @return FieldBuilder
     */
    public function requiredIf($column, $value)
    {
        $this->field->addRule("required_if:{$column},{$value}");

        return $this;
    }

    /**
     * Add the required unless rule to the field.
     *
     * @param $column
     * @param $operator
     * @param null $value
     * @return FieldBuilder
     */
    public function requiredUnless($column, $operator, $value = null)
    {
        if ($value) {
            $this->field->addRule("required_unless:$column,$operator,$value");
        } else {
            $this->field->addRule("required_unless:$column,$operator");
        }

        return $this;
    }

    /**
     * Add the required with rule to the field.
     *
     * @param array $values
     * @return FieldBuilder
     */
    public function requiredWith(...$values)
    {
        $value = implode(',', $values);

        $this->field->addRule("required_with:{$value}");

        return $this;
    }

    /**
     * Add the required with all rule to the field.
     *
     * @param array $values
     * @return FieldBuilder
     */
    public function requiredWithAll(...$values)
    {
        $value = implode(',', $values);

        $this->field->addRule("required_with_all:{$value}");

        return $this;
    }

    /**
     * Add the required without rule to the field.
     *
     * @param array $values
     * @return FieldBuilder
     */
    public function requiredWithout(...$values)
    {
        $value = implode(',', $values);

        $this->field->addRule("required_without:{$value}");

        return $this;
    }

    /**
     * @param array $values
     * @return FieldBuilder
     */
    public function requiredWithoutAll(...$values)
    {
        $value = implode(',', $values);

        $this->field->addRule("required_without_all:$value");

        return $this;
    }

    /**
     * Add the same rule to the field.
     *
     * @param $field
     * @return mixed
     */
    public function same($field)
    {
        $this->field->addRule("same:$field");

        return $this;
    }

    /**
     * Add the size rule to the field.
     *
     * @param $value
     * @return FieldBuilder
     */
    public function size($value)
    {
        $this->field->setAttribute('size', $value);
        $this->field->addRule("size:{$value}");

        return $this;
    }

    /**
     * Add the string rule to the field.
     *
     * @return mixed
     */
    public function string()
    {
        $this->field->addRule('string');

        return $this;
    }

    /**
     * Add the timezone rule to the field.
     *
     * @return mixed
     */
    public function timezone()
    {
        $this->field->addRule('timezone');

        return $this;
    }

    /**
     * Add the unique rule to the field.
     *
     * @param string $table
     * @param string $column
     * @return \Styde\Html\Rules\Unique
     */
    public function unique($table, $column = 'NULL')
    {
        $this->field->addRule($rule = new Unique($table, $column, $this));

        return $rule;
    }

    /**
     * Placeholder to prevent the user from calling ignore before calling unique.
     */
    public function ignore()
    {
        throw new \Exception('You need call the unique method before calling ignore.');
    }

    /**
     * @return mixed
     */
    public function url()
    {
        $this->field->addRule('url');

        return $this;
    }
}
