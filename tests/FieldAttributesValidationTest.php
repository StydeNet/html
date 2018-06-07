<?php

namespace Styde\Html\Tests;

use Styde\Html\Facades\Field;
use Illuminate\Validation\Rules\Exists;

class FieldAttributesValidationTest extends TestCase
{
    /** @test */
    function it_validate_that_the_required_attribute_generates_the_required_rule()
    {
        $field = Field::text('name', ['required' => true]);

        $this->assertSame(['required'], $field->getValidationRules());
    }

    /** @test */
    function it_not_generate_the_required_rule_if_the_attribute_is_not_present()
    {
        $field = Field::text('name');

        $this->assertNotSame(['required'], $field->getValidationRules());
    }

    /** @test */
    function it_validate_the_existence_of_the_nullable_rule()
    {
        $field = Field::text('name');

        $this->assertSame(['nullable'], $field->getValidationRules());
    }

    /** @test */
    function it_validate_the_existence_of_the_email_rule()
    {
        $field = Field::email('email', ['required' => true]);

        $this->assertEquals(['required', 'email'], $field->getValidationRules());
    }

    /** @test */
    function it_validate_that_the_email_rule_does_not_exist()
    {
        $field = Field::text('name', ['required' => false]);

        $this->assertNotEquals(['email'], $field->getValidationRules());
    }

    /** @test */
    function it_validate_the_existence_of_the_url_rule()
    {
        $field = Field::url('link', ['required' => true]);

        $this->assertEquals(['required', 'url'], $field->getValidationRules());
    }

    /** @test */
    function it_validate_that_the_url_rule_does_not_exist()
    {
        $field = Field::text('name', ['required' => true]);

        $this->assertNotEquals(['required', 'url'], $field->getValidationRules());
    }

    /** @test */
    function it_there_are_multiples_rules()
    {
        $field = Field::email('email', ['required']);

        $this->assertEquals(['required', 'email'], $field->getValidationRules());
    }

    /** @test */
    function it_select_field_has_the_required_rule_and_options()
    {
        $field = Field::select('visibility', null, ['required'])->options([
            'public' => 'Everyone',
            'admin' => 'Admin only',
            'auth' => 'Authenticated users only',
            'guest' => 'Guest users only',
        ]);

        $this->assertContains('required', $field->getValidationRules());

        $this->assertSame('in:"public","admin","auth","guest"', (string) $field->getValidationRules()[1]);
    }

    /** @test */
    function it_select_field_no_have_rule_if_not_specific_option()
    {
        $field = Field::select('visibility')->options([
            'public' => 'Everyone',
            'admin' => 'Admin only',
            'auth' => 'Authenticated users only',
            'guest' => 'Guest users only',
        ]);

        $this->assertNotSame('in:"public","admin","auth","guest","moderator"', (string) $field->getValidationRules()[0]);
    }

    /** @test */
    function it_select_field_have_rule_exists()
    {
        $field = Field::select('parent_id')
            ->from('menu_items', 'label', 'id', function ($query) {
                $query->whereNull('parent_id')
                    ->orderBy('label', 'ASC');
            })
            ->label('Parent');

        $this->assertInstanceOf(Exists::class, $field->getValidationRules()[1]);
    }
}