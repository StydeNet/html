<?php

namespace Styde\Html\Tests;

use Illuminate\Support\Facades\Artisan;

class MenuMakeCommandTest extends TestCase
{
    /** @test */
    function it_can_run_the_make_command()
    {
        $this->cleanDirectory();

        $result = Artisan::call('make:menu', ['name' => 'UserMenu']);

        $this->assertDirectoryExists(app_path('Menus'));
        $this->assertFileExists(app_path('Menus/UserMenu.php'));
    }

    protected function rmFile($filename)
    {
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    protected function cleanDirectory()
    {
        $this->rmFile(app_path('Menus/UserMenu.php'));
        if (is_dir(app_path('Http/Menus'))) {
            rmdir(app_path('Http/Menus'));
        }
    }
}
