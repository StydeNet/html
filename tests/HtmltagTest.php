<?php

namespace Styde\Html\Tests;

use Illuminate\Support\Facades\Route;
use Styde\Html\Facades\Html;
use Styde\Html\Htmltag;
use Styde\Html\VoidTag;

class HtmltagTest extends TestCase
{
    /** @test */
    function it_generates_an_html_tag()
    {
        $tag = new Htmltag('span', 'This is a span', ['id' => 'my-span']);

        $this->assertEquals(
            '<span id="my-span">This is a span</span>',
            $tag->render()
        );

        $this->assertEquals(
            '<span id="my-span">This is a span</span>',
            $tag
        );
    }

    /** @test */
    function it_generates_an_nested_html_tags()
    {
        $tag = new Htmltag('div', null, ['id' => 'div-id']);

        $text1 = new Htmltag('p', 'this is the first paragragh');

        $voidtag = new VoidTag('hr');

        $text2 = new Htmltag('p', 'this is the second paragragh');

        $tag->add($text1);
        $tag->add($voidtag);
        $tag->add($text2);

        $this->assertEquals(
            '<div id="div-id"><p>this is the first paragragh</p><hr><p>this is the second paragragh</p></div>',
            (string) $tag
        );
    }

    /** @test */
    function it_opens_html_tags()
    {
        $htmlElement = new Htmltag('div', null, ['class' => 'form-control']);

        $this->assertEquals('<div class="form-control">', (string) $htmlElement->open());
    }

    /** @test */
    function it_does_not_open_an_html_tag()
    {
        $tag = new Htmltag('span', 'This is a span', ['id' => 'my-span']);
        $tag->includeIf(false);

        $this->assertEquals('', $tag->open());
    }

    /** @test */
    function it_closes_html_tags()
    {
        $htmlElement = new Htmltag('span');

        $this->assertEquals('</span>', (string) $htmlElement->close());
    }

    /** @test */
    function it_does_not_close_html_tag()
    {
        $htmlElement = new Htmltag('span');
        $htmlElement->includeIf(false);

        $this->assertEquals('', (string) $htmlElement->close());
    }
}
