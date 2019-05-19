<?php

namespace Styde\Html\FormModel;

use Illuminate\Validation\Rule;
use Styde\Html\Rules\Unique;

trait IncludeRulesHelpers
{
    /**
     * @return mixed
     */
    public function accepted()
    {
        return $this->addRule('accepted');
    }

    /**
     * @return mixed
     */
    public function activeUrl()
    {
        return $this->addRule('active_url');
    }

    /**
     * @param string $date
     * @return mixed
     */
    public function after(string $date)
    {
        return $this->addRule("after:$date");
    }

    /**
     * @param string $date
     * @return mixed
     */
    public function afterOrEqual(string $date)
    {
        return $this->addRule("after_or_equal:$date");
    }

    /**
     * @return mixed
     */
    public function alpha()
    {
        return $this->addRule('alpha');
    }

    /**
     * @return mixed
     */
    public function alphaDash()
    {
        return $this->addRule('alpha_dash');
    }

    /**
     * @return mixed
     */
    public function alphaNum()
    {
        return $this->addRule('alpha_num');
    }

    /**
     * @return mixed
     */
    public function array()
    {
        return $this->addRule('array');
    }

    /**
     * @return mixed
     */
    public function bail()
    {
        return $this->addRule('bail');
    }

    /**
     * @param string $date
     * @return mixed
     */
    public function before(string $date)
    {
        return $this->addRule("before:$date");
    }

    /**
     * @param string $date
     * @return mixed
     */
    public function beforeOrEqual(string $date)
    {
        return $this->addRule("before_or_equal:$date");
    }

    /**
     * @param int $min
     * @param int $max
     * @return mixed
     */
    public function between(int $min, int $max)
    {
        return $this->addRule("between:$min,$max");
    }

    /**
     * @return mixed
     */
    public function boolean()
    {
        return $this->addRule('boolean');
    }

    /**
     * @return mixed
     */
    public function confirmed()
    {
        return $this->addRule('confirmed');
    }

    /**
     * @return mixed
     */
    public function date()
    {
        return $this->addRule('date');
    }

    /**
     * @param string $date
     * @return mixed
     */
    public function dateEquals(string $date)
    {
        return $this->addRule("date_equals:$date");
    }

    /**
     * @param string $format
     * @return mixed
     */
    public function dateFormat(string $format)
    {
        return $this->addRule("date_format:$format");
    }

    /**
     * @param string $field
     * @return mixed
     */
    public function different(string $field)
    {
        return $this->addRule("different:$field");
    }

    /**
     * @param int $value
     * @return mixed
     */
    public function digits(int $value)
    {
        return $this->addRule("digits:$value");
    }

    /**
     * @param int $min
     * @param int $max
     * @return mixed
     */
    public function digitsBetween(int $min, int $max)
    {
        return $this->addRule("digits_between:$min,$max");
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

        return $this->addRule(rtrim($rule, ','));
    }

    /**
     * @return mixed
     */
    public function distinct()
    {
        return $this->addRule('distinct');
    }

    /**
     * @return mixed
     */
    public function email()
    {
        return $this->addRule('email');
    }

    /**
     * @param string $table
     * @param string|null $column
     * @return mixed
     */
    public function exists(string $table, string $column = null)
    {
        if ($column) {
            return $this->addRule("exists:$table,$column");
        }

        return $this->addRule("exists:$table");
    }

    /**
     * @return mixed
     */
    public function file()
    {
        return $this->addRule('file');
    }

    /**
     * @return mixed
     */
    public function filled()
    {
        return $this->addRule('filled');
    }

    /**
     * @param string $field
     * @return mixed
     */
    public function gt(string $field)
    {
        return $this->addRule("gt:$field");
    }

    /**
     * @param string $field
     * @return mixed
     */
    public function gte(string $field)
    {
        return $this->addRule("gte:$field");
    }

    /**
     * @return mixed
     */
    public function image()
    {
        return $this->addRule('image');
    }

    /**
     * @param mixed ...$values
     * @return mixed
     */
    public function in(...$values)
    {
        if (isset($values[0]) && is_array($values[0])) {
            return $this->addRule(Rule::in($values[0]));
        }

        $fields = implode(',', $values);

        return $this->addRule("in:$fields");
    }

    /**
     * @param string $field
     * @return mixed
     */
    public function inArray(string $field)
    {
        return $this->addRule("in_array:$field");
    }

    /**
     * @return mixed
     */
    public function integer()
    {
        return $this->addRule('integer');
    }

    /**
     * @return mixed
     */
    public function ip()
    {
        return $this->addRule('ip');
    }

    /**
     * @return mixed
     */
    public function ipv4()
    {
        return $this->addRule('ipv4');
    }

    /**
     * @return mixed
     */
    public function ipv6()
    {
        return $this->addRule('ipv6');
    }

    /**
     * @return mixed
     */
    public function json()
    {
        return $this->addRule('json');
    }

    /**
     * @param string $field
     * @return mixed
     */
    public function lt(string $field)
    {
        return $this->addRule("lt:$field");
    }

    /**
     * @param string $field
     * @return mixed
     */
    public function lte(string $field)
    {
        return $this->addRule("lte:$field");
    }

    /**
     * @param int $value
     * @return mixed
     */
    public function max(int $value)
    {
        $this->setAttribute('max', $value);

        return $this->addRule("max:$value");
    }

    /**
     * @param int $value
     * @return mixed
     */
    public function maxlength(int $value)
    {
        $this->setAttribute('maxlength', $value);

        return $this->addRule("max:$value");
    }

    /**
     * @param mixed ...$values
     * @return mixed
     */
    public function mimetypes(...$values)
    {
        $extensions = implode(',', $values);

        return $this->addRule("mimetypes:$extensions");
    }

    /**
     * @param mixed ...$values
     * @return mixed
     */
    public function mimes(...$values)
    {
        $extensions = implode(',', $values);

        return $this->addRule("mimes:$extensions");
    }

    /**
     * @param $value
     * @return Field
     */
    public function min(int $value)
    {
        $this->setAttribute('min', $value);

        return $this->addRule("min:$value");
    }

    /**
     * @param $value
     * @return Field
     */
    public function minlength(int $value)
    {
        $this->setAttribute('minlength', $value);

        return $this->addRule("min:$value");
    }

    /**
     * @param mixed ...$values
     * @return mixed
     */
    public function notIn(...$values)
    {
        if (isset($values[0]) && is_array($values[0])) {
            return $this->addRule(Rule::notIn($values[0]));
        }

        $fields = implode(',', $values);

        return $this->addRule("not_in:$fields");
    }

    /**
     * @param $value
     * @return mixed
     */
    public function notRegex($value)
    {
        return $this->addRule("not_regex:/$value/");
    }

    /**
     * @return Field
     */
    public function nullable()
    {
        $this->withoutRules('required');

        return $this->addRule('nullable');
    }

    /**
     * @return mixed
     */
    public function numeric()
    {
        return $this->addRule('numeric');
    }

    /**
     * @param $value
     * @return Field
     */
    public function pattern($value)
    {
        $this->setAttribute('pattern', $value);

        return $this->regex($value);
    }

    /**
     * @return mixed
     */
    public function present()
    {
        return $this->addRule('present');
    }

    /**
     * @param $value
     * @return mixed
     */
    public function regex($value)
    {
        return $this->addRule("regex:/$value/");
    }

    /**
     * @return Field
     */
    public function required()
    {
        $this->setAttribute('required');

        $this->withoutRules('nullable');

        return $this->addRule('required');
    }

    /**
     * @param $column
     * @param $value
     * @return Field
     */
    public function requiredIf($column, $value)
    {
        return $this->addRule("required_if:$column,$value");
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
            return $this->addRule("required_unless:$column,$operator");
        }

        return $this->addRule("required_unless:$column,$operator,$value");
    }

    /**
     * @param array $values
     * @return Field
     */
    public function requiredWith(...$values)
    {
        $value = implode(',', $values);

        return $this->addRule("required_with:$value");
    }

    /**
     * @param array $values
     * @return Field
     */
    public function requiredWithAll(...$values)
    {
        $value = implode(',', $values);

        return $this->addRule("required_with_all:$value");
    }

    /**
     * @param array $values
     * @return Field
     */
    public function requiredWithout(...$values)
    {
        $value = implode(',', $values);

        return $this->addRule("required_without:$value");
    }

    /**
     * @param array $values
     * @return Field
     */
    public function requiredWithoutAll(...$values)
    {
        $value = implode(',', $values);

        return $this->addRule("required_without_all:$value");
    }

    /**
     * @param $field
     * @return mixed
     */
    public function same($field)
    {
        return $this->addRule("same:$field");
    }

    /**
     * @param $value
     * @return Field
     */
    public function size($value)
    {
        $this->setAttribute('size', $value);

        return $this->addRule("size:$value");
    }

    /**
     * @return mixed
     */
    public function string()
    {
        return $this->addRule('string');
    }

    /**
     * @return mixed
     */
    public function timezone()
    {
        return $this->addRule('timezone');
    }

    /**
     * @param string $table
     * @param string $column
     * @return \Styde\Html\Rules\Unique
     */
    public function unique($table, $column = 'NULL')
    {
        $rule = new Unique($table, $column, $this);

        $this->addRule($rule);

        return $rule;
    }

    /**
     * @param $id
     * @param null $idColumn
     * @return mixed
     * @throws \Exception
     */
    public function ignore($id, $idColumn = null)
    {
        foreach ($this->getValidationRules() as $key => $rule) {
            if (strpos($rule, 'unique:') !== false) {
                $data = str_replace('unique:', '', $rule);
                $this->withoutRules([$rule]);
            }
        }

        if (! isset($data)) {
            throw new \Exception('You need use the unique method before ignore.');
        }

        $data = explode(',', $data);

        $table = $data[0] ? $data[0] : 'NULL';
        $column = array_key_exists(1, $data) ? $data[1] : 'NULL';
        $idColumn = $idColumn ? $idColumn : 'id';

        return $this->addRule("unique:$table,$column,\"$id\",$idColumn");
    }

    /**
     * @return mixed
     */
    public function url()
    {
        return $this->addRule('url');
    }
}
