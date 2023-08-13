<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WebResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:webResources {name}  {--views} {--livewire} {--seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate web resources: dev:webResources {name}  {--views} {--livewire} {--seed} migration, model, controller, request classes, and web route.';
    
    

    // protected $command = 'dev:apiResources';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $livewire = $this->option('livewire');
        $generateViews = $this->option('views');
        $generateSeeder = $this->option('seed');
    
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
            '--api' => true,
        ]);
    
        // Generate request classes
        $this->call('make:request', ['name' => "{$name}/StoreRequest"]);
        $this->call('make:request', ['name' => "{$name}/UpdateRequest"]);
    
        // Generate API routes
        $this->createWebRoutes($name);
    

        // Generate views if the --views option is present
        if ($generateViews) {
            $this->generateViews($name);
        }

        // Generate Livewire component and view if the --livewire option is present
        if ($livewire) {
            $this->call('make:livewire', [
                'name' => "{$name}Component",
            ]);
    
            // Generate the corresponding Livewire view
            $this->generateLivewireView($name);
        }

        // Generate seeder if the --seeder option is present
        if ($generateSeeder) {
            $this->call('make:seeder', [
                'name' => "{$name}Seeder",
            ]);
        }
    
        $this->info('API resources generated successfully.');
    }
    

    protected function createWebRoutes($name)
    {
        $routePath = base_path('routes/web.php');
        $routeContents = file_get_contents($routePath);
    
        // Append web routes for the specified resource
        $routes = <<<EOT
    
    Route::resource('$name', ${name}Controller::class);
    
    EOT;
    
        file_put_contents($routePath, $routeContents . PHP_EOL . $routes);
    }

    protected function generateViews($name)
    {
        $viewsPath = resource_path("views/{$name}");

        // Create the directory for views if it doesn't exist
        if (!is_dir($viewsPath)) {
            mkdir($viewsPath, 0755, true);
            $this->info("Views directory created: {$viewsPath}");
        } else {
            $this->warn("Views directory already exists: {$viewsPath}");
        }

        // Generate index view
        $this->generateViewFile($name, 'index');

        // Generate show view
        $this->generateViewFile($name, 'show');

        // Generate create view
        $this->generateViewFile($name, 'create');

        // Generate edit view
        $this->generateViewFile($name, 'edit');
    }
    protected function generateViewFile($name, $view)
    {
        $viewPath = resource_path("views/{$name}/{$view}.blade.php");

        // Check if the view file already exists
        if (!file_exists($viewPath)) {
            // Create the view file
            file_put_contents($viewPath, '');

            $this->info("View generated: {$name}/{$view}.blade.php");
        } else {
            $this->warn("View already exists: {$name}/{$view}.blade.php");
        }
    }
    
    protected function generateLivewireView($name)
    {
        $viewPath = resource_path("views/livewire/{$name}-component.blade.php");

        // Check if the view file already exists
        if (!file_exists($viewPath)) {
            // Create the Livewire view file
            file_put_contents($viewPath, '');

            $this->info("Livewire view generated: {$name}-component.blade.php");
        } else {
            $this->warn("Livewire view already exists: {$name}-component.blade.php");
        }
    }

}
