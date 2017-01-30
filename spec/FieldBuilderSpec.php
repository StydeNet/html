<?php

namespace spec\Styde\Html;

use Styde\Html\Theme;
use Prophecy\Argument;
use Styde\Html\FormBuilder;
use PhpSpec\ObjectBehavior;
use Illuminate\Support\MessageBag;
use Styde\Html\Access\AccessHandler;
use Illuminate\Contracts\Session\Session;
use Illuminate\Translation\Translator as Lang;

class FieldBuilderSpec extends ObjectBehavior
{
    function let(FormBuilder $form, Theme $theme, Lang $lang)
    {
        $this->beConstructedWith($form, $theme, $lang);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Styde\Html\FieldBuilder');
    }

    function it_generates_a_text_field($form, $theme, $lang)
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

    function it_checks_for_access(AccessHandler $access)
    {
        $this->setAccessHandler($access);
        $access->check([])->shouldBeCalled()->willReturn(false);
        $this->text('name', 'value')->shouldReturn('');
    }

    function it_generates_a_text_field_with_abbreviated_options($form, $theme, $lang)
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

    function it_generates_a_text_field_with_a_custom_label($theme, $lang)
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

    function it_generates_a_text_field_with_a_custom_id($theme, $lang)
    {
        // Having
        $id = "name_new_id";

        // Expect
        $theme->render(null, Argument::withEntry('id', $id), "fields.default")
            ->shouldBeCalled();

        // When
        $this->text('name', 'value', ['id' => $id]);
    }

    function it_generates_a_field_with_a_custom_templates($theme)
    {
        // Having
        $custom = 'custom-template-here';

        // Expect
        $theme->render($custom, Argument::any(), "fields.default")
            ->shouldBeCalled();

        // When
        $this->text('name', 'value', ['template' => $custom]);
    }

    function it_generates_a_select_field($form, $theme)
    {
        // Having
        $attributes = ['empty' => '', 'label' => 'Gender'];
        $options = ['m' => 'Male', 'f' => 'Female'];
        $result = array_merge(['' => ''], $options);

        // Expect
        $form->select("gender", $result, null, ["class" => "", "id" => "gender"])->shouldBeCalled();

        // When
        $this->select('gender', $options, null, $attributes);
    }

    function it_generates_a_select_field_without_empty_option($form, $theme)
    {
        // Having
        $attributes = ['empty' => false, 'label' => 'Gender'];
        $options = ['m' => 'Male', 'f' => 'Female'];
        $result = $options; //no empty option

        // Expect
        $form->select("gender", $result, null, ["class" => "", "id" => "gender"])->shouldBeCalled();

        // When
        $this->select('gender', $options, null, $attributes);
    }

    function it_generates_a_multiple_select_field($form, $theme)
    {
        // Having
        $attributes = ['label' => 'Tags'];
        $selected = ['php', 'laravel'];
        $options = [
            'php'     => 'PHP',
            'laravel' => 'Laravel',
            'symfony' => 'Symfony',
            'ruby'    => 'Ruby on Rails'
        ];
        $result = $options; //no empty option

        // Expect
        $form->select(
            "tags", $result, $selected,
            [0 => "multiple", "class" => "", "id" => "tags"]
        )->shouldBeCalled();

        // When
        $this->selectMultiple('tags', $options, $selected, $attributes);
    }

    function it_adds_an_empty_option_to_select_fields($form, $lang)
    {
        // Having
        $empty = 'Select option';
        $options = ['m' => 'Male', 'f' => 'Female'];
        $result = array_merge(['' => $empty], $options);

        // Expect
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

    function it_generates_a_text_field_with_errors($form, $theme, $lang, Session $session)
    {
        // Having
        $session->get('errors')->willReturn(new MessageBag([
            'name' => ['This is wrong']
        ]));

        $this->setSessionStore($session);

        // Expect
        $form->text("name", "value", ["class" => "error", "id" => "name"])->shouldBeCalled();
        $theme->render(
            null,
            Argument::withEntry('errors', ['This is wrong']),
            "fields.default"
        )->shouldBeCalled();

        // When
        $this->text('name', 'value');
    }

    function it_generates_a_text_field_with_extra_data($theme)
    {
        // Expect
        $theme->render(
            null,
            Argument::withEntry('extra', 'extra'),
            "fields.default"
        )->shouldBeCalled();

        // When
        $this->text('name', 'value', [], ['extra' => 'extra']);
    }

    function it_takes_select_options_from_the_model($form, User $user)
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

    function it_generates_fields_with_default_css_classes_by_type($form)
    {
        // When
        $this->setCssClasses([
            'text' => 'text-field',
            'default' => 'deafult-field'
        ]);

        // Expect
        $form->text('name', null, ['class' => 'text-field', 'id' => 'name'])->shouldBeCalled();

        // When
        $this->text('name');
    }

    function it_generates_fields_with_default_css_classes($form)
    {
        // When
        $this->setCssClasses([
            'text' => 'text-field',
            'default' => 'default-field'
        ]);

        // Expect
        $form->textarea('content', null, ['class' => 'default-field', 'id' => 'content'])->shouldBeCalled();

        // When
        $this->textarea('content');
    }

    function it_uses_a_macros()
    {
        $this->macro('myMacro', function () {
             return '<macro>';
        });

        $this->myMacro()->shouldReturn('<macro>');
    }
}

interface User {

    public function getGenderOptions();

}
