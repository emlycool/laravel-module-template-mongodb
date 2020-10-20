<?php

namespace Template\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CustomControllerMakeCommand extends ControllerMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:ml-controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller class for Template modules';

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */


    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }

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
        return $this->rootNamespace().$this->option('module').'\\Controllers';
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

       $stub = str_replace($this->rootNamespace()."Http\\Controllers\Controller;\n",$this->rootNamespace()."BaseController as Controller;\n", $stub);
        return $this;
    }


}
