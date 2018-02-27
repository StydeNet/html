<?php

namespace Styde\Html\Tests;

use Styde\Html\Htmltag;
use Styde\Html\Facades\Html;

class HtmlBuilderTest extends TestCase
{
    /** @test */
    function it_generates_html_tags()
    {
        $this->assertEquals(
            '<span id="my-span">This is a span</span>',
            Html::tag('span', 'This is a span', ['id' => 'my-span'])->render()
        );

        $this->assertEquals(
            '<input type="text" readonly>',
            Html::tag('input', ['type' => 'text', 'readonly'])->render()
        );
    }

    /** @test */
    function it_closes_html_tags()
    {
        $htmlElement = new Htmltag('span');

        $this->assertEquals('</span>', (string) $htmlElement->close());
    }

    /** @test */
    function it_escapes_the_attributes_of_generated_tags()
    {
        $this->assertEquals(
            '<span id="&lt;my-span&gt;">Span</span>',
            Html::tag('span', 'Span', ['id' => '<my-span>'])->render()
        );
    }

    /** @test */
    function it_generates_html_tags_with_dynamic_methods()
    {
        $this->assertEquals(
            '<span id="my-span">This is a span</span>',
            Html::span('This is a span')->id('my-span')
        );
    }
    
    /** @test */
    function it_generates_links()
    {
        $this->assertHtmlEquals(
            '<a href="http://localhost/url">Text</a>',
            Html::link('url', 'Text')
        );
    }

    /** @test */
    function it_generate_the_html_class_attribute()
    {
        $html = Html::classes([
            'home' => true,
            'main',
            'dont-use-this' => false,
        ]);

        $this->assertEquals(' class="home main"', $html);
    }

    /** @test */
    function it_has_a_helper()
    {
        return $this->assertSame(app('html'), html());
    }
}
