<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
class GenerateRepositoryFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'make:repository-files {name : The name of the model}';

     protected $signature = 'make:files
                            {name : The name of the model}
                            {--repository : Generate Repository Files}
                            {--request : Generate a Request}
                            {--migration : Generate a migration}
                            {--model : Generate a Model}
                            {--seeder : Generate a seeder}
                            {--view : Generate a view}
                            {--route : Generate a Route}
                            {--all : Generate all files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description(LivewireComponent, Interface, Repository, Request)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelName = $this->argument('name');

        if ($this->option('all')) {
                    // Generate Interface
                $this->generateInterface($modelName);

                // Generate Repository
                $this->generateRepository($modelName);

                // Generate Livewire Component
                $this->generateLivewireComponent($modelName);

                // Generate Request
                $this->generateRequest($modelName);

                // Generate Livewire Blade File
                $this->livewireBlade($modelName);

                // Generate Model File
                $this->model($modelName);

                // Generate Migration File
                $this->generateMigration($modelName);

                // Generate Seeder File
                $this->generateSeeder($modelName);
                // Routes
                $this->generateRoutes($modelName);
        }
        if ($this->option('repository')) {
            // Generate Interface
            $this->generateInterface($modelName);

            // Generate Repository
            $this->generateRepository($modelName);

            // Generate Livewire Component
            $this->generateLivewireComponent($modelName);

            // Generate Livewire Blade File
            $this->livewireBlade($modelName);
        }

        if ($this->option('request')) {
            // Generate Request
            $this->generateRequest($modelName);
        }

        if ($this->option('model')) {
            // Generate Model File
            $this->model($modelName);
        }

        if ($this->option('migration')) {
            // Generate Migration File
            $this->generateMigration($modelName);
        }

        if ($this->option('seeder')) {
             // Generate Seeder File
            $this->generateSeeder($modelName);
        }
        if ($this->option('view')) {
             // Generate Livewire Blade File
            $this->livewireBlade($modelName);
        }
        if ($this->option('route')) {
            $this->generateRoutes($modelName);
        }



        $this->info("Files for $modelName generated successfully!");
    }

    protected function generateInterface($modelName)
    {
        $interfacePath = app_path("Repositories/Interfaces/{$modelName}RepositoryInterface.php");

        if (!File::exists(dirname($interfacePath))) {
            File::makeDirectory(dirname($interfacePath), 0755, true);
        }

        $stub = File::get(__DIR__ . '/stubs/interface.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);

        File::put($interfacePath, $stub);
    }

    protected function generateRepository($modelName)
    {
        $repositoryPath = app_path("Repositories/Files/{$modelName}Repository.php");

        if (!File::exists(dirname($repositoryPath))) {
            File::makeDirectory(dirname($repositoryPath), 0755, true);
        }

        $stub = File::get(__DIR__ . '/stubs/repository.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);

        File::put($repositoryPath, $stub);
    }

    protected function generateLivewireComponent($modelName)
    {
        $livewireComponentPath = app_path("Livewire/{$modelName}s.php");

        if (!File::exists(dirname($livewireComponentPath))) {
            File::makeDirectory(dirname($livewireComponentPath), 0755, true);
        }

        $stub = File::get(__DIR__ . '/stubs/livewireComponent.stub');
        $stub = str_replace(['{{ModelName}}','{{modelName}}'], [$modelName,Str::camel($modelName)], $stub);

        File::put($livewireComponentPath, $stub);
    }

    protected function generateRequest($modelName)
    {
        $requestPath = app_path("Http/Requests/{$modelName}Request.php");

        if (!File::exists(dirname($requestPath))) {
            File::makeDirectory(dirname($requestPath), 0755, true);
        }

        $stub = File::get(__DIR__ . '/stubs/request.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);

        File::put($requestPath, $stub);
    }
    // livewireBlade
    public function livewireBlade($modelName){
         $livewireBladePath = resource_path("views/livewire/{$modelName}s.blade.php");

        if (!File::exists(dirname($livewireBladePath))) {
            File::makeDirectory(dirname($livewireBladePath), 0755, true);
        }

        $stub = File::get(__DIR__ . '/stubs/livewireBlade.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);

        File::put($livewireBladePath, $stub);
    }

    // Model
    public function model($modelName){
        $modelPath = app_path("Models/{$modelName}.php");

        if (!File::exists(dirname($modelPath))) {
            File::makeDirectory(dirname($modelPath), 0755, true);
        }

        $stub = File::get(__DIR__ . '/stubs/model.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);

        File::put($modelPath, $stub);
    }

    // make migrateion
    public function generateMigration($modelName)
    {
        // Convert model name to proper table name (lowercase, plural)
        $tableName = Str::plural(Str::snake($modelName));

        $migrationName = "create_{$tableName}_table";
        $migrationPath = database_path("migrations/".date('Y_m_d_His')."_".$migrationName.".php");

        // Get stub content
        $stub = File::get(__DIR__.'/stubs/migration.stub');

        // Replace placeholders
        $stub = str_replace('{{tableName}}', $tableName, $stub);
        $stub = str_replace('{{ModelName}}', $modelName, $stub); // Keep model name for model reference if needed

        File::put($migrationPath, $stub);

        return $migrationPath;
    }
    public function generateSeeder($modelName)
    {
        $seederName = "{$modelName}Seeder";
        $seederPath = database_path("seeders/{$seederName}.php");

        $stub = File::get(__DIR__.'/stubs/seeder.stub');
        $stub = str_replace('{{ModelName}}', $modelName, $stub);
        $stub = str_replace('{{modelName}}', strtolower($modelName), $stub);

        File::put($seederPath, $stub);

        return $seederPath;
    }

    protected function generateRoutes($modelName)
    {
        $routeName = Str::plural(Str::kebab($modelName));
        $componentName = "{$modelName}s";
        $componentClass = "App\Livewire\\{$componentName}";

        // Check if component exists, create it if not
        if (!File::exists(app_path("Livewire/{$componentName}.php"))) {
            $this->generateLivewireComponent($modelName);
        }

        $routesFile = base_path('routes/web.php');
        $existingContent = File::get($routesFile);

        // Check if routes already exist
        if (Str::contains($existingContent, "Route::get('{$routeName}', {$componentName}::class)")) {
            $this->warn("Routes for {$modelName} already exist");
            return;
        }

        // 1. Handle the use statement - check for grouped imports first
        $useStatement = "use {$componentClass};";
        $groupedUsePattern = '/use App\\\\Livewire\\\\\{(.*?)\};/s';

        if (preg_match($groupedUsePattern, $existingContent, $matches)) {
            // Add to existing grouped imports
            $existingImports = $matches[1];
            if (!Str::contains($existingImports, $componentName)) {
                $newImports = trim($existingImports) . ",\n    {$componentName}";
                $existingContent = str_replace(
                    $matches[0],
                    "use App\Livewire\{\n    {$newImports}\n};",
                    $existingContent
                );
            }
        } elseif (!Str::contains($existingContent, $useStatement)) {
            // Add as separate use statement
            $existingContent = preg_replace(
                '/^<\?php\s+/',
                "<?php\n\n{$useStatement}\n",
                $existingContent
            );
        }

        // 2. Find the existing auth middleware group and append the new route
        if (Str::contains($existingContent, "Route::middleware(['auth'])->group(function () {")) {
            // Insert inside existing auth group
            $existingContent = preg_replace(
                '/(Route::middleware\(\[\'auth\'\]\)->group\(function \(\) \{\n)(.*?)(\n\s*\}\);)/s',
                "$1$2    Route::get('{$routeName}', {$componentName}::class)->name('{$routeName}');\n$3",
                $existingContent
            );
        } else {
            // Create new auth group if none exists (fallback)
            $routeSection = "\n\n// {$modelName} Routes\nRoute::middleware(['auth'])->group(function () {\n    Route::get('{$routeName}', {$componentName}::class)->name('{$routeName}');\n});";
            $existingContent .= $routeSection;
        }

        File::put($routesFile, $existingContent);
        $this->info("Route for {$modelName} added to auth middleware group");
    }
}
