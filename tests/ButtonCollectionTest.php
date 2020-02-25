<?php

namespace Styde\Html\Tests;

class ButtonCollectionTest extends TestCase
{
    /** @test */
    function it_renders_buttons()
    {
        $form = app(TestFormModel::class);

        $form->submit('Submit')->class('btn-primary');
        $form->reset('Reset');
        $form->button('Button');
        $form->link('link', 'Text');

        $this->assertTemplateMatches('form/buttons', $form->renderButtons());
    }
}
