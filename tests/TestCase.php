<?php

namespace Styde\Html\Tests;

use Styde\Html\HtmlServiceProvider;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableInterface;

class TestCase extends OrchestraTestCase
{
    use TestHelpers;

    protected function getPackageProviders($app)
    {
        return [HtmlServiceProvider::class];
    }

    protected function aUser()
    {
        return new TestUser(['role' => 'user']);
    }

    protected function anAdmin()
    {
        return new TestUser(['role' => 'admin']);
    }
}

class TestUser extends Model implements AuthenticatableInterface {
    use Authenticatable;

    protected $guarded = [];

    public function isA($role)
    {
        return $this->role === $role;
    }
}