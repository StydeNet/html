<?php

namespace Styde\Html\Tests;

use Styde\Html\Facades\Field;
use Illuminate\Validation\Rules\Exists;

class FieldAttributesValidationTest extends TestCase
{
    /** @test */
    function the_required_attribute_generates_the_required_rule()
    {
        $field = Field::text('name', ['required' => true]);

        $this->assertSame(['required'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_nullable_rule_when_the_required_attribute_is_not_present()
    {
        $field = Field::text('name');

        $this->assertSame(['nullable'], $field->getValidationRules());
    }

    /** @test */
    function it_returns_the_email_rule_for_email_fields()
    {
        $field = Field::email('email', ['required' => true]);

        $this->assertEquals(['required', 'email'], $field->getValidationRules());
    }

    /** @test */
    function it_returns_the_url_rule_when_the_type_of_the_field_is_url()
    {
        $field = Field::url('link', ['required' => true]);

        $this->assertEquals(['required', 'url'], $field->getValidationRules());
    }

    /** @test */
    function it_returns_multiple_rules()
    {
        $field = Field::email('email', ['required']);

        $this->assertEquals(['required', 'email'], $field->getValidationRules());
    }

    /** @test */
    function it_returns_multiple_options_in_the_rules_with_options_method_in_select_field()
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
    function it_select_field_have_rule_exists()
    {
        $field = Field::select('parent_id')
            ->from('table_name', 'label', 'id', function ($query) {
                $query->whereNull('parent_id')
                    ->orderBy('label', 'ASC');
            })
            ->label('Parent');

        $this->assertSame('exists:table_name,id', (string) $field->getValidationRules()[1]);
        $this->assertInstanceOf(Exists::class, $field->getValidationRules()[1]);
    }
}