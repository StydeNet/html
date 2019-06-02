<?php

namespace Styde\Html\Tests;

use Mockery as m;
use Styde\Html\Transformer;
use Illuminate\Http\Request;

class TransformFieldsTest extends TestCase
{
    /** @test */
    function validate_returns_transformed_values()
    {
        $request = m::mock(Request::class);
        $request->shouldReceive('validate')
            ->andReturn([
                'name' => 'Duilio Palacios',
            ]);

        $form = app(TestFormModel::class);

        $form->text('name')
           ->transformer(new ConvertCaseTransformer);

        $data = $form->validate($request);

        $this->assertSame('DUILIO PALACIOS', $data['name']);
    }

    /** @test */
    function fields_render_transformed_values()
    {
        $form = app(TestFormModel::class);

        $form->text('name')
            ->value('DUILIO PALACIOS')
            ->transformer(new ConvertCaseTransformer)
            ->controlOnly();

        $this->assertHtmlEquals(
            '<input type="text" name="name" value="duilio palacios">',
            $form->name
        );
    }
}

class ConvertCaseTransformer implements Transformer
{
    public function fromRequest($value)
    {
        return strtoupper($value);
    }

    public function forDisplay($value)
    {
        return strtolower($value);
    }
}
