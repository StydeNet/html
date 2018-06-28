<?php

namespace Styde\Html\Menu;

use Illuminate\Console\GeneratorCommand;

class MenuMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new styde\html menu class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Menu';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/menu.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Menus';
    }
}
