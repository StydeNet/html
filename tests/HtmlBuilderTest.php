<?php

namespace Styde\Html\Tests;

use Styde\Html\HtmlElement;

class HtmlBuilderTest extends TestCase
{
    /**
     * @var \Styde\Html\HtmlBuilder
     */
    var $htmlBuilder;

    function setUp()
    {
        parent::setUp();

        $this->htmlBuilder = $this->newHtmlBuilder();
    }

    /** @test */
    function it_generates_html_tags()
    {
        $this->assertEquals(
            '<span id="my-span">This is a span</span>',
            $this->htmlBuilder->tag('span', 'This is a span', ['id' => 'my-span'])->render()
        );

        $this->assertEquals(
            '<input type="text" readonly>',
            $this->htmlBuilder->tag('input', false, ['type' => 'text', 'readonly'])->render()
        );
    }

    /** @test */
    function it_closes_html_tags()
    {
        $htmlElement = new HtmlElement('span');

        $this->assertEquals('</span>', (string) $htmlElement->close());
    }

    /** @test */
    function it_escapes_the_attributes_of_generated_tags()
    {
        $this->assertEquals(
            '<span id="&lt;my-span&gt;">Span</span>',
            $this->htmlBuilder->tag('span', 'Span', ['id' => '<my-span>'])->render()
        );
    }

    /** @test */
    function it_generates_html_tags_with_dynamic_methods()
    {
        $this->assertEquals(
            '<span id="my-span">This is a span</span>',
            $this->htmlBuilder->span('This is a span')->id('my-span')
        );
    }

    /** @test */
    function it_generate_the_html_class_attribute()
    {
        $html = $this->htmlBuilder->classes([
            'home' => true,
            'main',
            'dont-use-this' => false,
        ]);

        $this->assertEquals(' class="home main"', $html);
    }
}