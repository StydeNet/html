<?php

namespace Styde\Html\Tests;

use Illuminate\Contracts\Support\Htmlable;

trait TestHelpers
{
    protected function assertHtmlEquals($expected, $actual)
    {
        $this->assertInstanceOf(Htmlable::class, $actual);

        $this->assertSame($expected, $actual->toHtml());
    }

    protected function assertTemplateMatches($template, $actual)
    {
        $this->assertInstanceOf(Htmlable::class, $actual);

        $actual = $actual->toHtml();

        $theme = config('html.theme', 'bootstrap4');

        $template = __DIR__ . "/snapshots/$theme/$template.html";

        if ( ! file_exists($template)) {
            file_put_contents($template, $actual);
            $this->markTestIncomplete("The template [$template] was created");

            return;
        }

        $html = file_get_contents($template);

        return $this->assertEquals(
            $this->removeExtraWhitespaces($html),
            $this->removeExtraWhitespaces($actual)
        );
    }

    private function removeExtraWhitespaces($string)
    {
        return trim(str_replace('> <', '><', preg_replace('/\s+/', ' ', $string)));
    }
}