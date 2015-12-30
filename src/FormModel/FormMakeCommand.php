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

    protected function getBaseFormStub()
    {
        return __DIR__.'/stubs/base-form.stub';
    }

    public function fire()
    {
        $this->makeBaseForm();
        parent::fire();
    }

    protected function makeBaseForm()
    {
        $path = $this->laravel['path'].'/Http/Forms/Form.php';

        if (! $this->files->exists($path)) {
            // Create app/Http/Forms folder
            $this->makeDirectory($path);
            // Create base Form class
            $stub = $this->files->get($this->getBaseFormStub());
            $this->replaceNamespace($stub, 'Form');
            $this->files->put($path, $stub);
            $this->info('Base form created successfully.');
        }
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = str_replace($this->laravel->getNamespace(), '', $name);

        return $this->laravel['path'].'/Http/Forms/'.str_replace('\\', '/', $name).'.php';
    }

}