<?php

namespace Styde\Html\Tests;

use Styde\Html\Str;

class StrTest extends TestCase
{
    /** @test */
    function it_converts_strings_like_field_names_to_titles()
    {
        $this->assertSame('Full name', Str::title('full_name'));
    }

    /** @test */
    function it_converts_plain_text_links_to_html_links()
    {
        // HTTP
        $this->assertSame(
            'Please visit <a href="http://styde.net" target="_blank">http://styde.net</a>',
            Str::linkify('Please visit http://styde.net')
        );

        // HTTPS
        $this->assertSame(
            'Please visit <a href="https://styde.net" target="_blank">https://styde.net</a>',
            Str::linkify('Please visit https://styde.net')
        );
    }

    /** @test */
    function it_resumes_a_string()
    {
        $fullText = '"My name is Ozymandias, king of kings:
        Look on my works, ye Mighty, and despair!"
        Nothing beside remains. Round the decay
        Of that colossal wreck, boundless and bare
        The lone and level sands stretch far away';

        $this->assertSame(
            '"My name is Ozymandias, king of kings: Look on my works,...',
            Str::teaser($fullText, 56)
        );

        // it also strips HTML
        $textWithHtml = '"My name is <strong>Ozymandias</strong>, <i>king of kings</i>:
        Look on my works, ye Mighty, and despair!"
        Nothing beside remains. Round the decay
        Of that colossal wreck, boundless and bare
        The lone and level sands stretch <i>far away</i>';

        $this->assertSame(
            '"My name is Ozymandias, king of kings: Look on my works,...',
            Str::teaser($textWithHtml, 56)
        );

        // if a string is short it does nothing
        $text = 'You know nothing, John Snow';

        $this->assertSame($text, Str::teaser($text, 28));
    }
}