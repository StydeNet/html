<?php

namespace Styde\Html\Tests;

use Illuminate\Http\Request;
use Illuminate\Http\Testing\FileFactory;
use Illuminate\Support\Facades\{View, Route};
use Styde\Html\Facades\Form;
use Styde\Html\FormBuilder;
use Styde\Html\FormModel;

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
    function it_gets_the_fields_by_name()
    {
        $form = app(FormModel::class);

        $form->text('name');

        $this->assertInstanceOf(FormModel\Field::class, $form->name);
        $this->assertSame('text', $form->name->type);
        $this->assertSame('name', $form->name->name);
    }

    /** @test */
    function can_use_a_customized_template()
    {
        View::addLocation(__DIR__.'/views');

        $formModel = app(FormModel::class)->template('custom-templates/form-model');

        $this->assertHtmlEquals('<p>Custom template</p>', $formModel);
    }

    /** @test */
    function it_returns_the_rules_from_all_fields()
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
    function it_return_all_validated_fields_of_a_form()
    {
        $files = [
            'photo' => $image = (new FileFactory)->image('foo.jpg', 30, 20),
        ];
        $request = Request::create('/', 'GET', [
            'name' => 'Clemir',
            'email' => 'clemir@styde.net',
            'password' => 'secret',
            'password_confirmation' => 'secret',
            "remember_me" => 1,
        ], [], $files);

        $result = app(RegisterForm::class)->validate($request);

        $expect = [
            'name' => 'Clemir',
            'email' => 'clemir@styde.net',
            'password' => 'secret',
            'password_confirmation' => 'secret',
            'photo' => $image,
            "remember_me" => 1,
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
    function it_throws_a_bad_method_call_exception_when_calling_a_non_existing_method()
    {
        $this->expectException('BadMethodCallException');
        $this->expectExceptionMessage('Call to undefined method Styde\Html\FormModel\FieldCollection::badMethod()');

        app(LoginForm::class)->badMethod();
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
        $this->text('name')->required()->disableRules();
        $this->email('email')->required();
        $this->password('password')->confirmed()->min(6)->max(12)->required();
        $this->password('password_confirmation')->min(6)->max(12)->required();
        $this->file('photo')->required()->image()->dimensions(['ratio' => '3/2']);
        $this->checkbox('remember_me')->required();
    }
}

class UserForm extends FormModel
{
    public function setup()
    {
        $this->text('name');
        $this->email('email');
    }

    public function creationSetup()
    {
        $this->submit('Create user');
    }

    public function updateSetup()
    {
        $this->submit('Update user');
    }
}
