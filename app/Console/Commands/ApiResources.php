<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ApiResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:apiResources {name} {--seeder} {--resources}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate API resources: migration, model, controller, request classes, and API route.';
    

    // protected $command = 'dev:apiResources';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $generateSeeder = $this->option('seed');
        $generateResources = $this->option('resources');

        // Generate migration
        $this->call('make:migration', [
            'name' => "create_{$name}_table"
        ]);

        // Generate model
        $this->call('make:model', [
            'name' => $name,
        ]);

        // Generate controller
        $this->call('make:controller', [
            'name' => "{$name}Controller",
        ]);
        // Generate request classes
        $this->call('make:request', ['name' => "{$name}/StoreRequest"]);
        $this->call('make:request', ['name' => "{$name}/UpdateRequest"]);

        
        // Generate API routes for the api command
        if ($this instanceof \App\Console\Commands\ApiResources) {
            $this->createApiRoutes($name);
        }

        // Generate seeder if the --seed or --seeder option is present
        if ($generateSeeder) {
            $this->call('make:seeder', [
                'name' => "{$name}Seeder",
            ]);
        }


        if ($generateResources) {
            // Create an additional API resource class, e.g., "ProductResource"
            $this->call('make:api-resource', [
                'name' => "{$name}Resource",
            ]);
        }
        
        $this->info('API resources generated successfully.');
    }
    

    protected function createApiRoutes($name)
    {
        $routePath = base_path('routes/api.php');
        $routeContents = file_get_contents($routePath);

        // Append API routes for the specified resource
        $routes = <<<EOT

        Route::apiResource('$name', ${name}Controller::class);

        EOT;

        file_put_contents($routePath, $routeContents . PHP_EOL . $routes);
    }

       // Generate API resource class if the --resources option is present


}
