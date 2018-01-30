<?php

namespace Styde\Html\Tests;

use Styde\Html\Facades\Form;

class FormBuilderTest extends TestCase
{
    /** @test */
    function it_adds_the_novalidate_attribute_to_all_forms()
    {
        Form::novalidate(true);

        $this->assertHtmlEquals(
            '<form method="GET" novalidate>', Form::open(['method' => 'GET'])
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
}