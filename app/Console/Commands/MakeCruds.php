<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeCruds extends Command
{
    protected $signature = 'make:cruds';
    protected $description = 'Generate Livewire 3 CRUD with controllers and views';

    // Test values
    protected $testModel = 'MenuItem';
    protected $testControllerPath = 'app/Livewire/Backend';
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

        $this->generateController($model, $controllerPath);
        $this->generateView($model, $viewsPath);
    }

    protected function getFillableAttributes($modelImportPath)
    {
        if (!class_exists($modelImportPath)) {
            $this->error("Model $modelImportPath not found.");
            return [];
        }

        return (new $modelImportPath)->getFillable();
    }

    protected function getValidationRules($modelImportPath)
    {
        if (!class_exists($modelImportPath)) {
            $this->error("Model $modelImportPath not found.");
            return [];
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
    


    protected function generateController($model, $controllerPath)
    {
        $controllerClass = $model . 'Controller';
        $controllerFile = "$controllerPath/$controllerClass.php";
    
        $namespaceForThisFile = str_replace('/', '\\', ucfirst($controllerPath));
        $modelImportPath = "App\\Models\\$model";
    
        $fillables = $this->getFillableAttributes($modelImportPath);
        $properties = implode("\n    ", array_map(fn($f) => "public \$$f;", $fillables));
    
        // $rules = implode(",\n            ", $rulesArray);
    
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

    protected \$rulesString = [
        $rulesString
    ];

    public function mount()
    {
        Log::info('mount function called');
        try {
            \$this->{Str::camel(Str::plural('$model'))} = $model::all();
        } catch (Exception \$e) {
            Log::info('Failed to fetch ' . Str::plural('$model') . ': ' . \$e->getMessage());
        }
    }

}
EOD);
    
        $this->info("Controller generated for $model at $controllerFile");
    }

    protected function generateView($model, $viewsPath)
    {
        $viewFile = "$viewsPath/{$model}Cruds.blade.php";

        File::ensureDirectoryExists($viewsPath);

        File::put($viewFile, <<<EOD

        <h1>Hey pyaromanis</h1>

    EOD);

        $this->info("View generated for $model at $viewFile");
    }

}