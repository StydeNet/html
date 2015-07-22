<?php

namespace spec\Styde\Html;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class ThemeSpec extends ObjectBehavior
{
    function let(Factory $factory)
    {
        $this->beConstructedWith($factory, 'theme');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Styde\Html\Theme');
    }

    function it_renders_custom_templates(Factory $factory, View $view)
    {
        // Having
        $custom = 'custom.template';
        $data = array();
        $template = 'template';

        // Expect
        $factory->make($custom, $data)->shouldBeCalled()->willReturn($view);

        $view->render()->shouldBeCalled()->willReturn('<html>');

        $factory->exists("themes/theme/$template")->shouldNotBeCalled();

        // When
        $this->render($custom, $data, $template)->shouldReturn('<html>');
    }

    function it_renders_published_templates(Factory $factory, View $view)
    {
        // Having
        $custom = null;
        $data = array();
        $template = 'template';

        // Expect
        $factory->make(null, $data)->shouldNotBeCalled();

        $factory->exists("themes/theme/$template")->shouldBeCalled()->willReturn(true);
        $factory->make("themes/theme/$template", [])->shouldBeCalled()->willReturn($view);
        $view->render()->shouldBeCalled()->willReturn('<html>');

        // When
        $this->render($custom, $data, $template)->shouldReturn('<html>');
    }

    function it_renders_default_templates(Factory $factory, View $view)
    {
        // Having
        $custom = null;
        $data = array();
        $template = 'theme/template';

        // Expect
        $factory->make(null, $data)->shouldNotBeCalled();
        $factory->exists("themes/theme/$template")->shouldBeCalled()->willReturn(false);
        $factory->make("themes/theme/$template", [])->shouldNotBeCalled();
        $factory->make("styde.html::theme/$template", [])->shouldBeCalled()->willReturn($view);
        $view->render()->shouldBeCalled()->willReturn('<html>');

        // When
        $this->render($custom, $data, $template)->shouldReturn('<html>');
    }
}