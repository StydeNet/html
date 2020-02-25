<?php

namespace Styde\Html\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\ValidationException;
use Styde\Html\FormModel;
use Illuminate\Http\Request;
use Styde\Html\Facades\Form;
use Illuminate\Http\Testing\FileFactory;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class FormModelTest extends TestCase
{
    /** @test */
    function it_builds_a_login_form()
    {
        Route::post('login', ['as' => 'login']);

        $loginForm = app(LoginForm::class);

        $this->assertTemplateMatches('form-model/login-form', $loginForm);
    }

    /** @test */
    function form_can_accept_files()
    {
        $form = app(TestFormModel::class);

        $form->acceptFiles();

        $this->assertHtmlEquals(
            '<form method="post" enctype="multipart/form-data"><input type="hidden" name="_token">',
            $form->form->open()
        );
    }

    /** @test */
    function can_use_a_customized_template()
    {
        View::addLocation(__DIR__.'/views');

        $formModel = app(FormModel::class)->template('custom-templates/form-model');

        $this->assertHtmlEquals('<p>Custom template</p>', $formModel);
    }

    /** @test */
    function can_use_a_customized_template_from_the_current_theme()
    {
        View::addLocation(__DIR__.'/views');

        $formModel = app(FormModel::class)->template('@custom-form-model');

        $this->assertHtmlEquals('<p>Custom template from theme</p>', $formModel);
    }

    /** @test */
    function it_returns_the_form_model_validation_rules()
    {
        $rules = app(RegisterForm::class)->getValidationRules();

        $expect = [
            'name' => [],
            'email' => ['email', 'required'],
            'password' => ['confirmed', 'min:6', 'max:12', 'required'],
            'password_confirmation' => ['min:6', 'max:12', 'required'],
            'photo' => ['file', 'required', 'image', 'dimensions:ratio=3/2'],
            'remember_me' => ['required']
        ];

        $this->assertSame($expect, $rules);
    }

    /** @test */
    function select_field_does_not_add_an_empty_in_rule()
    {
        $form = app(TestFormModel::class);

        $form->select('parent_id');

        $this->assertCount(0, $form->parent_id->getField()->getValidationRules());
    }

    /** @test */
    function radios_field_does_not_add_an_empty_in_rule()
    {
        $form = app(TestFormModel::class);

        $form->radios('role');

        $this->assertCount(0, $form->role->getField()->getValidationRules());
    }

    /** @test */
    function checkboxes_field_does_not_add_an_empty_in_rule()
    {
        $form = app(TestFormModel::class);

        $form->checkboxes('tags');

        $this->assertCount(0, $form->tags->getField()->getValidationRules());
    }

    /** @test */
    function it_returns_all_validated_fields_of_a_form()
    {
        $files = [
            'photo' => $image = (new FileFactory)->image('foo.jpg', 30, 20),
        ];
        $request = Request::create('/', 'GET', [
            'name' => 'Clemir',
            'email' => 'clemir@styde.net',
            'password' => 'secret',
            'password_confirmation' => 'secret',
            'remember_me' => 1,
        ], [], $files);

        $result = app(RegisterForm::class)->validate($request);

        $expect = [
            'name' => 'Clemir',
            'email' => 'clemir@styde.net',
            'password' => 'secret',
            'password_confirmation' => 'secret',
            'photo' => $image,
            'remember_me' => 1,
        ];

        $this->assertSame($expect, $result);
    }

    /** @test */
    function only_fields_have_rules()
    {
        $form = app(FormModel::class);

        $form->tag('h3', 'title here');
        $form->email('email')->required();

        $expect = [
            'email' => ['email', 'required']
        ];

        $this->assertSame($expect, $form->getValidationRules());
    }

    /** @test */
    function it_builds_an_update_form()
    {
        $userModel = $this->aUserWithData([
            'name' => 'Clemir',
            'email' => 'clemir@styde.net'
        ]);

        $userForm = app(UserForm::class)->model($userModel)->forUpdate();

        $this->assertTemplateMatches('form-model/user-form-for-update', $userForm);
    }

    /** @test */
    function it_builds_a_create_form_with_forCreation_method()
    {
        $userModel = $this->aUserWithData([
            'name' => 'Clemir',
            'email' => 'clemir@styde.net'
        ]);

        $userForm = app(UserForm::class)->forCreation();

        $this->assertTemplateMatches('form-model/user-form-for-creation', $userForm);
    }

    /** @test */
    function it_sets_the_novalidate_attribute()
    {
        Route::post('login', ['as' => 'login']);

        $form = app(LoginForm::class)->novalidate();

        $this->assertTemplateMatches('form-model/form-with-novalidate', $form);
    }

    /** @test */
    function setting_novalidate_in_a_form_model_does_not_change_the_global_novalidate_config()
    {
        Route::post('login', ['as' => 'login']);
        app(LoginForm::class)->novalidate();

        $this->assertHtmlEquals('<form method="get"></form>', Form::get());
    }

    /** @test */
    function novalidate_can_be_deactivated()
    {
        Route::post('login', ['as' => 'login']);

        $form = app(LoginForm::class)->novalidate(false);

        $this->assertTemplateMatches('form-model/login-form', $form);
    }

    /** @test */
    function set_a_model_and_get_the_field_values_from_its_attributes()
    {
        $eloquentModel = new class extends Model {
            public function getNameAttribute()
            {
                return 'Duilio Palacios';
            }
        };

        $form = app(TestFormModel::class);

        $form->model($eloquentModel);

        $form->text('name');

        $this->assertSame('Duilio Palacios', $form->name->getField()->displayValue());
    }

    /** @test */
    function it_throws_a_bad_method_call_exception_when_calling_a_non_existing_method()
    {
        $this->expectException('BadMethodCallException');
        $this->expectExceptionMessage('Call to undefined method Styde\Html\Tests\LoginForm::badMethod()');

        app(LoginForm::class)->badMethod();
    }

    /** @test */
    function adds_strategies_to_email_rule()
    {
        $form = app(EmailForm::class);

        $expectedRules = [
            'email' => ['email:rfc,dns', 'required'],
            'another_email' => ['email:rfc,dns'],
        ];

        $this->assertSame($expectedRules, $form->getValidationRules());
    }

    /** @test */
    function calls_failedValidation_callback_if_validation_fails()
    {
        $form = app(CallbackForm::class);

        $this->assertFalse($form->failedValidationWasCalled);

        try {
            $form->validate(new Request(['name' => null]));
            $this->fail("Validation didn't failed");
        } catch (ValidationException $exception) {
        }

        $this->assertTrue($form->failedValidationWasCalled);
    }

    /** @test */
    function failedValidation_has_a_placeholder_method()
    {
        $form = app(EmailForm::class);

        try {
            $form->validate(new Request(['email' => 'invalid-email']));
            $this->fail("Validation didn't failed");
        } catch (ValidationException $exception) {
            $this->assertTrue(true);
        }
    }
}

class CallbackForm extends FormModel
{
    public $failedValidationWasCalled = false;

    public function setup()
    {
        $this->text('name')->required();
    }

    public function failedValidation(Request $request)
    {
        $this->failedValidationWasCalled = true;
    }
}

class EmailForm extends FormModel
{
    public function setup()
    {
        $this->email('email', ['rfc', 'dns'])->required();
        $this->email('another_email', 'rfc,dns');
    }
}

class LoginForm extends FormModel
{
    public function setup()
    {
        $this->route('login')->role('form');

        $this->email('email');
        $this->password('password');
        $this->checkbox('remember_me');

        $this->submit(trans('auth.login_action'))->classes('btn btn-primary');
        $this->link(url('password/email'), trans('auth.forgot_link'));
    }
}

class RegisterForm extends FormModel
{
    public function setup()
    {
        $this->text('name')->required()->withoutRules();
        $this->email('email')->required();
        $this->password('password')->confirmed()->min(6)->max(12)->required();
        $this->password('password_confirmation')->min(6)->max(12)->required();
        $this->file('photo')->required()->image()->dimensions(['ratio' => '3/2']);
        $this->checkbox('remember_me')->required();
    }
}

class UserForm extends FormModel
{
    public function creationSetup()
    {
        $this->setup();

        $this->submit('Create user');
    }

    public function updateSetup()
    {
        $this->setup();

        $this->submit('Update user');
    }

    public function setup()
    {
        $this->text('name');
        $this->email('email');
    }
}
