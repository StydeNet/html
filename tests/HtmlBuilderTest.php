<?php

namespace Styde\Html\Tests;

use Illuminate\Support\Facades\Route;
use Styde\Html\Facades\Html;
use Styde\Html\Htmltag;

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
    function it_escapes_the_attributes_of_generated_tags()
    {
        $this->assertEquals(
            '<span id="&lt;my-span&gt;">Span</span>',
            Html::tag('span', 'Span', ['id' => '<my-span>'])->render()
        );
    }
    
    /** @test */
    function remove_attribute_from_tag()
    {
        $this->assertEquals(
            '<input name="test">',
            Html::tag('input', ['name' => 'test', 'required'])->removeAttr('required')->render()
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
    function it_generates_a_secure_link()
    {
        $this->assertHtmlEquals(
            '<a href="https://localhost/url">Text</a>',
            Html::Securelink('url', 'Text')
        );
    }

    /** @test */
    function it_generates_links_with_additional_attributes()
    {
        $this->assertHtmlEquals(
            '<a target="_blank" href="http://localhost/url">Text</a>',
            Html::link('url', 'Text', ['target' => '_blank'])
        );
    }

    /** @test */
    function it_generates_a_style_sheet_link()
    {
        $this->assertHtmlEquals(
            '<link type="text/css" rel="stylesheet" href="http://localhost/css/app.css">',
            Html::style('css/app.css')
        );
    }

    /** @test */
    function it_generates_a_javascript_tag()
    {
        $this->assertHtmlEquals(
            '<script src="http://localhost/js/app.js"></script>',
            Html::script('js/app.js')
        );
    }

    /** @test */
    function it_generates_a_link_from_asset()
    {
        $this->assertHtmlEquals(
            '<a href="http://localhost/user/avatar">Avatar</a>',
            Html::linkAsset('user/avatar', 'Avatar')
        );
    }

    /** @test */
    function it_generates_a_link_from_a_route()
    {
        Route::get('dashboard', ['as' => 'dashboard']);
        Route::get('edit/{page}', ['as' => 'pages.edit']);

        $this->assertHtmlEquals(
            '<a href="http://localhost/dashboard">Control Panel</a>',
            Html::linkRoute('dashboard', 'Control Panel')
        );

        $this->assertHtmlEquals(
            '<a href="http://localhost/edit/profile">Edit profile</a>',
            Html::linkRoute('pages.edit', 'Edit profile', ['profile'])
        );
    }

    /** @test */
    function it_generates_the_html_class_attribute()
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

    /** @test */
    function it_is_macroable()
    {
        Html::macro('myCustomMethod', function () {
            return 'my-custom-tag';
        });

        $this->assertSame('my-custom-tag', Html::myCustomMethod());
    }
}
