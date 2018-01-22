<?php

namespace spec\Styde\Html;

use Styde\Html\Theme;
use PhpSpec\ObjectBehavior;
use Styde\Html\HtmlBuilder;
use Prophecy\Argument as Arg;
use Illuminate\Routing\UrlGenerator;
use \Illuminate\Contracts\Http\Kernel;
use Illuminate\Contracts\View\Factory;

class FormBuilderSpec extends ObjectBehavior
{
    function let(HtmlBuilder $html, UrlGenerator $url, Theme $theme, Factory $view, Kernel $kernel)
    {
        $theme->getView()->shouldBeCalled()->willReturn($view);

        $this->beConstructedWith($html, $url, 'csrf_token', $theme);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Styde\Html\FormBuilder');
    }

    function it_adds_a_novalidate_option($html)
    {
        // Expect
        $html->attributes(Arg::containing('novalidate'))->shouldBeCalled();

        // When
        $this->novalidate(true);
        $this->open(['method' => 'GET']);
    }

    function it_generates_time_inputs($html)
    {
        // Expect
        $html->attributes(Arg::withEntry('type', 'time'));

        // When
        $this->time('time');
    }

    function it_generate_radios($theme)
    {
        // Having
        $name = 'gender';
        $attributes = [];
        $hasErrors = false;

        // Expect
        $radios = [
            [
                "name" => "gender",
                "value" => "m",
                "label" => "Male",
                "selected" => true,
                "id" => "gender_m"
            ],
            [
                "name" => "gender",
                "value" => "f",
                "label" => "Female",
                "selected" => false,
                "id" => "gender_f"
            ]
        ];
        $theme->render(null, compact('name', 'radios', 'attributes', 'hasErrors'), "forms.radios")->shouldBeCalled();

        // When
        $this->radios('gender', ['m' => 'Male', 'f' => 'Female'], 'm');
    }

    function it_generate_checkboxes($theme)
    {
        // Having
        $name = 'tags';
        $tags = ['php' => 'PHP', 'python' => 'Python', 'js' => 'JS', 'ruby' => 'Ruby on Rails'];
        $checked = ['php', 'js'];
        $attributes = [];
        $hasErrors = false;

        // Expect
        $checkboxes = [
            [
                "name" => "tags[]",
                "value" => "php",
                "label" => "PHP",
                "checked" => true,
                "id" => "tags_php"
            ],
            [
                "name" => "tags[]",
                "value" => "python",
                "label" => "Python",
                "checked" => false,
                "id" => "tags_python"
            ],
            [
                "name" => "tags[]",
                "value" => "js",
                "label" => "JS",
                "checked" => true,
                "id" => "tags_js"
            ],
            [
                "name" => "tags[]",
                "value" => "ruby",
                "label" => "Ruby on Rails",
                "checked" => false,
                "id" => "tags_ruby"
            ]
        ];
        $theme->render(null, compact('name', 'checkboxes', 'attributes', 'hasErrors'), "forms.checkboxes")->shouldBeCalled();

        // When
        $this->checkboxes('tags', $tags, $checked);
    }

}
