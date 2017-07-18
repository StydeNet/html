<?php

namespace spec\Styde\Html;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Contracts\View\Factory as View;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HtmlBuilderSpec extends ObjectBehavior
{
    function let(UrlGenerator $url, View $view)
    {
        $this->beConstructedWith($url, $view);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Styde\Html\HtmlBuilder');
    }

    public function it_generates_html_tags()
    {
        $this->tag('span', 'This is a span', ['id' => 'my-span'])
            ->render()->shouldReturn('<span id="my-span">This is a span</span>');
    }

    public function it_generates_html_tags_with_dynamic_methods()
    {
        $this->span('This is a span')->id('my-span')
            ->render()->shouldReturn('<span id="my-span">This is a span</span>');
    }

    function it_generate_the_html_class_attribute()
    {
        $this->classes([
            'home' => true,
            'main',
            'dont-use-this' => false
        ])->shouldReturn(' class="home main"');
    }
}
