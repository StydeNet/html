<?php

namespace Styde\Html\Tests;

use Styde\Html\FormModel\Field;
use Styde\Html\FormModel\FieldCollection;

class FieldCollectionTest extends TestCase
{
    /** @test */
    function it_adds_a_field()
    {
        $fields = new FieldCollection(field());

        $fields->text('first_name');

        $this->assertInstanceOf(Field::class, $fields->first_name);
    }

    /** @test */
    function it_renders_fields()
    {
        $fields = new FieldCollection(field());

        $fields->text('name')->label('Full name');
        $fields->select('role')->options(['admin' => 'Admin' , 'user' => 'User']);

        $this->assertTemplateMatches('field-collection/fields', $fields->render());
    }
}
