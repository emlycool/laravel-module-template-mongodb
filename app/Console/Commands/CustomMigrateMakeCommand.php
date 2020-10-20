<?php

namespace Template\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Support\Facades\Artisan;

class CustomMigrateMakeCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'make:ml-migration {name : The name of the migration}
        {--M|module= :  Generate a class in Template custom name space}
        {--create= : The table to be created}
        {--table= : The table to migrate}
        {--path= : The location where the migration file should be created}
        {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
        {--fullpath : Output the full path of the migration}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = ' Create a new controller class for Template modules';



    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $options = $this->options();
        $arguments = $this->arguments();

        if($options['module']){
            $arguments['--path'] = "/modules/{$options['module']}/migrations";
        }
        foreach($options as $key => $value){
           if($key !== 'module'){
               if($value){
                   $arguments['--'.$key] = $value;
               }
           }
        }

       return Artisan::call('make:migration', $arguments,$this->output);
    }
}
