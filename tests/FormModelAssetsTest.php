<?php

namespace Styde\Html\Tests;

use Styde\Html\FormModel;

class FormModelAssetsTest extends TestCase
{
    /** @test */
    function render_the_scripts()
    {
        $form = app(AssetsForm::class);

        $this->assertSame(['my-calendar.js', '//cdn.example.com/the-editor.js'], $form->scripts());

        $this->assertSame(
            '<script src="http://localhost/my-calendar.js"></script>'.
            '<script src="//cdn.example.com/the-editor.js"></script>',
            $form->renderScripts()->toHtml()
        );
    }

    /** @test */
    function render_the_styles()
    {
        $form = app(AssetsForm::class);

        $this->assertSame(['my-calendar.css', '//cdn.example.com/the-editor.css'], $form->styles());

        $this->assertSame(
            '<link type="text/css" rel="stylesheet" href="http://localhost/my-calendar.css">'
            .'<link type="text/css" rel="stylesheet" href="//cdn.example.com/the-editor.css">',
            $form->renderStyles()->toHtml()
        );
    }
}

class AssetsForm extends FormModel
{
    public function setup()
    {
        $this->calendar('starts_at');

        $this->calendar('ends_at');

        $this->tag('p', 'Not a field');

        $this->editor('body');

        $this->editor('content');
    }

    protected function calendar($name)
    {
        $this->text($name)
            ->script('my-calendar.js')
            ->style('my-calendar.css');
    }

    protected function editor($name)
    {
        $this->text($name)
            ->script('//cdn.example.com/the-editor.js')
            ->style('//cdn.example.com/the-editor.css');
    }
}
