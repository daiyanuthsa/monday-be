<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem; // Import Filesystem

class MakeRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name : The name of the repository}'; // Defines 'name' as a required argument

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class and interface';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files; // Property to hold the Filesystem instance

    /**
     * Create a new command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files // Inject Filesystem
     * @return void
     */
    public function __construct(Filesystem $files) // Constructor to inject Filesystem
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
        $name = $this->argument('name'); // Get the 'name' argument

        $this->createRepositoryDirectory();
        $this->createRepositoryInterface($name);
        $this->createRepositoryClass($name);

        $this->info("Repository {$name}Repository.php and interface {$name}RepositoryInterface.php created successfully!"); // Success message
        return 0; // Indicate success
    }

    /**
     * Create the Repositories directory if it doesn't exist.
     */
    protected function createRepositoryDirectory()
    {
        $path = app_path('Repositories'); // Path to app/Repositories
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0755, true); // Create directory if it doesn't exist
        }
    }

    /**
     * Get the stub file for the generator.
     *
     * @param string $type 'interface' or 'class'
     * @return string
     */
    protected function getStub($type)
    {
        // You'll create these stub files in the next step
        return file_get_contents(resource_path("stubs/repository.{$type}.stub"));
    }

    /**
     * Create the repository interface file.
     *
     * @param string $name
     */
    protected function createRepositoryInterface($name)
    {
        $template = str_replace(
            ['{{name}}'], // Placeholder in the stub
            [$name],      // Value to replace with
            $this->getStub('interface') // Get the interface stub content
        );

        $filename = "{$name}RepositoryInterface.php";
        $path = app_path("Repositories/{$filename}"); // Path to the new interface file

        if ($this->files->exists($path)) {
            $this->error("Interface {$filename} already exists!"); // Error if file exists
            return;
        }

        $this->files->put($path, $template); // Create and write to the file
        $this->info("Interface {$filename} created.");
    }

    /**
     * Create the repository class file.
     *
     * @param string $name
     */
    protected function createRepositoryClass($name)
    {
        $template = str_replace(
            ['{{name}}'], // Placeholder in the stub
            [$name],      // Value to replace with
            $this->getStub('class') // Get the class stub content
        );

        $filename = "{$name}Repository.php";
        $path = app_path("Repositories/{$filename}"); // Path to the new class file

        if ($this->files->exists($path)) {
            $this->error("Repository class {$filename} already exists!"); // Error if file exists
            return;
        }

        $this->files->put($path, $template); // Create and write to the file
        $this->info("Repository class {$filename} created.");
    }
}