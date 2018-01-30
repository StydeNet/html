<?php

namespace spec\Styde\Html\FormModel;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Styde\Html\FormBuilder;
use Styde\Html\FormModel\ButtonCollection;
use Styde\Html\FormModel\FieldCollection;
use Styde\Html\Theme;

class FormSpec extends ObjectBehavior
{
    function let(
        FormBuilder $formBuilder,
        FieldCollection $fieldCollection,
        ButtonCollection $buttonCollection,
        Theme $theme
    ) {
        $this->beConstructedWith($formBuilder, $fieldCollection, $buttonCollection, $theme);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Styde\Html\FormModel\Form');
    }

    function it_opens_a_form($formBuilder)
    {
        $formBuilder->open([
            'route'  => ['user.update', 1],
            'method' => 'PUT',
            'class'  => 'form',
        ])->shouldBeCalled()->willReturn('<form>');

        // Handy methods to configure a form from the form model
        $this->route('user.update', 1);
        $this->method('PUT');
        $this->classes('form');

        $this->open()->shouldReturn('<form>');
    }

    function it_opens_a_form_with_extra_attributes($formBuilder)
    {
        $formBuilder->open([
            'method' => 'POST',
            'class'  => 'form',
            'id'     => 'my_form',
        ])->shouldBeCalled();

        // You can pass an array of attributes
        $this->attributes(['method' => 'POST']);
        // Or just one attribute / value pair
        $this->attributes('class', 'form');
        // You can pass extra attributes when you open the form in the template
        $this->open(['id' => 'my_form']);
    }

    function it_opens_a_form_with_files($formBuilder)
    {
        $formBuilder->open(['enctype' => 'multipart/form-data'])->shouldBeCalled();

        $this->withFiles();
        $this->open();
    }

    function it_closes_a_form($formBuilder)
    {
        $formBuilder->close()->shouldBeCalled();

        $this->close();
    }

    function it_renders_a_form($fieldCollection, $buttonCollection, $theme)
    {
        $theme->render([
            'form'    => $this->getWrappedObject(),
            'fields'  => $fieldCollection,
            'buttons' => $buttonCollection,
        ]);
    }

}
