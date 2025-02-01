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

use Livewire\\Attributes\\Layout;
use $modelImportPath;
use Livewire\\Component;
use Livewire\\WithPagination;
use Livewire\\WithFileUploads;
use Exception;
use Illuminate\\Support\\Facades\\Log;

class $controllerClass extends Component
{
    use WithFileUploads, WithPagination;

    $properties

    public \$search = '';
    public \$sortField = 'id';
    public \$sortDirection = 'asc';
    public \$perPage = 10;

    protected \$rules = [
        $rulesString
    ];

    public function mount()
    {
        Log::info('mount function called');
        // Any initialization can be added here if needed.
    }

    public function sortBy(\$field)
    {
        if (\$this->sortField === \$field) {
            \$this->sortDirection = \$this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            \$this->sortField = \$field;
            \$this->sortDirection = 'asc';
        }
    }

    public function read()
    {
        // Dynamically retrieve fillable fields from the model
        \$modelInstance = new $model;
        \$fillable = \$modelInstance->getFillable();

        // Build the query
        \$query = $model::query();

        // Apply filtering if a search term is provided
        if (!empty(\$this->search)) {
            \$query->where(function(\$q) use (\$fillable) {
                foreach (\$fillable as \$field) {
                    \$q->orWhere(\$field, 'like', '%' . \$this->search . '%');
                }
            });
        }

        // Apply sorting
        \$query->orderBy(\$this->sortField, \$this->sortDirection);

        // Return paginated results as an array
        \$paginatedResults = \$query->paginate(\$this->perPage);

        // Dynamically get the columns and their data
        \$tabledata = \$paginatedResults->map(function (\$item) {
            return \$item->toArray(); // Convert each item to an array
        });

        return [
            'tabledata' => \$tabledata, 
            'pagination' => [
                'current_page' => \$paginatedResults->currentPage(),
                'per_page' => \$paginatedResults->perPage(),
                'total' => \$paginatedResults->total(),
                'last_page' => \$paginatedResults->lastPage(),
            ],
            'sort' => [
                'field' => \$this->sortField,
                'direction' => \$this->sortDirection,
            ],
            'search' => [
                'term' => \$this->search,
            ],
        ];
    }

    public function render()
    {
        Log::info('render function called');
        \$data = \$this->read();
        return view('$viewName-cruds', [
            'tabledata' => \$data['tabledata'],
            'pagination' => \$data['pagination'],
            'sort' => \$data['sort'],
            'search' => \$data['search'],
        ])->layout('$layoutName');
    }
}
EOD);

        Log::info("Controller generated for $model at $controllerFile");
    }
}