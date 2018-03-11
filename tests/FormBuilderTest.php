<?php

namespace Styde\Html\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\HtmlString;
use Styde\Html\Facades\Form;

class FormBuilderTest extends TestCase
{
    /** @test */
    function it_opens_a_form()
    {
        $this->assertHtmlEquals(
            '<form method="get">', Form::open()
        );
    }

    /** @test */
    function it_opens_a_post_form()
    {
        $expected =
            '<form method="post">'.
                '<input type="hidden" name="_token">';

        $this->assertHtmlEquals($expected, Form::post()->open());
    }

    /** @test */
    function it_opens_a_put_form()
    {
        $expected =
            '<form method="post">'.
                '<input type="hidden" name="_token">'.
                '<input type="hidden" name="_method" value="put">';

        $this->assertHtmlEquals($expected, Form::put()->open());
    }

    /** @test */
    function it_opens_a_delete_form()
    {
        $expected =
            '<form method="post">'.
                '<input type="hidden" name="_token">'.
                '<input type="hidden" name="_method" value="delete">';

        $this->assertHtmlEquals($expected, Form::delete()->open());
    }

    /** @test */
    function it_renders_forms()
    {
        $this->assertTemplateMatches(
            'form/get-method', Form::get()->render()
        );
    }
    
    /** @test */
    function it_can_accept_files()
    {
        $expected =
            '<form method="post" enctype="multipart/form-data">'
                .'<input type="hidden" name="_token">';

        $this->assertHtmlEquals($expected, Form::post()->withFiles()->open());
    }
    
    /** @test */
    function it_assign_a_route_to_the_action_attribute()
    {
        Route::post('the-url/{param1}/{param2}', ['as' => 'the-route']);

        $this->assertTemplateMatches(
            'form/route', Form::post()->route('the-route', ['with', 'parameters'])
        );
    }

    /** @test */
    function it_renders_a_csrf_token_field_with_post_forms()
    {
        Session::put('_token', 'random_token_here');

        $this->assertTemplateMatches(
            'form/csrf-field', Form::post()->render()
        );
    }

    /** @test */
    function it_renders_a_method_field_with_put_forms()
    {
        Session::put('_token', 'random_token_here');

        $this->assertTemplateMatches(
            'form/put-method', Form::put()->render()
        );
    }
    
    /** @test */
    function it_adds_the_novalidate_attribute_to_all_forms()
    {
        Form::novalidate(true);

        $this->assertHtmlEquals(
            '<form novalidate method="get">', Form::get()->open()
        );
    }

    /** @test */
    function it_generates_time_inputs()
    {
        $this->assertHtmlEquals(
            '<input type="time" name="time">', Form::time('time')
        );
    }

    /** @test */
    function it_generate_radios()
    {
        $this->assertTemplateMatches(
            'form/radios', Form::radios('gender', ['m' => 'Male', 'f' => 'Female'], 'm')
        );
    }

    /** @test */
    function it_generate_checkboxes()
    {
        $tags = ['php' => 'PHP', 'python' => 'Python', 'js' => 'JS', 'ruby' => 'Ruby on Rails'];
        $checked = ['php', 'js'];

        $this->assertTemplateMatches(
            'form/checkboxes', Form::checkboxes('tags', $tags, $checked)
        );
    }
    
    /** @test */
    function display_input_values_from_the_users_session()
    {
        Session::put('_old_input', ['name' => 'Duilio Palacios']);

        $this->assertHtmlEquals(
            '<input type="text" name="name" value="Duilio Palacios">', Form::text('name')
        );
    }

    /** @test */
    function display_input_values_from_the_current_model()
    {
        $user = new class extends Model {
            protected $attributes = ['name' => 'Duilio'];
        };

        Form::setCurrentModel($user);

        $this->assertHtmlEquals(
            '<input type="text" name="name" value="Duilio">', Form::text('name')
        );
    }

    /** @test */
    function display_textarea_content_from_the_current_model()
    {
        $post = new class extends Model {
            protected $attributes = ['content' => 'The content.'];
        };

        Form::setCurrentModel($post);

        $this->assertHtmlEquals(
            '<textarea name="content">The content.</textarea>', Form::textarea('content')
        );
    }

    /** @test */
    function it_is_macroable()
    {
        Form::macro('myCustomMethod', function () {
            return 'my-custom-tag';
        });

        $this->assertSame('my-custom-tag', Form::myCustomMethod());
    }
}
