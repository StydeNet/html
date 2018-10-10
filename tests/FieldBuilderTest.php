<?php

namespace Styde\Html\Tests;

use Styde\Html\Facades\Field;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;

class FieldBuilderTest extends TestCase
{
    /** @test */
    function it_generates_a_text_field()
    {
        $this->assertTemplateMatches(
            'field/text', Field::text('name', 'value')
        );
    }

    /** @test */
    function it_generates_a_required_text_field()
    {
        $this->assertTemplateMatches(
            'field/text-required', Field::text('name', ['required' => true])
        );
    }

    /** @test */
    function it_generates_a_required_password_field()
    {
        $this->assertTemplateMatches(
            'field/password-required', Field::password('password')->required()
        );
    }

    /** @test */
    public function it_generates_a_text_field_with_a_custom_label()
    {
        $this->assertTemplateMatches(
            'field/text-custom-label', Field::text('name', 'value', ['label' => 'Full name'])
        );
    }

    /** @test */
    public function it_generates_a_select_field()
    {
         trans()->addLines([
             'validation.empty_option.default' => 'Select value',
         ], 'en');

        $this->assertTemplateMatches(
            'field/select', Field::select('gender', ['m' => 'Male', 'f' => 'Female'])
        );
    }

    /** @test */
    function it_adds_an_empty_option_to_select_fields()
    {
        $this->assertTemplateMatches(
            'field/select-empty', Field::select('gender', ['m' => 'Male', 'f' => 'Female'], ['empty' => 'Select gender'])
        );
    }

    /** @test */
    function it_generates_a_multiple_select_field()
    {
        $options = [
            'php'     => 'PHP',
            'laravel' => 'Laravel',
            'symfony' => 'Symfony',
            'ruby'    => 'Ruby on Rails'
        ];

        $this->assertTemplateMatches(
            'field/select-multiple', Field::select('tags', $options, ['php', 'laravel'], ['multiple'])
        );

        $this->assertTemplateMatches(
            'field/select-multiple', Field::selectMultiple('tags', $options, ['php', 'laravel'])
        );
    }

    /** @test */
    function it_generates_a_multiple_select_field_with_optgroup()
    {
        $options = [
            'backend' => [
                'laravel' => 'Laravel',
                'rails' => 'Ruby on Rails',
            ],
            'frontend' => [
                'vue' => 'Vue',
                'angular' => 'Angular',
            ],
        ];

        $this->assertTemplateMatches(
            'field/select-group', Field::selectMultiple('frameworks', $options, ['vue', 'laravel'])
        );
    }

    /** @test */
    function it_generates_a_text_field_with_errors()
    {
        tap(app('session.store'), function ($session) {
            $session->put('errors', new MessageBag([
                'name' => ['This is really wrong']
            ]));

            Field::setSessionStore($session);
        });

        $this->assertTemplateMatches(
            'field/text_with_errors', Field::text('name')
        );
    }

    /** @test */
    function it_generates_checkboxes()
    {
        $tags = [
            'php' => 'PHP',
            'python' => 'Python',
            'js' => 'JS',
            'ruby' => 'Ruby on Rails'
        ];
        $checked = ['php', 'js'];

        $this->assertTemplateMatches(
            'field/checkboxes', Field::checkboxes('tags', $tags, $checked)
        );
    }

    /** @test */
    function it_generate_radios()
    {
        $this->assertTemplateMatches(
            'field/radios', Field::radios('gender', ['m' => 'Male', 'f' => 'Female'], 'm')
        );
    }

    /** @test */
    function it_only_renders_the_field_if_the_user_has_the_expected_role()
    {
        $field = Field::text('name');

        $this->assertNull($field->ifIs('admin')->render());

        $this->actingAs($this->aUser());
        $this->assertNull($field->ifIs('admin')->render());

        $this->actingAs($this->anAdmin());
        $this->assertNotNull($field->ifIs('admin')->render());
    }

    /** @test */
    function it_only_renders_the_field_if_the_user_is_not_guest()
    {
        $field = Field::text('name');

        $this->assertNotNull($field->ifGuest()->render());

        $this->actingAs($this->aUser());
        $this->assertNull($field->ifGuest()->render());
    }

    /** @test */
    function if_only_renders_the_field_if_user_is_logged_in()
    {
        $field = Field::text('name');

        $this->assertNull($field->ifAuth()->render());

        $this->actingAs($this->aUser());
        $this->assertNotNull($field->ifAuth()->render());
    }

    /** @test */
    function it_only_renders_the_field_if_the_user_has_the_given_ability()
    {
        $this->actingAs($this->aUser());

        Gate::define('edit-all', function ($user) {
            return false;
        });

        Gate::define('edit-mine', function ($user) {
            return true;
        });

        $field = Field::text('name');

        $this->assertNull($field->ifCan('edit-all')->render());

        $this->assertNotNull($field->ifCan('edit-mine')->render());
    } 

    /** @test */
    function it_only_renders_the_field_if_the_user_does_not_have_the_given_ability()
    {
        $this->actingAs($this->aUser());

        Gate::define('edit-all', function ($user) {
            return false;
        });

        Gate::define('edit-mine', function ($user) {
            return true;
        });

        $field = Field::text('name');

        $this->assertNotNull($field->ifCannot('edit-all')->render());

        $this->assertNull($field->ifCannot('edit-mine')->render());
    }

    /** @test */
    function it_can_customize_the_template()
    {
        View::addLocation(__DIR__.'/views');

        $field = Field::text('name', 'value')->template('custom-templates.field-text');

        $this->assertTemplateMatches('field/text-custom-template', $field);
    }
}