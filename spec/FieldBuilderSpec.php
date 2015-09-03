<?php

namespace spec\Styde\Html;

use Styde\Html\Theme;
use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Styde\Html\FormBuilder;
use Styde\Html\Access\AccessHandler;
use Illuminate\Translation\Translator as Lang;

class FieldBuilderSpec extends ObjectBehavior
{
    public function let(FormBuilder $form, Theme $theme, Lang $lang)
    {
        $this->beConstructedWith($form, $theme, $lang);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Styde\Html\FieldBuilder');
    }

    public function it_generates_a_text_field($form, $theme, $lang)
    {
        // Expect
        $form->text("name", "value", ["class" => "", "id" => "name"])
            ->shouldBeCalled()
            ->willReturn('<input>');

        $lang->get('validation.attributes.name')
            ->shouldBeCalled()
            ->willReturn('validation.attributes.name');

        $theme->render(null, [
            "htmlName" => "name",
            "id" => "name",
            "label" => "Name",
            "input" => "<input>",
            "errors" => [],
            "hasErrors" => false,
            "required" => false
        ], "fields.default")->shouldBeCalled()->willReturn('html');

        // When
        $this->text('name', 'value')->shouldReturn('html');
    }

    public function it_checks_for_access(AccessHandler $access)
    {
        $this->setAccessHandler($access);
        $access->check([])->shouldBeCalled()->willReturn(false);
        $this->text('name', 'value')->shouldReturn('');
    }

    public function it_generates_a_text_field_with_abbreviated_options($form, $theme, $lang)
    {
        // Having
        $this->setAbbreviations(['ph' => 'placeholder']);
        $placeholder = "Write your name";

        // Expect
        $form->text("name", "value", Argument::withEntry('placeholder', $placeholder))
            ->shouldBeCalled();

        // When
        $this->text('name', 'value', ['ph' => $placeholder]);
    }

    public function it_generates_a_text_field_with_a_custom_label($theme, $lang)
    {
        // Having
        $label = "Full name";

        // Expect
        $lang->get('validation.attributes.name')->shouldNotBeCalled();
        $theme->render(null, Argument::withEntry('label', $label), "fields.default")
            ->shouldBeCalled();

        // When
        $this->text('name', 'value', ['label' => $label]);
    }

    public function it_generates_a_field_with_a_custom_templates($theme)
    {
        // Having
        $custom = 'custom-template-here';

        // Expect
        $theme->render($custom, Argument::any(), "fields.default")
            ->shouldBeCalled();

        // When
        $this->text('name', 'value', ['template' => $custom]);
    }

    public function it_generates_a_select_field($form, $theme)
    {
        // Having
        $attributes = ['empty' => '', 'label' => 'Gender'];
        $options = ['m' => 'Male', 'f' => 'Female'];
        $result = array_merge(['' => ''], $options);

        // Expectc
        $form->select("gender", $result, null, ["class" => "", "id" => "gender"])->shouldBeCalled();

        // When
        $this->select('gender', $options, null, $attributes);
    }

    public function it_adds_an_empty_option_to_select_fields($form, $lang)
    {
        // Having
        $empty = 'Select option';
        $options = ['m' => 'Male', 'f' => 'Female'];
        $result = array_merge(['' => $empty], $options);

        // Expec
        $lang->get("validation.empty_option.gender")
            ->shouldBeCalled()
            ->willReturn("validation.empty_option.gender");

        $lang->get("validation.empty_option.default")
            ->shouldBeCalled()
            ->willReturn($empty);

        $form->select("gender", $result, "m", ["class" => "", "id" => "gender"])
            ->shouldBeCalled()
            ->willReturn('<select>');

        // When
        $this->select('gender', $options, 'm', ['label' => 'Gender']);
    }

    public function it_generates_a_text_field_with_errors($form, $theme, $lang)
    {
        // Having
        $errors = ['This is really wrong'];
        $this->setErrors([
            'name' => $errors
        ]);

        // Expect
        $form->text("name", "value", ["class" => "error", "id" => "name"])->shouldBeCalled();
        $theme->render(
            null,
            Argument::withEntry('errors', $errors),
            "fields.default"
        )->shouldBeCalled();

        // When
        $this->text('name', 'value');
    }

    public function it_takes_select_options_from_the_model($form, User $user)
    {
        // Having
        $attributes = ['empty' => '', 'label' => 'Gender'];
        $options = ['m' => 'Male', 'f' => 'Female'];
        $result = array_merge(['' => ''], $options);

        // Expect
        $form->getModel()->shouldBeCalled()->willReturn($user);
        $user->getGenderOptions()->shouldBeCalled()->willReturn($options);
        $form->select("gender", $result, "m", ["class" => "", "id" => "gender"])->shouldBeCalled();

        // When
        $this->select('gender', null, 'm', $attributes);
    }
}

interface User
{
    public function getGenderOptions();
}
