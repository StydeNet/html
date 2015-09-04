<?php

namespace spec\Styde\Html;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;

class HtmlBuilderSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Styde\Html\HtmlBuilder');
    }

    public function it_generate_the_html_class_attribute()
    {
        $this->classes([
            'home' => true,
            'main',
            'dont-use-this' => false
        ])->shouldReturn(' class="home main"');
    }
}
