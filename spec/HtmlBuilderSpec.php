<?php

namespace spec\Styde\Html;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HtmlBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Styde\Html\HtmlBuilder');
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
