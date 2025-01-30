<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class MakeCruds extends Command
{
    protected $signature = 'make:cruds';
    protected $description = 'Generate Livewire 3 CRUD with controllers and views';

    // Test values for quick testing
    protected $testModel = 'TestCrud';
    protected $testControllerPath = 'app/Livewire/TestCrud';
    protected $testViewsPath = 'resources/views';

    public function handle()
    {
        $this->info('Hey pyaro manis!');

        // Use test values for quick testing
        $models = [$this->testModel];
        $model = $this->testModel;
        $controllerPath = $this->testControllerPath;
        $viewsPath = $this->testViewsPath;

        // Uncomment for interactive input
        /*
        $models = collect(File::files(app_path('Models')))
            ->map(fn ($file) => pathinfo($file->getFilename(), PATHINFO_FILENAME))
            ->all();
        
        if (empty($models)) {
            $this->error('No models found in app/Models');
            return;
        }
        
        $model = $this->choice('Select a model for CRUD:', $models);
        $controllerPath = $this->ask('Enter controller path (default: app/Http/Controllers)') ?: 'app/Http/Controllers';
        $viewsPath = $this->ask('Enter views path (default: resources/views)') ?: 'resources/views';
        */

        try {
            $this->generateController($model, $controllerPath);
            $this->generateView($model, $viewsPath);
            $this->updateNavigation($model, $viewsPath);
        } catch (Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
            Log::error("Error in MakeCruds command: " . $e->getMessage());
        }
    }

/**
 * Update the navigation file with new routes.
 *
 * @param string $model
 * @param string $viewsPath
 */
protected function updateNavigation($model, $viewsPath)
{
    $navFilePath = "$viewsPath/partials/navigation.blade.php"; // Corrected file name
    $routeName = Str::kebab(Str::plural($model)); // e.g., "test-cruds"
    $routeLabel = Str::plural($model); // e.g., "TestCruds"

    // New route link for desktop navigation (with proper indentation and spacing)
    $desktopRouteLink = <<<EOD
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('$routeName.index')" :active="request()->routeIs('$routeName.index')" wire:navigate>
                            {{ __('$routeLabel') }}
                        </x-nav-link>
                    </div>
    EOD;

    // New route link for responsive navigation (with proper indentation and spacing)
    $responsiveRouteLink = <<<EOD
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('$routeName.index')" :active="request()->routeIs('$routeName.index')" wire:navigate>
                    {{ __('$routeLabel') }}
                </x-responsive-nav-link>
            </div>
    EOD;

    if (!File::exists($navFilePath)) {
        $this->error("Navigation file not found at $navFilePath");
        Log::error("Navigation file not found at $navFilePath");
        return;
    }

    // Read the existing content
    $content = File::get($navFilePath);

    // Check if the route already exists
    if (Str::contains($content, "route('$routeName.index')")) {
        $this->info("Route for $routeLabel already exists in the navigation file.");
        return;
    }

    // Add the new route to desktop navigation
    $lastDesktopNavLink = strrpos($content, '</x-nav-link>');
    if ($lastDesktopNavLink !== false) {
        // Find the immediate parent <div> of the last <x-nav-link> by looking backwards from the last <x-nav-link>
        $desktopDivStart = strrpos($content, '<div', $lastDesktopNavLink - strlen($content));
        $desktopDivEnd = strpos($content, '</div>', $lastDesktopNavLink);

        if ($desktopDivStart !== false && $desktopDivEnd !== false) {
            // Insert the new route after the last desktop navigation link's parent <div>
            $content = substr_replace(
                $content,
                $desktopRouteLink,
                $desktopDivEnd + strlen('</div>'),
                0
            );
        }
    }

    // Add two new lines before the new responsive nav link
    $content = substr_replace($content, "\n\n", $lastDesktopNavLink, 0);

    // Add the new route to responsive navigation
    $responsiveNavLinkPattern = '/<x-responsive-nav-link([^>]*:active="[^"]*"[^>]*)>/s';
    preg_match_all($responsiveNavLinkPattern, $content, $matches);

    // If any matching responsive links are found
    if (!empty($matches[0])) {
        $lastResponsiveNavLink = end($matches[0]);

        // Find the position of the last responsive nav link
        $lastResponsiveNavLinkPos = strrpos($content, $lastResponsiveNavLink);

        if ($lastResponsiveNavLinkPos !== false) {
            // Find the immediate parent <div> of the last <x-responsive-nav-link>
            $responsiveDivStart = strrpos($content, '<div', $lastResponsiveNavLinkPos - strlen($content));
            $responsiveDivEnd = strpos($content, '</div>', $lastResponsiveNavLinkPos);

            if ($responsiveDivStart !== false && $responsiveDivEnd !== false) {
                // Insert the new route after the last responsive navigation link's parent <div>
                $content = substr_replace(
                    $content,
                    $responsiveRouteLink,
                    $responsiveDivEnd + strlen('</div>'),
                    0
                );
            }
        }
    }

    // Write the updated content back to the file
    File::put($navFilePath, $content);
    $this->info("Navigation file updated with route for $routeLabel.");
    Log::info("Navigation file updated with route for $routeLabel.");
}

    /**
     * Get fillable attributes from the model.
     *
     * @param string $modelImportPath
     * @return array
     */
    protected function getFillableAttributes($modelImportPath)
    {
        if (!class_exists($modelImportPath)) {
            $this->error("Model $modelImportPath not found.");
            Log::error("Model $modelImportPath not found.");
            return [];
        }

        return (new $modelImportPath)->getFillable();
    }

    /**
     * Get validation rules based on the database schema.
     *
     * @param string $modelImportPath
     * @return array
     */
    protected function getValidationRules($modelImportPath)
    {
        if (!class_exists($modelImportPath)) {
            $this->error("Model $modelImportPath not found.");
            Log::error("Model $modelImportPath not found.");
            return [];
        }

        try {
            $model = new $modelImportPath;
            $table = $model->getTable();
            $rules = [];

            $columns = \DB::select("SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, CHARACTER_MAXIMUM_LENGTH 
                                    FROM information_schema.columns 
                                    WHERE table_schema = DATABASE() 
                                    AND table_name = ?", [$table]);

            foreach ($columns as $column) {
                $name = $column->COLUMN_NAME;
                $type = $column->DATA_TYPE;
                $isNullable = $column->IS_NULLABLE === 'YES';
                $maxLength = $column->CHARACTER_MAXIMUM_LENGTH;

                $ruleSet = $isNullable ? ['nullable'] : ['required'];

                switch ($type) {
                    case 'varchar':
                    case 'char':
                        $ruleSet[] = "string|max:" . ($maxLength ?? 255);
                        break;
                    case 'int':
                    case 'bigint':
                    case 'decimal':
                    case 'float':
                    case 'double':
                        $ruleSet[] = 'numeric';
                        break;
                    case 'tinyint':
                        $ruleSet[] = 'boolean';
                        break;
                    case 'text':
                    case 'longtext':
                        $ruleSet[] = 'string';
                        break;
                    case 'datetime':
                    case 'timestamp':
                        $ruleSet[] = 'date';
                        break;
                }

                if (Str::contains($name, ['image', 'photo', 'file'])) {
                    $ruleSet = ['nullable', 'image', 'max:1024'];
                }

                $rules[$name] = implode('|', $ruleSet);
            }

            return $rules;
        } catch (Exception $e) {
            $this->error("Failed to fetch validation rules: " . $e->getMessage());
            Log::error("Failed to fetch validation rules: " . $e->getMessage());
            return [];
        }
    }

/**
 * Generate a Livewire controller.
 *
 * 
 * @param string $model
 * @param string $controllerPath
 * 
 * 
 * 
 * 
 * 
 */
protected function generateController($model, $controllerPath)
{
    $controllerClass = $model . 'Controller';
    $controllerFile = "$controllerPath/$controllerClass.php";

    $namespaceForThisFile = str_replace('/', '\\', ucfirst($controllerPath));
    $modelImportPath = "App\\Models\\$model";

    $fillables = $this->getFillableAttributes($modelImportPath);
    $properties = implode("\n    ", array_map(fn($f) => "public \$$f;", $fillables));

    $rulesArray = $this->getValidationRules($modelImportPath);
    $rulesString = implode(",\n        ", array_map(fn($k, $v) => "'$k' => '$v'", array_keys($rulesArray), $rulesArray));

    File::ensureDirectoryExists($controllerPath);
    File::put($controllerFile, <<<EOD
<?php

namespace $namespaceForThisFile;

use Livewire\Attributes\Layout;
use $modelImportPath;
use Livewire\Component;
use Livewire\WithFileUploads;
use Exception;
use Illuminate\Support\Facades\Log;

class $controllerClass extends Component
{
    use WithFileUploads;

    $properties

    protected \$rules = [
        $rulesString
    ];

    public function mount()
    {
        Log::info('mount function called');
        try {
            \$this->{Str::camel(Str::plural('$model'))} = $model::all();
        } catch (Exception \$e) {
            Log::error('Failed to fetch ' . Str::plural('$model') . ': ' . \$e->getMessage());
        }
    }

}
EOD);

    $this->info("Controller generated for $model at $controllerFile");
    Log::info("Controller generated for $model at $controllerFile");
}
    /**
     * Generate a Blade view.
     *
     * @param string $model
     * @param string $viewsPath
     */
    protected function generateView($model, $viewsPath)
    {
        $modelkebapi = Str::kebab(Str::plural($model)); // e.g., "menu-items"
        $viewFile = "$viewsPath/{$modelkebapi}-cruds.blade.php";

        // Ensure the directory exists
        File::ensureDirectoryExists($viewsPath);

        // Check if the view file already exists
        if (File::exists($viewFile)) {
            $this->info("View for $model already exists at $viewFile.");
            return;
        }

        // Generate the view content
        File::put($viewFile, <<<EOD
        <h1>Hey pyaromanis</h1>
    EOD);

        $this->info("View generated for $model at $viewFile");
        Log::info("View generated for $model at $viewFile");
    }
}