<?php
namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use App\Providers\CrudControllerGenerator;
use App\Providers\CrudViewGenerator;
use App\Providers\NavigationUpdater;
use App\Providers\RouteUpdater;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class MakeCruds extends Command
{
    protected $signature = 'make:cruds';
    protected $description = 'Generate Livewire 3 CRUD with controllers and views';

    // Test values for quick testing
    protected string $testModel = 'SiteSettings';
    protected string $testControllerPath = 'app/Livewire/SiteSettings';
    protected string $testViewsPath = 'resources/views/backend/';

    public function handle(): void
    {
        $this->info('Hey pyaro manis!');
    
        // Get a list of available models
        $models = collect(\File::files(app_path('Models')))
            ->map(fn($file) => $file->getFilenameWithoutExtension())
            ->toArray();
    
        // Step 1: Ask the user to choose a model
        $model = $this->choice('Select a model to generate CRUD for:', $models);
    
        // Define paths for controller and views
        $controllerPath = $this->ask('Enter the controller path (default: app/Livewire/Backend/)', 'app/Livewire/Backend');
        $viewsPath = $this->ask('Enter the views path (default: resources/views/backend/)', 'resources/views/backend');
        
        // Generate the fully qualified controller class name
        $controllerClass = str_replace('/', '\\', ucfirst($controllerPath)) . '\\' . $model . 'Controller';
        $controllerFile = base_path($controllerPath . '/' . $model . 'Controller.php');
        $viewFile = ($viewsPath . '/' . $model . '-cruds.blade.php');
    
        try {
            // Step 2: Check if the model exists in the database
            $modelClass = 'App\\Models\\' . $model;
            $tableName = (new $modelClass)->getTable();
    
            if (!\Schema::hasTable($tableName)) {
                $this->info("Table for model $model does not exist. Creating table...");
    
                // Get fillable attributes from the model
                $reflectionClass = new \ReflectionClass($modelClass);
                $fillable = $reflectionClass->getProperty('fillable')->getValue(new $modelClass);
    
                // Create the table
                \Schema::create($tableName, function ($table) use ($fillable) {
                    $table->id();
                    foreach ($fillable as $column) {
                        $table->string($column)->nullable();
                    }
                    $table->timestamps();
                });
    
                $this->info("✅ Table created successfully for $model.");
            } else {
                $this->info("✅ Table already exists for $model.");
            }
    
            // Step 3: Generate Controller
            $this->generateFile(
                $controllerFile,
                fn() => (new CrudControllerGenerator())->generate($model, $controllerPath),
                "🔥 Controller already exists at $controllerFile.",
                "Generating controller for $model...",
                "✅ Controller generated successfully for $model.\n"
            );
    
            // Step 4: Generate View
            $this->generateFile(
                $viewFile,
                fn() => (new CrudViewGenerator())->generate($model, $viewsPath),
                "🔥 View already exists at $viewFile.",
                "Generating view for $model...",
                "✅ View generated successfully for $model.\n"
            );
    
            // Step 5: Check and update navigation
            $navigationUpdater = new NavigationUpdater();
            $result = $navigationUpdater->update($model, $viewsPath);
    
            if ($result) {
                $this->info("✅ Navigation updated successfully for $model.");
            } else {
                $this->info("⚠️ Navigation update skipped or failed for $model.");
            }
    
            // Step 6: Check and update routes
            $routeUpdater = new RouteUpdater();
            $result = $routeUpdater->addRoute($model, $controllerClass);
    
            if ($result) {
                $this->info("✅ Route added successfully for $model.");
            } else {
                $this->info("⚠️ Route already exists or update failed for $model.");
            }
    
            // Final Success Message
            $this->info("🎉 ✨ CRUD generation completed for $model. 🚀 🎯");
        } catch (Exception $e) {
            // Handle any errors that occur during the process
            $this->error("✨ An error occurred 🚨: " . $e->getMessage());
            Log::error("Error in MakeCruds command: " . $e->getMessage());
        }
    }
    
    /**
     * Helper function to generate a file if it doesn't exist or if the user confirms replacement.
     *
     * @param string $filePath The path to the file.
     * @param callable $generator The function to generate the file.
     * @param string $existsMessage The message to display if the file already exists.
     * @param string $generateMessage The message to display when generating the file.
     * @param string $successMessage The message to display after successful generation.
     */
    protected function generateFile(string $filePath, callable $generator, string $existsMessage, string $generateMessage, string $successMessage): void
    {
        if (file_exists($filePath)) {
            $this->info($existsMessage);
            if ($this->confirm('Do you want to replace it?', false)) {
                $this->info($generateMessage);
                $generator();
                $this->info($successMessage);
            } else {
                $this->info("Skipping file generation for $filePath.\n");
            }
        } else {
            $this->info("$filePath file does not exist. $generateMessage");
            $generator();
            $this->info($successMessage);
        }
    }
}