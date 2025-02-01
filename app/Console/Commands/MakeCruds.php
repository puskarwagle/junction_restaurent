<?php
namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use App\Providers\CrudControllerGenerator;
use App\Providers\CrudViewGenerator;
use App\Providers\NavigationUpdater;
use App\Providers\RouteGenerator;

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
    protected string $testViewsPath = 'resources/views';

    public function handle(): void
    {
        $this->info('Hey pyaro manis!');

        // Get a list of available models
        $models = collect(\File::files(app_path('Models')))
            ->map(fn($file) => $file->getFilenameWithoutExtension())
            ->toArray();

        if (empty($models)) {
            $this->error('No models found in the App\Models directory.\n');
            return;
        }

        // Step 1: Ask the user to choose a model
        $model = $this->choice('Select a model to generate CRUD for:', $models);

        // Define paths for controller and views
        // $controllerPath = $this->testControllerPath;
        // $viewsPath = $this->testViewsPath;
        $controllerPath = $this->ask('Enter the controller path (default: app/Livewire/)', 'app/Livewire/');
        $viewsPath = $this->ask('Enter the views path (default: resources/views)', 'resources/views');

        // Generate the fully qualified controller class name
        $controllerClass = str_replace('/', '\\', ucfirst($controllerPath)) . '\\' . $model . 'Controller';
        $controllerFile = base_path($controllerPath . '/' . $model . 'Controller.php');
        $viewFile = ($viewsPath . '/' . $model . '-cruds.blade.php');

        try {
            // Step 2: Check and generate controller
            $controllerGenerator = new CrudControllerGenerator();
            if (file_exists($controllerFile)) {
                $this->info("🔥 Controller already exists at $controllerFile.");
                if ($this->confirm('Do you want to replace it?', false)) {
                    $this->info("Generating controller for $model...");
                    $controllerGenerator->generate($model, $controllerPath);
                    $this->info("✅ Controller generated successfully for $model.\n");
                } else {
                    $this->info("Skipping controller generation for $model.\n");
                }
            } else {
                $this->info("Generating controller for $model...");
                $controllerGenerator->generate($model, $controllerPath);
                $this->info("✅ Controller generated successfully for $model.\n");
            }


            // Step 3: Check and generate view
            $viewGenerator = new CrudViewGenerator();
            if (file_exists($viewFile)) {
                $this->info("🔥 View already exists at $viewFile.");
                if ($this->confirm('Do you want to replace it?', false)) {
                    $this->info("Generating view for $model...");
                    $viewGenerator->generate($model, $viewsPath); // Call the view generator
                    $this->info("✅ View generated successfully for $model.\n");
                } else {
                    $this->info("Skipping view generation for $model.\n");
                }
            } else {
                $this->info("$viewFile file does not exist. Generating view for $model...");
                $viewGenerator->generate($model, $viewsPath); // Call the view generator
                $this->info("✅ View generated successfully for $model.\n");
            }


            // Step 4: Check and update navigation
            $navigationUpdater = new NavigationUpdater();
            $navFilePath = "$viewsPath/livewire/layout/navigation.blade.php";
            if (file_exists($navFilePath)) {
                $this->info("🔥 Navigation file already exists at $navFilePath.");
                if ($this->confirm('Do you want to update it?', false)) {
                    $this->info("Updating navigation for $model...");
                    $navigationUpdater->update($model, $viewsPath);
                    $this->info("✅ Navigation updated successfully for $model.\n");
                } else {
                    $this->info("Skipping navigation update for $model.\n");
                }
            } else {
                $this->info("Navigation file does not exist. Skipping navigation update for $model.\n");
            }

            // Step 5: Check and update routes
            $routeUpdater = new RouteUpdater();
            $webFilePath = base_path('routes/web.php');
            if (file_exists($webFilePath)) {
                $this->info("🔥 Routes file already exists at $webFilePath.");
                if ($this->confirm('Do you want to update it?', false)) {
                    $this->info("Updating routes for $model...");
                    $routeUpdater->addRoute($model, $controllerClass);
                    $this->info("✅ Route added successfully for $model.\n");
                } else {
                    $this->info("Skipping route update for $model.\n");
                }
            } else {
                $this->info("Routes file does not exist. Skipping route update for $model.\n");
            }

            // Final Success Message
            $this->info("🎉 ✨ CRUD generation completed for $model. 🚀 🎯");
        } catch (Exception $e) {
            // Handle any errors that occur during the process
            $this->error("✨ An error occurred 🚨: " . $e->getMessage());
            Log::error("Error in MakeCruds command: " . $e->getMessage());
        }
    }
}