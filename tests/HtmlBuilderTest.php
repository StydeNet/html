<?php

namespace Styde\Html\Tests;

use Mockery;
use Styde\Html\HtmlBuilder;

class HtmlBuilderTest extends TestCase
{
    /**
     * @var HtmlBuilder
     */
    var $htmlBuilder;

    function setUp()
    {
        $this->htmlBuilder = new HtmlBuilder(
            Mockery::mock(\Illuminate\Contracts\Routing\UrlGenerator::class),
            Mockery::mock(\Illuminate\Contracts\View\Factory::class)
        );
    }

    /** @test */
    function it_generates_html_tags()
    {
        $this->assertEquals(
            '<span id="my-span">This is a span</span>',
            $this->htmlBuilder->tag('span', 'This is a span', ['id' => 'my-span'])
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