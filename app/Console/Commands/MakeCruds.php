<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Providers\CrudControllerGenerator;
use App\Providers\CrudViewGenerator;
use App\Providers\NavigationUpdater;
use App\Providers\RouteUpdater;

use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Input\InputArgument;

class MakeCruds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:cruds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Livewire 3 CRUD with controllers and views (without table creation).';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('CRUD Generator Started!');

        try {
            $model = $this->promptForModel();
            $controllerPath = $this->promptForControllerPath();
            $viewsPath = $this->promptForViewsPath();

            $controllerClass = str_replace('/', '\\', ucfirst($controllerPath)) . '\\' . $model . 'Controller';
            $controllerFile = base_path($controllerPath . '/' . $model . 'Controller.php');
            $viewFile = $viewsPath . '/' . $model . '-cruds.blade.php';

            $this->generateController($model, $controllerPath, $controllerFile);
            $this->generateView($model, $viewsPath, $viewFile);
            $this->updateNavigation($model, $viewsPath);
            $this->updateRoutes($model, $controllerClass);

            $this->info("🎉 ✨ CRUD generation completed for $model. 🚀 🎯");

            return Command::SUCCESS;

        } catch (Exception $e) {
            $this->error("🚨 An error occurred: " . $e->getMessage());
            Log::error("Error in MakeCruds command: " . $e->getMessage());

            return Command::FAILURE;
        }
    }

    /**
     * Prompts the user to select a model from available models in the app/Models directory.
     *
     * @return string
     * @throws Exception
     */
    private function promptForModel(): string
    {
        $models = $this->getAvailableModels();

        if (empty($models)) {
            throw new Exception('No models found in the app/Models directory.  Please create at least one model before running this command.');
        }

        return $this->choice('Select a model to generate CRUD for:', $models);
    }

    /**
     * Retrieves a list of available models from the app/Models directory.
     *
     * @return array
     */
    private function getAvailableModels(): array
    {
        return collect(File::files(app_path('Models')))
            ->map(fn ($file) => $file->getFilenameWithoutExtension())
            ->toArray();
    }

    /**
     * Prompts the user to enter the controller path.
     *
     * @return string
     */
    private function promptForControllerPath(): string
    {
        return $this->ask('Enter the controller path (default: app/Livewire/Backend/)', 'app/Livewire/Backend');
    }

    /**
     * Prompts the user to enter the views path.
     *
     * @return string
     */
    private function promptForViewsPath(): string
    {
        return $this->ask('Enter the views path (default: resources/views/backend/)', 'resources/views/backend');
    }

    /**
     * Generates the controller file.
     *
     * @param string $model
     * @param string $controllerPath
     * @param string $controllerFile
     * @return void
     */
    private function generateController(string $model, string $controllerPath, string $controllerFile): void
    {
        try {
            $this->generateFile(
                $controllerFile,
                fn () => (new CrudControllerGenerator())->generate($model, $controllerPath),
                "🔥 Controller already exists at $controllerFile.",
                "Generating controller for $model...",
                "✅ Controller generated successfully for $model.\n"
            );
        } catch (Exception $e) {
            throw new Exception("Failed to generate controller: " . $e->getMessage());
        }
    }

    /**
     * Generates the view file.
     *
     * @param string $model
     * @param string $viewsPath
     * @param string $viewFile
     * @return void
     */
    private function generateView(string $model, string $viewsPath, string $viewFile): void
    {
        try {
            $this->generateFile(
                $viewFile,
                fn () => (new CrudViewGenerator())->generate($model, $viewsPath),
                "🔥 View already exists at $viewFile.",
                "Generating view for $model...",
                "✅ View generated successfully for $model.\n"
            );
        } catch (Exception $e) {
            throw new Exception("Failed to generate view: " . $e->getMessage());
        }
    }

    /**
     * Updates the navigation file.
     *
     * @param string $model
     * @param string $viewsPath
     * @return void
     */
    private function updateNavigation(string $model, string $viewsPath): void
    {
        try {
            $navigationUpdater = new NavigationUpdater();
            $result = $navigationUpdater->update($model, $viewsPath);

            if ($result) {
                $this->info("✅ Navigation updated successfully for $model.");
            } else {
                $this->info("⚠️ Navigation update skipped or failed for $model.");
            }
        } catch (Exception $e) {
            Log::error("Navigation update failed: " . $e->getMessage());
            $this->info("🚨 Navigation update failed. See logs for details.");
        }
    }

    /**
     * Updates the routes file.
     *
     * @param string $model
     * @param string $controllerClass
     * @return void
     */
    private function updateRoutes(string $model, string $controllerClass): void
    {
        try {
            $routeUpdater = new RouteUpdater();
            $result = $routeUpdater->addRoute($model, $controllerClass);

            if ($result) {
                $this->info("✅ Route added successfully for $model.");
            } else {
                $this->info("⚠️ Route already exists or update failed for $model.");
            }
        } catch (Exception $e) {
            Log::error("Route update failed: " . $e->getMessage());
            $this->info("🚨 Route update failed. See logs for details.");
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
     * @return void
     */
    protected function generateFile(string $filePath, callable $generator, string $existsMessage, string $generateMessage, string $successMessage): void
    {
        if (file_exists($filePath)) {
            $this->info($existsMessage);
            if ($this->confirm('Do you want to replace it?', true)) {
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