<?php

namespace Styde\Html\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Styde\Html\Facades\Field;
use Styde\Html\Facades\Form;

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
    function it_generates_a_not_required_text_field()
    {
        $this->assertTemplateMatches(
            'field/text-not-required', Field::text('name', ['required' => false])
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
    public function it_generates_a_text_field_with_a_custom_id()
    {
        $this->assertTemplateMatches(
            'field/text-custom-id', Field::text('name', 'value', ['id' => 'custom_id'])
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
    public function it_generates_a_select_field_with_custom_trans_empty()
    {
         trans()->addLines([
             'validation.empty_option.gender' => 'Select gender',
         ], 'en');

        $this->assertTemplateMatches(
            'field/select-empty', Field::select('gender', ['m' => 'Male', 'f' => 'Female'])
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
    function it_generates_an_input_field_with_label()
    {
        $this->assertTemplateMatches(
            'field/input', Field::input('text', 'profession', 'developer')
        );
    }

    /** @test */
    function it_generates_an_email_field()
    {
        $this->assertTemplateMatches(
            'field/email', Field::email('e-mail', 'clemir@styde.net')
        );
    }

    /** @test */
    function it_generates_an_url_field()
    {
        $this->assertTemplateMatches(
            'field/url', Field::url('site', 'https://styde.net')
        );
    }

    /** @test */
    function it_generates_a_file_field()
    {
        $this->assertTemplateMatches(
            'field/file', Field::file('myFile')
        );
    }

    /** @test */
    function it_generates_a_textarea_field()
    {
        $this->assertTemplateMatches(
            'field/textarea', Field::textarea('address', '742 Evergreen Terrace', ['rows' => 2, 'cols' => 20])
        );
    }

    /** @test */
    function it_generates_an_hidden_field()
    {
        $this->assertTemplateMatches(
            'field/hidden', Field::hidden('_token', 'some-random-token')
        );
    }

    /** @test */
    function it_generates_a_checkbox_field()
    {
        $this->assertTemplateMatches(
            'field/checkbox', Field::checkbox('remember_me')
        );
    }

    /** @test */
    function it_can_customize_the_template_by_method()
    {
        View::addLocation(__DIR__.'/views');

        $this->assertTemplateMatches(
            'field/text-custom-template',
            Field::text('name', 'value')->template('custom-templates.field-text')
        );
    }

    /** @test */
    function it_can_customize_the_template_by_attributes_array()
    {
        View::addLocation(__DIR__.'/views');

        $this->assertTemplateMatches(
            'field/text-custom-template',
            Field::text('name', 'value', ['template' => 'custom-templates.field-text'])
        );
    }

    /** @test */
    function it_generates_a_field_with_dots_name()
    {
        $this->assertTemplateMatches(
            'field/text-dots-name',
            Field::text('student.name')
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

    /** @test */
    function it_adds_a_select_field_with_options_from_model()
    {
        $post = new class extends Model {
            public function getCategoryOptions()
            {
                return ['general' => 'General', 'random' => 'Random'];
            }
        };

        Form::setCurrentModel($post);

        $this->assertTemplateMatches(
            'field/select-from-model', Field::select('category', [], null, ['empty' => 'Select category'])
        );
    }

    /** @test */
    function it_adds_a_select_field_with_options_in_wrong_name_method()
    {
        $post = new class extends Model {
            public function getUserIdOptions()
            {
                return ['1' => 'John', '2' => 'Mary'];
            }
        };

        Form::setCurrentModel($post);

        $this->assertTemplateMatches(
            'field/select-without-options-from-model', Field::select('user', [], null, ['empty' => 'Select user'])
        );
    }

    /** @test */
    function it_adds_a_select_field_from_model_without_options()
    {
        $this->assertTemplateMatches(
            'field/select-without-options-from-model', Field::select('user', [], null, ['empty' => 'Select user'])
        );
    }

    /** @test */
    function it_is_macroable()
    {
        Field::macro('myCustomField', function () {
            return 'my-custom-field';
        });

        $this->assertSame('my-custom-field', Field::myCustomField());
    }
}
