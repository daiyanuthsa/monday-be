<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name : The name of the service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $this->createServiceDirectory();
        $this->createService($name);
        $this->info("Service {$name}Service.php created successfully!"); // Success message
        return 0; // Indicate success
    }
    
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
     * Create the Services directory if it doesn't exist.
     */
    protected function createServiceDirectory()
    {
        $path = app_path('Services'); // Path to app/Services
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
    protected function getStub()
    {
        // You'll create these stub files in the next step
        return file_get_contents(resource_path("stubs/service.stub"));
    }

    protected function createService($name)
    {
        $template = str_replace(
            ['{{name}}', '{{lowername}}'], // Placeholder in the stub
            [$name, strtolower($name)],      // Value to replace with
            $this->getStub() // Get the interface stub content
        );

        $filename = "{$name}Service.php";
        $path = app_path("Services/{$filename}"); // Path to the new service file

        if ($this->files->exists($path)) {
            $this->error("Service {$filename} already exists!"); // Error if file exists
            return;
        }

        $this->files->put($path, $template); // Create and write to the file
        $this->info("Service {$filename} created.");
    }
}
