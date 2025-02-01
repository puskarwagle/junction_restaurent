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

        // Define paths for controller and views (as per your requirements)
        $controllerPath = $this->testControllerPath; // Keep controller path as is
        $viewsPath = $this->testViewsPath; // Keep views path as is

        // Generate the fully qualified controller class name
        $controllerClass = str_replace('/', '\\', ucfirst($controllerPath)) . '\\' . $model . 'Controller';
        $controllerFile = base_path($controllerPath . '/' . $model . 'Controller.php');
        $this->info($viewsPath);
        $viewFile = resource_path($viewsPath . '/' . Str::kebab($model) . '-crud.blade.php');

        try {
            // Step 2: Check if the controller exists and ask to replace
            if (file_exists($controllerFile)) {
                $this->info("🔥 Controller already exists at $controllerFile.");
                $confirmController = $this->confirm('Do you want to replace it ❓', false);
                if (!$confirmController) {
                    $this->info('Skipping controller generation.');
                } else {
                    $this->info("Step 1: Generating controller for $model...");
                    $controllerGenerator = new CrudControllerGenerator();
                    $controllerGenerator->generate($model, $controllerPath);
                    $this->info("✅ Controller generated successfully for $model.\n");
                }
            } else {
                $this->info("Step 1: Generating controller for $model...");
                $controllerGenerator = new CrudControllerGenerator();
                $controllerGenerator->generate($model, $controllerPath);
                $this->info("✅ Controller generated successfully for $model.\n");
            }

            // Step 3: Check if the view exists and ask to replace
            if (file_exists($viewFile)) {
                $this->info("🔥 View already exists at $viewFile.");
                $confirmView = $this->confirm('Do you want to replace it ❓', false);
                if (!$confirmView) {
                    $this->info('Skipping view generation.');
                } else {
                    $this->info("Step 2: Generating view for $model...");
                    $viewGenerator = new CrudViewGenerator();
                    $viewGenerator->generate($model, $viewsPath);
                    $this->info("✅ View generated successfully for $model.\n");
                }
            } else {
                $this->info("Step 2: Generating view for $model...");
                $viewGenerator = new CrudViewGenerator();
                $viewGenerator->generate($model, $viewsPath);
                $this->info("✅ View generated successfully for $model.\n");
            }

            // Step 4: Update Navigation
            $this->info("Step 3: Updating navigation for $model...");
            $navigationUpdater = new NavigationUpdater();
            $navExists = $navigationUpdater->update($model, $viewsPath);

            if ($navExists) {
                $this->info("🔥 Navigation entry for $model already exists.");
                $confirmNav = $this->confirm('Do you want to replace it ❓', false);
                if (!$confirmNav) {
                    $this->info('Skipping navigation update.');
                } else {
                    $navigationUpdater->update($model, $viewsPath); // Re-run update to overwrite
                    $this->info("✅ Navigation updated successfully for $model.\n");
                }
            } else {
                $this->info("✅ Navigation added successfully for $model.\n");
            }

            // Step 5: Update Routes
            $this->info("Step 4: Updating routes for $model...");
            $routeGenerator = new RouteGenerator();
            $routeExists = $routeGenerator->addRoute($model, $controllerClass);

            if ($routeExists) {
                $this->info("🔥 Route for $model already exists.");
                $confirmRoute = $this->confirm('Do you want to replace it ❓', false);
                if (!$confirmRoute) {
                    $this->info('Skipping route update.');
                } else {
                    $routeGenerator->addRoute($model, $controllerClass); // Re-run update to overwrite
                    $this->info("✅ Route updated successfully for $model.\n");
                }
            } else {
                $this->info("✅ Route added successfully for $model.\n");
            }

            // Final Success Message
            $this->info("🎉 ✨ CRUD generation completed for $model. 🚀 🎯");
        } catch (Exception $e) {
            // Handle any errors that occur during the process
            $this->error("❌ An error occurred 🚨: " . $e->getMessage());
            Log::error("Error in MakeCruds command: " . $e->getMessage());
        }
    }

}