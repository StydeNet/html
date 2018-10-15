<?php

namespace Styde\Html\Tests;

use Styde\Html\Facades\Field;
use Illuminate\Support\MessageBag;
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
    function it_generates_radios()
    {
        $this->assertTemplateMatches(
            'field/radios', Field::radios('gender', ['m' => 'Male', 'f' => 'Female'], 'm')
        );
    }

    /** @test */
    function it_can_customize_the_template()
    {
        View::addLocation(__DIR__.'/views');

        $this->assertTemplateMatches(
            'field/text-custom-template',
            Field::text('name', 'value')->template('custom-templates.field-text')
        );
    }
    
    /** @test */
    function it_can_add_labels_with_html()
    {
        $this->assertTemplateMatches(
            'field/text-with-raw-label',
            Field::text('name', 'value')->rawLabel('Label with <strong>HTML</strong>')
        );
    }
}
