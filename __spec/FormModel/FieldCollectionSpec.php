<?php

namespace spec\Styde\Html\FormModel;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Styde\Html\FieldBuilder;
use Styde\Html\FormModel\Field;

class FieldCollectionSpec extends ObjectBehavior
{
    function let(FieldBuilder $fieldBuilder)
    {
        $this->beConstructedWith($fieldBuilder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Styde\Html\FormModel\FieldCollection');
    }

    function it_adds_a_field()
    {
        $this->add('first_name', 'text');
        $this->__get('first_name')->shouldReturnAnInstanceOf(Field::class);
    }

    function it_renders_fields($fieldBuilder)
    {
        $roles = ['admin' => 'Admin' , 'user' => 'User'];

        $fieldName = $this->add('name')->label('Full name');
        $fieldRole = $this->add('role', 'select')->options($roles);

        $fieldBuilder->render($fieldName)
            ->shouldBeCalled()
            ->willReturn('<input>');

        $fieldBuilder->render($fieldRole)
            ->shouldBeCalled()
            ->willReturn('<select>');

        $this->render()->shouldReturn('<input><select>');
    }

}
