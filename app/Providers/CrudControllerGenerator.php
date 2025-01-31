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

        $fillables = $this->getFillableAttributes($modelImportPath);
        $properties = implode("\n    ", array_map(fn($f) => "public \$$f;", $fillables));

        $rulesArray = $this->getValidationRules($modelImportPath);
        $rulesString = implode(",\n        ", array_map(fn($k, $v) => "'$k' => '$v'", array_keys($rulesArray), $rulesArray));

        // Generate the view name dynamically
        // $viewName = Str::kebab(Str::plural($model)); // e.g., "test-cruds"
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
        return view('$viewName')
            ->layout('$layoutName');
    }
}
EOD);

        Log::info("Controller generated for $model at $controllerFile");
    }

    protected function getFillableAttributes($modelImportPath)
    {
        if (!class_exists($modelImportPath)) {
            throw new Exception("Model $modelImportPath not found.");
        }

        return (new $modelImportPath)->getFillable();
    }

    protected function getValidationRules($modelImportPath)
    {
        if (!class_exists($modelImportPath)) {
            throw new Exception("Model $modelImportPath not found.");
        }

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
    }
}