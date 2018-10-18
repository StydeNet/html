<?php

namespace Styde\Html\FormModel;

use Illuminate\Console\GeneratorCommand;

class FormMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form model class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Form';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/form.stub';
    }

    /**
     * Get the stub file for the base class
     *
     * @return string
     */
    protected function getBaseFormStub()
    {
        return __DIR__.'/stubs/base-form.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Forms';
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->makeBaseForm();
        parent::handle();
    }

    /**
     * Create base Form class
     *
     * @return void
     */
    protected function makeBaseForm()
    {
        $path = $this->getPath($this->qualifyClass('FormModel'));

        if (! $this->files->exists($path)) {
            // Create app/Http/Forms folder
            $this->makeDirectory($path);
            // Create base Form class
            $stub = $this->files->get($this->getBaseFormStub());
            $this->replaceNamespace($stub, 'FormModel');
            $this->files->put($path, $stub);
        }
    }
}
