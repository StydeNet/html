<?php

namespace spec\Styde\Html\FormModel;

use Styde\Html\Theme;
use Styde\Html\{HtmlBuilder, FormBuilder};
use PhpSpec\ObjectBehavior;
use Illuminate\Contracts\Routing\UrlGenerator;

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

    function it_renders_buttons($formBuilder)
    {
        $formBuilder->button();

        $this->submit('Submit')->class('btn-primary');
        $this->reset('Reset');
        $this->button('Button');
        $this->link('Link', 'link');

        $this->render()->shouldReturn(
             '<button type="submit" class="btn-primary">Submit</button>'
            .'<button type="reset">Reset</button>'
            .'<button type="button">Button</button>'
            .'<a href="link">Link</a>'
        );
    }
}
