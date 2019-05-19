<?php

namespace Styde\Html\Tests;

use Styde\Html\Facades\Field;

class FieldRuleTest extends TestCase
{
    /** @test */
    function add_a_custom_rule()
    {
        $field = Field::text('name')
            ->withRule($rule = new MyCustomRule);

        $this->assertSame([$rule], $field->getValidationRules());
    }

    /** @test */
    function can_remove_a_rule_when_field_has_a_custom_rule()
    {
        $field = Field::text('name')
            ->required()
            ->withRule($rule = new MyCustomRule)
            ->withoutRules('required');

        $this->assertSame([$rule], $field->getValidationRules());
    }
    
    /** @test */
    function it_disables_all_rules()
    {
        $field = Field::number('code')
            ->max(10)
            ->required()
            ->withRule(new MyCustomRule)
            ->withoutRules();

        $this->assertSame([], $field->getValidationRules());
    }

    /** @test */
    function it_deletes_specific_rules()
    {
        $field = Field::email('email')->min(10)->required()->withoutRules('required', 'min');
        $this->assertSame(['email'], $field->getValidationRules());

        $field = Field::email('email')->min(10)->required()->withoutRules(['min', 'required']);
        $this->assertSame(['email'], $field->getValidationRules());
    }
}

class MyCustomRule implements \Illuminate\Contracts\Validation\Rule
{
    public function passes($attribute, $value)
    {
        return true;
    }

    public function message()
    {
    }
}
