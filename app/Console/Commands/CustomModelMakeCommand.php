<?php

namespace Template\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class CustomModelMakeCommand extends ModelMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:ml-model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Eloquent model class in modules folder';




    protected function getOptions()
    {
        $frameworkOptions =  parent::getOptions();



        array_push($frameworkOptions,
            ['module','M',InputOption::VALUE_REQUIRED,'Generate a class in Template custom name space']);

        return $frameworkOptions;
    }

    protected function rootNamespace()
    {
        return "Template\\Modules\\";
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        if(!$this->option('module')){
            $this->error("Module not specified");
            exit();
        }
        return $this->rootNamespace().$this->option('module').'\\Models';
    }


    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        return base_path('modules').'/'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());
        return $this->replaceNamespace($stub, $name)
            ->replaceControllerNameSpace($stub)
            ->replaceClass($stub, $name);
    }

    protected function replaceControllerNameSpace(&$stub){

        $stub = str_replace("Illuminate\\Database\\Eloquent\\Model;\n","Jenssegers\Mongodb\Eloquent\Model;\n", $stub);
        return $this;
    }
}
