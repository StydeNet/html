<?php

namespace Styde\Html\Tests;

class FormBuilderTest extends TestCase
{
    /**
     * @var \Styde\Html\FormBuilder
     */
    protected $formBuilder;

    function setUp()
    {
        parent::setUp();

        $this->formBuilder = $this->newFormBuilder();
    }

    /** @test */
    function it_adds_the_novalidate_attribute_to_all_forms()
    {
        $this->formBuilder->novalidate(true);

        $this->assertHtmlEquals(
            '<form method="GET" novalidate>', $this->formBuilder->open(['method' => 'GET'])
        );
    }

    /** @test */
    function it_generates_time_inputs()
    {
        $this->assertHtmlEquals(
            '<input type="time" name="time">', $this->formBuilder->time('time')
        );
    }

    /** @test */
    function it_generate_radios()
    {
        $this->assertTemplateMatches(
            'form/radios', $this->formBuilder->radios('gender', ['m' => 'Male', 'f' => 'Female'], 'm')
        );
    }

    /** @test */
    function it_generate_checkboxes()
    {
        $tags = ['php' => 'PHP', 'python' => 'Python', 'js' => 'JS', 'ruby' => 'Ruby on Rails'];
        $checked = ['php', 'js'];

        $this->assertTemplateMatches(
            'form/checkboxes', $this->formBuilder->checkboxes('tags', $tags, $checked)
        );
    }
}