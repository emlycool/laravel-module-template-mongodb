<?php

namespace Template\Console\Commands;

use Illuminate\Foundation\Console\RequestMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class CustomRequestMakeCommand extends RequestMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:ml-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form request class in modules folder';



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
        return $this->rootNamespace().$this->option('module').'\\Requests';
    }

    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        return base_path('modules').'/'.str_replace('\\', '/', $name).'.php';
    }

}
