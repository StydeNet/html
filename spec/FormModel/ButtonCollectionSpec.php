<?php

namespace spec\Styde\Html\FormModel;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Styde\Html\FormBuilder;
use Styde\Html\HtmlBuilder;

class ButtonCollectionSpec extends ObjectBehavior
{
    function let(FormBuilder $formBuilder, HtmlBuilder $htmlBuilder)
    {
        $this->beConstructedWith($formBuilder, $htmlBuilder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Styde\Html\FormModel\ButtonCollection');
    }

    function it_renders_buttons($formBuilder, $htmlBuilder)
    {
        $formBuilder->button('Submit', ['type' => 'submit', 'class' => 'btn-primary'])
            ->shouldBeCalled()
            ->willReturn('<submit>');

        $formBuilder->button('Reset', ['type' => 'reset'])
            ->shouldBeCalled()
            ->willReturn('<reset>');

        $formBuilder->button('Button', ['type' => 'button'])
            ->shouldBeCalled()
            ->willReturn('<button>');

        $htmlBuilder->link('Link', 'link', [], false)
            ->shouldBeCalled()
            ->willReturn('<a>');

        $this->submit('Submit')->classes('btn-primary');
        $this->reset('Reset');
        $this->button('Button');
        $this->link('Link', 'link');

        $this->render()->shouldReturn('<submit><reset><button><a>');
    }
}
