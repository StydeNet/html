<?php

namespace spec\Styde\Html;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StrSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Styde\Html\Str');
    }

    function it_converts_strings_like_field_names_to_titles()
    {
        $this->title('full_name')->shouldReturn('Full name');
    }

    function it_converts_plain_text_links_to_html_links()
    {
        // HTTP
        $text = 'Please visit http://styde.net';
        $html = 'Please visit <a href="http://styde.net" target="_blank">http://styde.net</a>';
        $this->linkify($text)->shouldReturn($html);

        // HTTPS
        $text = 'Please visit https://styde.net';
        $html = 'Please visit <a href="https://styde.net" target="_blank">https://styde.net</a>';
        $this->linkify($text)->shouldReturn($html);
    }

    function it_resumes_a_string()
    {
        $text = '"My name is Ozymandias, king of kings:
        Look on my works, ye Mighty, and despair!"
        Nothing beside remains. Round the decay
        Of that colossal wreck, boundless and bare
        The lone and level sands stretch far away';

        $resume = '"My name is Ozymandias, king of kings: Look on my works,...';
        $this->teaser($text, 56)->shouldReturn($resume);

        // it also strips HTML
        $text = '"My name is <strong>Ozymandias</strong>, <i>king of kings</i>:
        Look on my works, ye Mighty, and despair!"
        Nothing beside remains. Round the decay
        Of that colossal wreck, boundless and bare
        The lone and level sands stretch <i>far away</i>';

        $resume = '"My name is Ozymandias, king of kings: Look on my works,...';
        $this->teaser($text, 56)->shouldReturn($resume);

        // if a string is short it does nothing
        $text = 'You know nothing, John Snow';
        $this->teaser($text, 50)->shouldReturn($text);
    }
}