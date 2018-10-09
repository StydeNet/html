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

        $fields->add('first_name', 'text');

        $this->assertInstanceOf(Field::class, $fields->first_name);
    }

    /** @test */
    function it_renders_fields()
    {
        $fields = new FieldCollection(field());

        $fields->add('name')->label('Full name');
        $fields->add('role', 'select')->options(['admin' => 'Admin' , 'user' => 'User']);

        $this->assertTemplateMatches('field-collection/fields', $fields->render());
    }

    /** @test */
    function it_render_field_password_with_required_rule()
    {
        $fields = new FieldCollection(field());

        $fields->password('password')->required();

        $this->assertTemplateMatches('field-collection/fields', $fields->render());
    }
}