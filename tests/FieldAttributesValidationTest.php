<?php

namespace Styde\Html\Tests;

use Illuminate\Support\Facades\Gate;
use Styde\Html\Facades\Field;
use Illuminate\Validation\Rules\Exists;

class FieldAttributesValidationTest extends TestCase
{
    /** @test */
    function the_required_attribute_generates_the_required_rule()
    {
        $field = Field::text('name', ['required']);

        $this->assertSame(['required'], $field->getValidationRules());
    }

    /** @test */
    function it_returns_the_email_rule_for_email_fields()
    {
        $field = Field::email('email', ['required' => true]);

        $this->assertEquals(['email', 'required'], $field->getValidationRules());
    }

    /** @test */
    function it_returns_the_url_rule_when_the_type_of_the_field_is_url()
    {
        $field = Field::url('link', ['required' => true]);

        $this->assertEquals(['url', 'required'], $field->getValidationRules());
    }

    /** @test */
    function it_returns_multiple_rules()
    {
        $field = Field::email('email', ['required']);

        $this->assertEquals(['email', 'required'], $field->getValidationRules());
    }

    /** @test */
    function it_builds_the_in_rule_when_the_field_includes_static_options()
    {
        $field = Field::select('visibility', null, ['required'])->options([
            'public' => 'Everyone',
            'admin' => 'Admin only',
            'auth' => 'Authenticated users only',
            'guest' => 'Guest users only',
        ]);

        $this->assertSame('in:"public","admin","auth","guest"', (string) $field->getValidationRules()[1]);
    }

    /** @test */
    function it_builds_the_exists_rule_when_options_come_from_a_table()
    {
        $field = Field::select('parent_id')
            ->from('table_name', 'label', 'id', function ($query) {
                $query->whereNull('parent_id')
                    ->orderBy('label', 'ASC');
            })
            ->label('Parent');

        $this->assertSame('exists:table_name,id', (string) $field->getValidationRules()[0]);
        $this->assertInstanceOf(Exists::class, $field->getValidationRules()[0]);
    }

    /** @test */
    function it_adds_the_max_rule()
    {
        $field = Field::text('name')->max(10);

        $this->assertEquals(['max:10'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_file_rule_to_file_fields()
    {
        $field = Field::file('avatar');

        $this->assertEquals(['file'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_date_rule_to_date_fields()
    {
        $field = Field::date('time');

        $this->assertEquals(['date'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_numeric_rule_to_number_fields()
    {
        $field = Field::number('field');

        $this->assertEquals(['numeric'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_nullable_rule()
    {
        $field = Field::text('name')->nullable();

        $this->assertSame(['nullable'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_required_rule()
    {
        $field = Field::text('name')->required();

        $this->assertSame(['required'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_min_rule()
    {
        $field = Field::text('name')->minlength(10);

        $this->assertSame(['min:10'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_regex_rule()
    {
        $field = Field::text('name')->regex('.{6,}');

        $this->assertSame(['regex:/.{6,}/'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_placeholder_attribute()
    {
        $field = Field::text('name')->placeholder('Foo Bar');

        $this->assertSame(['placeholder' => 'Foo Bar'], $field->attributes);
    }

    /** @test */
    function it_adds_the_value_attribute()
    {
        $field = Field::text('name')->value('Foo Bar');

        $this->assertSame('Foo Bar', $field->value);
    }

    /** @test */
    function it_adds_the_required_if_rule()
    {
        $field = Field::text('name')->requiredIf('status', true);

        $this->assertSame(['required_if:status,1'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_required_unless_rule()
    {
        $field = Field::text('offer_code')->requiredUnless('price', '>=', 500);

        $this->assertSame(['required_unless:price,>=,500'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_required_with_rule()
    {
        $field = Field::text('name')->requiredWith('foo', 'bar', 'john', 'doe');

        $this->assertSame(['required_with:foo,bar,john,doe'], $field->getValidationRules());
    }
    
    /** @test */
    function it_adds_the_required_with_all_rule()
    {
        $field = Field::text('name')->requiredWithAll('foo', 'bar');

        $this->assertSame(['required_with_all:foo,bar'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_required_without_rule()
    {
        $field = Field::text('name')->requiredWithOut('John', 'Doe');

        $this->assertSame(['required_without:John,Doe'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_required_without_all_rule()
    {
        $field = Field::text('name')->requiredWithoutAll('foo', 'bar');

        $this->assertSame(['required_without_all:foo,bar'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_same_rule()
    {
        $field = Field::number('phone')->same('phone2');

        $this->assertSame(['numeric', 'same:phone2'], $field->getValidationRules());
    }
    
    /** @test */
    function it_adds_the_size_rule()
    {
        $field = Field::file('image')->size(1000);

        $this->assertSame(['file', 'size:1000'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_image_rule()
    {
        $field = Field::file('image')->image();

        $this->assertSame(['file', 'image'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_accepted_rule()
    {
        $field = Field::text('name')->accepted();

        $this->assertSame(['accepted'], $field->getValidationRules());
    }
    
    /** @test */
    function it_adds_the_active_url_rule()
    {
        $field = Field::text('name')->activeUrl();

        $this->assertSame(['active_url'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_after_rule()
    {
        $field = Field::text('name')->after('tomorrow');

        $this->assertSame(['after:tomorrow'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_after_or_equal_rule()
    {
        $field = Field::text('name')->afterOrEqual('tomorrow');

        $this->assertSame(['after_or_equal:tomorrow'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_alpha_rule()
    {
        $field = Field::text('name')->alpha();

        $this->assertSame(['alpha'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_alpha_dash_rule()
    {
        $field = Field::text('name')->alphaDash();

        $this->assertSame(['alpha_dash'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_alpha_num_rule()
    {
        $field = Field::text('name')->alphaNum();

        $this->assertSame(['alpha_num'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_array_rule()
    {
        $field = Field::text('name')->array();

        $this->assertSame(['array'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_bail_rule()
    {
        $field = Field::text('name')->bail();

        $this->assertSame(['bail'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_before_rule()
    {
        $field = Field::text('name')->before('tomorrow');

        $this->assertSame(['before:tomorrow'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_before_or_equal_rule()
    {
        $field = Field::text('name')->beforeOrEqual('tomorrow');

        $this->assertSame(['before_or_equal:tomorrow'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_between_rule()
    {
        $field = Field::text('name')->between(1,10);

        $this->assertSame(['between:1,10'], $field->getValidationRules());
    }
    
    /** @test */
    function it_adds_the_boolean_rule()
    {
        $field = Field::text('value')->boolean();

        $this->assertSame(['boolean'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_confirmed_rule()
    {
        $field = Field::email('email')->confirmed();

        $this->assertSame(['email', 'confirmed'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_date_rule()
    {
        $field = Field::text('date')->date();

        return $this->assertSame(['date'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_date_equals_rule()
    {
        $field = Field::text('date')->dateEquals('tomorrow');

        return $this->assertSame(['date_equals:tomorrow'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_date_format_rule()
    {
        $field = Field::text('date')->dateFormat('d-m-Y');

        return $this->assertSame(['date_format:d-m-Y'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_different_rule()
    {
        $field = Field::text('last_name')->different('first_name');

        return $this->assertSame(['different:first_name'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_digits_rule()
    {
        $field = Field::number('age')->digits(2);

        return $this->assertSame(['numeric', 'digits:2'], $field->getValidationRules());
    }
    
    /** @test */
    function it_adds_the_digits_between_rule()
    {
        $field = Field::number('age')->digitsBetween(1,2);

        return $this->assertSame(['numeric', 'digits_between:1,2'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_dimensions_rule()
    {
        $field = Field::file('avatar')->dimensions(['min_width' => 100, 'max_height' => 100]);

        return $this->assertSame(['file', 'dimensions:min_width=100,max_height=100,'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_distinct_rule()
    {
        $field = Field::text('name')->distinct();

        return $this->assertSame(['distinct'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_email_rule()
    {
        $field = Field::text('email')->email();

        return $this->assertSame(['email'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_exists_rule()
    {
        $field = Field::text('foo')->exists('table', 'column');

        return $this->assertSame(['exists:table,column'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_file_rule()
    {
        $field = Field::text('foobar')->file();

        return $this->assertSame(['file'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_filled_rule()
    {
        $field = Field::text('name')->filled();

        return $this->assertSame(['filled'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_gt_rule()
    {
        $field = Field::text('name')->gt('field');

        return $this->assertSame(['gt:field'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_gte_rule()
    {
        $field = Field::text('name')->gte('field');

        return $this->assertSame(['gte:field'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_in_rule()
    {
        $field = Field::text('name')->in('first-zone', 'second-zone');

        return $this->assertSame(['in:first-zone,second-zone'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_in_array_rule()
    {
        $field = Field::text('name')->inArray('value');

        return $this->assertSame(['in_array:value'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_integer_rule()
    {
        $field = Field::text('dni')->integer();

        return $this->assertSame(['integer'], $field->getValidationRules());
    }
    
    /** @test */
    function it_adds_the_ip_rule()
    {
        $field = Field::text('ip')->ip();

        return $this->assertSame(['ip'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_ipv4_rule()
    {
        $field = Field::text('ip')->ipv4();

        return $this->assertSame(['ipv4'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_ipv6_rule()
    {
        $field = Field::text('ip')->ipv6();

        return $this->assertSame(['ipv6'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_json_rule()
    {
        $field = Field::text('data')->json();

        return $this->assertSame(['json'], $field->getValidationRules());
    }
    
    /** @test */
    function it_adds_the_lt_rule()
    {
        $field = Field::text('data')->lt('field');

        return $this->assertSame(['lt:field'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_lte_rule()
    {
        $field = Field::text('data')->lte('field');

        return $this->assertSame(['lte:field'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_mimetypes_rule()
    {
        $field = Field::text('data')->mimetypes('video/avi', 'video/mpeg');

        return $this->assertSame(['mimetypes:video/avi,video/mpeg'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_mimes_rule()
    {
        $field = Field::text('data')->mimes('mp3', 'mp4', 'avi');

        return $this->assertSame(['mimes:mp3,mp4,avi'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_not_in_rule()
    {
        $field = Field::text('name')->notIn('first-zone', 'second-zone');

        return $this->assertSame(['not_in:first-zone,second-zone'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_not_regex_rule()
    {
        $field = Field::text('name')->notRegex('.{6,}');

        $this->assertSame(['not_regex:/.{6,}/'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_numeric_rule()
    {
        $field = Field::text('number')->numeric();

        $this->assertSame(['numeric'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_present_rule()
    {
        $field = Field::text('name')->present();

        $this->assertSame(['present'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_string_rule()
    {
        $field = Field::text('name')->string();

        $this->assertSame(['string'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_the_timezone_rule()
    {
        $field = Field::text('date')->timezone();

        $this->assertSame(['timezone'], $field->getValidationRules());
    }
    
    /** @test */
    function it_adds_the_unique_rule()
    {
        $field = Field::text('name')->unique('users', 'name');

        $this->assertSame(['unique:users,name'], $field->getValidationRules());
    }

    /** @test */
    function it_adds_ignore_in_unique_rule()
    {
        $field = Field::text('name')->unique('users', 'name')->ignore(1, 'user_id');

        $this->assertSame('unique:users,name,"1",user_id', (string) $field->getValidationRules()[0]);
    }

    /** @test */
    function it_cannot_use_method_ignore()
    {
        $this->expectException('Exception');

        Field::text('name')->ignore(1, 'user_id');
    }

    /** @test */
    function it_adds_the_url_rule()
    {
        $field = Field::text('page')->url();

        $this->assertSame(['url'], $field->getValidationRules());
    }

    /** @test */
    function it_delete_all_rules_when_call_ifis_method_and_not_pass()
    {
        $field = Field::text('name')->required()->ifIs('foobar');

        $this->assertSame([], $field->getValidationRules());
    }

    /** @test */
    function it_delete_all_rules_when_call_ifguest_method_and_not_pass()
    {
        $this->actingAs($this->aUser());

        $field = Field::text('name')->required()->ifGuest();

        $this->assertSame([], $field->getValidationRules());
    }

    /** @test */
    function it_delete_all_rules_when_call_ifauth_method_and_not_pass()
    {
        $field = Field::text('name')->required()->ifAuth();

        $this->assertSame([], $field->getValidationRules());
    }

    /** @test */
    function it_delete_all_rules_when_call_ifcan_method_and_not_pass()
    {
        $this->actingAs($this->aUser());

        Gate::define('edit-all', function ($user) {
            return false;
        });

        $field = Field::text('name')->required()->ifCan('edit-all');

        $this->assertSame([], $field->getValidationRules());
    }

    /** @test */
    function it_deletes_all_the_rules()
    {
        $field = Field::number('code')->min(1)->max(10)->required()->disableRules();

        $this->assertSame([], $field->getValidationRules());
    }

    /** @test */
    function it_deletes_a_specific_rule()
    {
        $field = Field::email('email')->min(10)->required()->disableRules('required', 'min');

        $this->assertSame(['email'], $field->getValidationRules());

        $field = Field::email('email')->min(10)->required()->disableRules(['min', 'required']);

        $this->assertSame(['email'], $field->getValidationRules());
    }

    /** @test */
    function unauthorized_fields_dont_retrieve_rules()
    {
        //FIXME: this should not be checked in the field level but in the collection.

        $this->actingAs($this->aUser());

        Gate::define('edit-all', function ($user) {
            return true;
        });

        $field = Field::text('name')->required()->ifCannot('edit-all');

        $this->assertSame([], $field->getValidationRules());
    }
}
