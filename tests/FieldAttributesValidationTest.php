<?php

namespace Styde\Html\Tests;

use Styde\Html\Facades\Field;
use Illuminate\Validation\Rules\Exists;

class FieldAttributesValidationTest extends TestCase
{
    /** @test */
    function the_required_attribute_generates_the_required_rule()
    {
        $field = Field::text('name', ['required']);

        $this->assertSame(['required'], $field->getValidationRules());
    }

    /** @test */
    function it_returns_the_email_rule_for_email_fields()
    {
        $field = Field::email('email', ['required' => true]);

        $this->assertEquals(['email', 'required'], $field->getValidationRules());
    }

    /** @test */
    function it_returns_the_url_rule_when_the_type_of_the_field_is_url()
    {
        $field = Field::url('link', ['required' => true]);

        $this->assertEquals(['url', 'required'], $field->getValidationRules());
    }

    /** @test */
    function it_returns_multiple_rules()
    {
        $field = Field::email('email', ['required']);

        $this->assertEquals(['email', 'required'], $field->getValidationRules());
    }

    /** @test */
    function it_builds_the_in_rule_when_the_field_includes_static_options()
    {
        $field = Field::select('visibility', null, ['required'])->options([
            'public' => 'Everyone',
            'admin' => 'Admin only',
            'auth' => 'Authenticated users only',
            'guest' => 'Guest users only',
        ]);

        $this->assertSame('in:"public","admin","auth","guest"', (string) $field->getValidationRules()[1]);
    }

    /** @test */
    function it_builds_the_exists_rule_when_options_come_from_a_table()
    {
        $field = Field::select('parent_id')
            ->from('table_name', 'label', 'id', function ($query) {
                $query->whereNull('parent_id')
                    ->orderBy('label', 'ASC');
            })
            ->label('Parent');

        $this->assertSame('exists:table_name,id', (string) $field->getValidationRules()[0]);
        $this->assertInstanceOf(Exists::class, $field->getValidationRules()[0]);
    }

    /** @test */
    function it_returns_the_max_rule_when_call_method_max()
    {
        $field = Field::text('name')->max(10);

        $this->assertEquals(['max:10'], $field->getValidationRules());
    }

    /** @test */
    function it_returns_the_file_rule_for_file_fields()
    {
        $field = Field::file('avatar');

        $this->assertEquals(['file'], $field->getValidationRules());
    }

    /** @test */
    function it_returns_the_date_rule_for_date_fields()
    {
        $field = Field::date('time');

        $this->assertEquals(['date'], $field->getValidationRules());
    }

    /** @test */
    function it_returns_the_numeric_rule_for_number_fields()
    {
        $field = Field::number('field');

        $this->assertEquals(['numeric'], $field->getValidationRules());
    }

    /** @test */
    function it_returns_nullable_rule_when_call_nullable_method()
    {
        $field = Field::text('name')->nullable();

        $this->assertSame(['nullable'], $field->getValidationRules());
    }

    /** @test */
    function it_returns_required_rule_when_call_required_method()
    {
        $field = Field::text('name')->required();

        $this->assertSame(['required'], $field->getValidationRules());
    }
    
    /** @test */
    function it_delete_all_rules_when_call_disabledRules_method_without_parameters()
    {
        $field = Field::number('code')->min(1)->max(10)->required()->disableRules();

        $this->assertSame([], $field->getValidationRules());
    }
    
    /** @test */
    function it_delete_specific_rule_when_call_disableRules_method_with_the_rules_to_be_deleted_as_parameters()
    {
        $field = Field::email('email')->min(10)->required()->disableRules('required', 'min');

        $this->assertSame(['email'], $field->getValidationRules());
    }
    
    /** @test */
    function it_delete_specifics_rules_when_call_disableRules_method_with_an_array_in_parameter()
    {
        $field = Field::email('email')->min(10)->required()->disableRules(['min', 'required']);

        $this->assertSame(['email'], $field->getValidationRules());
    }
    
    /** @test */
    function it_delete_required_rule_when_call_nullable_method()
    {
        $field = Field::name('first_name')->required()->nullable();

        $this->assertSame(['nullable'], $field->getValidationRules());
    }

    /** @test */
    function it_delete_nullable_rule_when_call_required_method()
    {
        $field = Field::name('first_name')->nullable()->required();

        $this->assertSame(['required'], $field->getValidationRules());
    }
}