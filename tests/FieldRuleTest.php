<?php

namespace Styde\Html\Tests;

use Styde\Html\Facades\Field;

class FieldRuleTest extends TestCase
{
    /** @test */
    function add_a_custom_rule()
    {
        $builder = Field::text('name')->withRule($rule = new MyCustomRule);

        $this->assertSame([$rule], $builder->getField()->getValidationRules());
    }

    /** @test */
    function can_remove_a_rule_when_field_has_a_custom_rule()
    {
        $builder = Field::text('name')
            ->required()
            ->withRule($rule = new MyCustomRule)
            ->withoutRules('required');

        $this->assertSame([$rule], $builder->getField()->getValidationRules());
    }
    
    /** @test */
    function it_removes_all_the_rules()
    {
        $builder = Field::number('code')
            ->max(10)
            ->required()
            ->withRule(new MyCustomRule)
            ->withoutRules();

        $this->assertSame([], $builder->getField()->getValidationRules());
    }

    /** @test */
    function it_removes_specific_rules()
    {
        $builder = Field::email('email')->min(10)->required()->withoutRules('required', 'min');
        $this->assertSame(['email'], $builder->getField()->getValidationRules());

        $builder = Field::email('email')->min(10)->required()->withoutRules(['min', 'required']);
        $this->assertSame(['email'], $builder->getField()->getValidationRules());
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
