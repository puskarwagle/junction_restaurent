<?php
namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Log;

class CrudControllerGenerator
{
    public function generate($model, $controllerPath)
    {
        $controllerClass = $model . 'Controller';
        $controllerFile = "$controllerPath/$controllerClass.php";

        $namespaceForThisFile = str_replace('/', '\\', ucfirst($controllerPath));
        $modelImportPath = "App\\Models\\$model";

        // Ensure the model exists
        if (!class_exists($modelImportPath)) {
            throw new Exception("Model $modelImportPath not found.");
        }

        // Get fillable attributes from the model
        $fillables = (new $modelImportPath)->getFillable();
        $properties = implode("\n    ", array_map(fn($f) => "public \$$f;", $fillables));

        // Generate the rules string with empty rules for the user to fill in
        $rulesString = implode(",\n        ", array_map(fn($f) => "'$f' => ''", $fillables));

        // Generate the view name dynamically
        $viewName = $model;
        $layoutName = 'layouts.app'; // Default layout, can be customized if needed

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
            // Dynamically set the property name (e.g., "testCruds" for "TestCrud")
            \$propertyName = lcfirst('$model') . 's';
            
            // Fetch all records from the model
            \$this->{\$propertyName} = $model::all();
        } catch (Exception \$e) {
            Log::error("Failed to fetch $model records: " . \$e->getMessage());
        }
    }
    
    public function render()
    {
        Log::info('render function called');
        return view('$viewName-cruds')
            ->layout('$layoutName');
    }
}
EOD);

        Log::info("Controller generated for $model at $controllerFile");
    }
}