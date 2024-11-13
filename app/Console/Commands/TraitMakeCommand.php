<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class TraitMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {name : The name of the trait}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new trait';

    protected $files;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path('Traits/' . $name . '.php');
        if ($this->files->exists($path)) {
            $this->error("Trait '{$name}' already exists!");
            return 1;
        }

       $this->makeDirectory($path);
       $stub = file_get_contents($this->getStub($path));
       $this->files->put($path, str_replace('{{class}}', $name, $stub));
        return 0;
    }

    public function makeDirectory($path)
    {
        $directory = dirname($path);
        if(! $this->files->isDirectory($directory)){
          $this->files->makeDirectory($directory);
        }
    }
    public function getStub($path)
    {
        return __DIR__. "/stubs/trait.stub";
    }
   
}
