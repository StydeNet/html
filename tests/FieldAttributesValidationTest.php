<?php

namespace Styde\Html\Tests;

use Illuminate\Contracts\Validation\Rule;
use Styde\Html\Facades\Field;
use Styde\Html\Fields\FieldBuilder;
use Illuminate\Validation\Rules\In;
use Illuminate\Validation\Rules\NotIn;

class FieldAttributesValidationTest extends TestCase
{
    /** @test */
    function the_required_attribute_generates_the_required_rule()
    {
        $this->assertHasRules(['required'], Field::text('name', ['required']));
    }

    /** @test */
    function it_returns_the_email_rule_for_email_fields()
    {
        $this->assertHasRules(
            ['email', 'required'], Field::email('email', ['required' => true])
        );
    }

    /** @test */
    function it_returns_the_url_rule_when_the_type_of_the_field_is_url()
    {
        $this->assertHasRules(['url'], Field::url('link'));
    }

    /** @test */
    function it_builds_the_in_rule_when_the_field_includes_static_options()
    {
        $builder = Field::select('visibility')->required()->options([
            'public' => 'Everyone',
            'admin' => 'Admin only',
            'auth' => 'Authenticated users only',
            'guest' => 'Guest users only',
        ]);

        $this->assertHasRules(
            ['required', 'in:"public","admin","auth","guest"'],
            $builder->getValidationRules()
        );
    }

    /** @test */
    function it_builds_the_exists_rule_when_options_come_from_a_table()
    {
        $field = Field::select('parent_id')
            ->from('table_name', 'label', 'id', function ($query) { // TODO: improve this syntax
                $query->whereNull('parent_id')
                    ->orderBy('label', 'ASC');
            });

        $this->assertHasRules(['exists:table_name,id'], $field);
    }

    /** @test */
    function it_adds_the_max_rule()
    {
        $this->assertHasRules(['max:10'], Field::text('name')->max(10));

        $this->assertHasRules(['max:10'], Field::text('name')->maxlength(10));
    }

    /** @test */
    function it_adds_the_file_rule_to_file_fields()
    {
        $this->assertHasRules(['file'], Field::file('avatar'));
    }

    /** @test */
    function it_adds_the_date_rule_to_date_fields()
    {
        $this->assertHasRules(['date'], Field::date('time'));
    }

    /** @test */
    function it_adds_the_numeric_rule_to_number_fields()
    {
        $this->assertHasRules(['numeric'], Field::number('field'));
    }

    /** @test */
    function it_adds_the_nullable_rule()
    {
        $field = Field::text('name')->nullable();

        $this->assertHasRules(['nullable'], Field::text('name')->nullable());
    }

    /** @test */
    function it_adds_the_required_rule()
    {
        $this->assertHasRules(['required'], Field::text('name')->required());
    }

    /** @test */
    function it_adds_the_min_rule()
    {
        $this->assertHasRules(['min:10'], Field::text('name')->min(10));

        $this->assertHasRules(['min:10'], Field::text('name')->minlength(10));
    }

    /** @test */
    function it_adds_the_regex_rule()
    {
        $this->assertHasRules(['regex:/.{6,}/'], Field::text('name')->regex('.{6,}'));
    }

    /** @test */
    function it_adds_the_pattern_rule()
    {
        $field = Field::text('name')->pattern('.{6,}')->getField();

        $this->assertHasRules(['regex:/.{6,}/'], $field);
        $this->assertTrue($field->hasAttribute('pattern'));
    }

    /** @test */
    function it_adds_the_placeholder_attribute()
    {
        $field = Field::text('name')->placeholder('This is a placeholder')->getField();

        $this->assertEquals(['placeholder' => 'This is a placeholder'], $field->attributes);
    }

    /** @test */
    function it_adds_the_value_attribute()
    {
        $field = Field::text('name')->value('This is the value')->getField();

        $this->assertSame('This is the value', $field->value);
    }

    /** @test */
    function it_adds_the_required_if_rule()
    {
        $this->assertHasRules(
            ['required_if:status,1'],
            Field::text('name')->requiredIf('status', true)
        );
    }

    /** @test */
    function it_adds_the_required_unless_rule()
    {
        $this->assertHasRules(
            ['required_unless:price,>=,500'],
            Field::text('offer_code')->requiredUnless('price', '>=', 500)
        );

        $this->assertHasRules(
            ['required_unless:price,500'],
            Field::text('offer_code')->requiredUnless('price', 500)
        );
    }

    /** @test */
    function it_adds_the_required_with_rule()
    {
        $this->assertHasRules(
            ['required_with:foo,bar,john,doe'],
            Field::text('name')->requiredWith('foo', 'bar', 'john', 'doe')
        );
    }
    
    /** @test */
    function it_adds_the_required_with_all_rule()
    {
        $this->assertHasRules(
            ['required_with_all:foo,bar'],
            Field::text('name')->requiredWithAll('foo', 'bar')
        );
    }

    /** @test */
    function it_adds_the_required_without_rule()
    {
        $this->assertHasRules(
            ['required_without:John,Doe'],
            Field::text('name')->requiredWithout('John', 'Doe')
        );
    }

    /** @test */
    function it_adds_the_required_without_all_rule()
    {
        $this->assertHasRules(
            ['required_without_all:foo,bar'],
            Field::text('name')->requiredWithoutAll('foo', 'bar')
        );
    }

    /** @test */
    function it_adds_the_same_rule()
    {
        $this->assertHasRules(
            ['numeric', 'same:phone2'],
            Field::number('phone')->same('phone2')
        );
    }
    
    /** @test */
    function it_adds_the_size_rule()
    {
        $this->assertHasRules(['file', 'size:1000'], Field::file('image')->size(1000));
    }

    /** @test */
    function it_adds_the_image_rule()
    {
        $this->assertHasRules(['file', 'image'], Field::file('image')->image());
    }

    /** @test */
    function it_adds_the_accepted_rule()
    {
        $this->assertHasRules(['accepted'], Field::text('name')->accepted());
    }
    
    /** @test */
    function it_adds_the_active_url_rule()
    {
        $this->assertHasRules(['active_url'], Field::text('name')->activeUrl());
    }

    /** @test */
    function it_adds_the_after_rule()
    {
        $this->assertHasRules(['after:tomorrow'], Field::text('name')->after('tomorrow'));
    }

    /** @test */
    function it_adds_the_after_or_equal_rule()
    {
        $this->assertHasRules(
            ['after_or_equal:tomorrow'],
            Field::text('name')->afterOrEqual('tomorrow')
        );
    }

    /** @test */
    function it_adds_the_alpha_rule()
    {
        $this->assertHasRules(['alpha'], Field::text('name')->alpha());
    }

    /** @test */
    function it_adds_the_alpha_dash_rule()
    {
        $this->assertHasRules(['alpha_dash'], Field::text('name')->alphaDash());
    }

    /** @test */
    function it_adds_the_alpha_num_rule()
    {
        $this->assertHasRules(['alpha_num'], Field::text('name')->alphaNum());
    }

    /** @test */
    function it_adds_the_array_rule()
    {
        $this->assertHasRules(['array'], Field::text('name')->array());
    }

    /** @test */
    function it_adds_the_bail_rule()
    {
        $this->assertHasRules(['bail'], Field::text('name')->bail());
    }

    /** @test */
    function it_adds_the_before_rule()
    {
        $this->assertHasRules(
            ['before:tomorrow'],
            Field::text('name')->before('tomorrow')
        );
    }

    /** @test */
    function it_adds_the_before_or_equal_rule()
    {
        $this->assertHasRules(
            ['before_or_equal:tomorrow'],
            Field::text('name')->beforeOrEqual('tomorrow')
        );
    }

    /** @test */
    function it_adds_the_between_rule()
    {
        $this->assertHasRules(['between:1,10'], Field::text('name')->between(1,10));
    }
    
    /** @test */
    function it_adds_the_boolean_rule()
    {
        $this->assertHasRules(['boolean'], Field::text('value')->boolean());
    }

    /** @test */
    function it_adds_the_confirmed_rule()
    {
        $this->assertHasRules(['email', 'confirmed'], Field::email('email')->confirmed());
    }

    /** @test */
    function it_adds_the_date_rule()
    {
        $this->assertHasRules(['date'], Field::text('date')->date());
    }

    /** @test */
    function it_adds_the_date_equals_rule()
    {
        $this->assertHasRules(
            ['date_equals:tomorrow'],
            Field::text('date')->dateEquals('tomorrow')
        );
    }

    /** @test */
    function it_adds_the_date_format_rule()
    {
        $this->assertHasRules(
            ['date_format:d-m-Y'],
            Field::text('date')->dateFormat('d-m-Y')
        );
    }

    /** @test */
    function it_adds_the_different_rule()
    {
        $this->assertHasRules(
            ['different:first_name'],
            Field::text('last_name')->different('first_name')
        );
    }

    /** @test */
    function it_adds_the_digits_rule()
    {
        $this->assertHasRules(['numeric', 'digits:2'], Field::number('age')->digits(2));
    }
    
    /** @test */
    function it_adds_the_digits_between_rule()
    {
        $this->assertHasRules(
            ['numeric', 'digits_between:1,2'],
            Field::number('age')->digitsBetween(1, 2)
        );
    }

    /** @test */
    function it_adds_the_dimensions_rule()
    {
        $this->assertHasRules(
            ['file', 'dimensions:min_width=100,max_height=100'],
            Field::file('avatar')->dimensions(['min_width' => 100, 'max_height' => 100])
        );
    }

    /** @test */
    function it_adds_the_distinct_rule()
    {
        $this->assertHasRules(['distinct'], Field::text('name')->distinct());
    }

    /** @test */
    function it_adds_the_email_rule()
    {
        $this->assertHasRules(['email'], Field::text('email')->email());
    }

    /** @test */
    function it_adds_the_exists_rule()
    {
        $this->assertHasRules(
            ['exists:table,column'],
            Field::text('foo')->exists('table', 'column')
        );

        $this->assertHasRules(
            ['exists:table'],
            Field::text('foo')->exists('table')
        );
    }

    /** @test */
    function it_adds_the_file_rule()
    {
        $this->assertHasRules(['file'], Field::text('foobar')->file());
    }

    /** @test */
    function it_adds_the_filled_rule()
    {
        $this->assertHasRules(['filled'], Field::text('name')->filled());
    }

    /** @test */
    function it_adds_the_gt_rule()
    {
        $this->assertHasRules(['gt:field'], Field::text('name')->gt('field'));
    }

    /** @test */
    function it_adds_the_gte_rule()
    {
        $this->assertHasRules(['gte:field'], Field::text('name')->gte('field'));
    }

    /** @test */
    function it_adds_the_in_rule()
    {
        $this->assertHasRules(
            ['in:first-zone,second-zone'],
            Field::text('name')->in('first-zone', 'second-zone')
        );
    }

    /** @test */
    function it_adds_the_in_rule_class()
    {
        $this->assertHasRules(
            [new In(['first-zone','second-zone'])],
            Field::text('name')->in(['first-zone', 'second-zone'])
        );
    }

    /** @test */
    function it_adds_the_in_array_rule()
    {
        $this->assertHasRules(
            ['in_array:value'],
            Field::text('name')->inArray('value')
        );
    }

    /** @test */
    function it_adds_the_integer_rule()
    {
        $this->assertHasRules(['integer'], Field::text('dni')->integer());
    }
    
    /** @test */
    function it_adds_the_ip_rule()
    {
        $this->assertHasRules(['ip'], Field::text('ip')->ip());
    }

    /** @test */
    function it_adds_the_ipv4_rule()
    {
        $this->assertHasRules(['ipv4'], Field::text('ip')->ipv4());
    }

    /** @test */
    function it_adds_the_ipv6_rule()
    {
        $this->assertHasRules(['ipv6'], Field::text('ip')->ipv6());
    }

    /** @test */
    function it_adds_the_json_rule()
    {
        $this->assertHasRules(['json'], Field::text('data')->json());
    }
    
    /** @test */
    function it_adds_the_lt_rule()
    {
        $this->assertHasRules(['lt:field'], Field::text('data')->lt('field'));
    }

    /** @test */
    function it_adds_the_lte_rule()
    {
        $this->assertHasRules(['lte:field'], Field::text('data')->lte('field'));
    }

    /** @test */
    function it_adds_the_mimetypes_rule()
    {
        $this->assertHasRules(
            ['mimetypes:video/avi,video/mpeg'],
            Field::text('data')->mimetypes('video/avi', 'video/mpeg')
        );
    }

    /** @test */
    function it_adds_the_mimes_rule()
    {
        $this->assertHasRules(
            ['mimes:mp3,mp4,avi'],
            Field::text('data')->mimes('mp3', 'mp4', 'avi')
        );
    }

    /** @test */
    function it_adds_the_not_in_rule_string()
    {
        $this->assertHasRules(
            ['not_in:first-zone,second-zone'],
            Field::text('name')->notIn('first-zone', 'second-zone')
        );
    }

    /** @test */
    function it_adds_the_not_in_rule_class()
    {
        $this->assertHasRules(
            [new NotIn(['first-zone','second-zone'])],
            Field::text('name')->notIn(['first-zone', 'second-zone'])
        );
    }

    /** @test */
    function it_adds_the_not_regex_rule()
    {
        $this->assertHasRules(
            ['not_regex:/.{6,}/'],
            Field::text('name')->notRegex('.{6,}')
        );
    }

    /** @test */
    function it_adds_the_numeric_rule()
    {
        $this->assertHasRules(['numeric'], Field::text('number')->numeric());
    }

    /** @test */
    function it_adds_the_present_rule()
    {
        $this->assertHasRules(['present'], Field::text('name')->present());
    }

    /** @test */
    function it_adds_the_string_rule()
    {
        $this->assertHasRules(['string'], Field::text('name')->string());
    }

    /** @test */
    function it_adds_the_timezone_rule()
    {
        $this->assertHasRules(['timezone'], Field::text('date')->timezone());
    }
    
    /** @test */
    function it_adds_the_unique_rule()
    {
        $this->assertHasRules(
            ['unique:users,name,NULL,id'],
            Field::text('name')->unique('users', 'name')->getField()
        );
    }

    /** @test */
    function it_adds_ignore_in_unique_rule()
    {
        $this->assertHasRules(
            ['unique:users,name,"1",user_id'],
            Field::text('name')->unique('users', 'name')->ignore(1, 'user_id')
        );
    }

    /** @test */
    function it_cannot_use_method_ignore_without_calling_unique_first()
    {
        $this->expectException('Exception');

        Field::text('name')->ignore(1, 'user_id');
    }

    /** @test */
    function it_adds_the_url_rule()
    {
        $this->assertHasRules(['url'], Field::text('page')->url());
    }

    protected function assertHasRules(array $rules, $field)
    {
        if (method_exists($field, 'getParent')) {
            $field = $field->getParent();
        }

        if ($field instanceof FieldBuilder) {
            $field = $field->getField();
        }

        $this->assertEquals($rules, $field->getValidationRules());
    }
}
