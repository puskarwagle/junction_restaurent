<?php
namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Log;

class CrudControllerGenerator
{
    public function generate(string $model, string $controllerPath): void
    {
        $controllerClass = $model . 'Controller';
        $controllerFile = "$controllerPath/$controllerClass.php";

        $namespaceForThisFile = implode('\\', array_map(fn($part) => Str::studly($part), explode('/', $controllerPath)));
        $modelImportPath = "App\\Models\\$model";

        if (!class_exists($modelImportPath)) {
            throw new Exception("Model $modelImportPath not found.");
        }

        $fillables = (new $modelImportPath)->getFillable();
        $rulesString = implode(",\n        ", array_map(fn($f) => "'$f' => 'required'", $fillables));

        $viewName = $model;
        $layoutName = 'layouts.app';

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
use Illuminate\\View\\View;

class $controllerClass extends Component
{
    use WithFileUploads, WithPagination;

    public bool \$showCreateForm = false;
    public array \$selectedIds = [];
    public bool \$selectAll = false;
    public int \$nextId;
    public ?string \$editingField = null;
    public string \$editingValue = '';
    public array \$clickCount = [];
    public string \$search = '';
    public string \$sortField = 'id';
    public string \$sortDirection = 'asc';
    public int \$perPage = 10;

    protected array \$rules = [
        $rulesString
    ];

    public function mount(): void
    {
        \$this->calculateNextId();
    }

    public function sortBy(string \$field): void
    {
        if (\$this->sortField === \$field) {
            \$this->sortDirection = \$this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            \$this->sortField = \$field;
            \$this->sortDirection = 'asc';
        }
    }

    public function calculateNextId(): void
    {
        \$maxId = $model::max('id');
        \$this->nextId = \$maxId ? \$maxId + 1 : 1;
    }

    public function create(): void
    {
        \$this->validate();

        try {
            $model::create(\$this->only(...\$this->fillableAttributes()));
            session()->flash('message', '$model created successfully.');
            \$this->reset([...\$this->fillableAttributes(), 'showCreateForm']);
            \$this->calculateNextId();
        } catch (Exception \$e) {
            session()->flash('error', 'Failed to create ' . $model . ': ' . \$e->getMessage());
            Log::error('Error creating ' . $model . ': ' . \$e->getMessage());
        }
    }

    public function incrementClick(string \$field, int \$id, mixed \$value): void
    {
        \$key = "\$field-\$id";
        \$this->clickCount[\$key] = (\$this->clickCount[\$key] ?? 0) + 1;

        if (\$this->clickCount[\$key] === 4) {
            \$this->editingField = \$key;
            \$this->editingValue = (string) \$value;
            \$this->clickCount[\$key] = 0;
        }
    }

    public function saveModifiedField(string \$field, int \$id): void
    {
        \$record = $model::find(\$id);

        if (!\$record) {
            session()->flash('error', 'Record not found.');
            return;
        }

        if (!in_array(\$field, \$record->getFillable(), true)) {
            session()->flash('error', 'Field cannot be edited.');
            return;
        }

        \$this->validate([
            \$field => \$this->rules[\$field] ?? 'required',
        ]);

        \$record->\$field = \$this->editingValue;
        \$record->save();

        \$this->editingField = null;
        \$this->editingValue = '';
    }

    public function delete(): void
    {
        if (!empty(\$this->selectedIds)) {
            $model::whereIn('id', \$this->selectedIds)->delete();
            session()->flash('message', 'Selected records deleted successfully.');
            \$this->selectedIds = [];
        } else {
            session()->flash('error', 'No records selected.');
        }
    }
        
    public function read(): array
    {
        \$modelInstance = new $model;
        \$fillable = \$modelInstance->getFillable();

        \$query = $model::query();

        if (!empty(\$this->search)) {
            \$query->where(function(\$q) use (\$fillable) {
                foreach (\$fillable as \$field) {
                    \$q->orWhere(\$field, 'like', '%' . \$this->search . '%');
                }
            });
        }

        \$query->orderBy(\$this->sortField, \$this->sortDirection);
        \$paginatedResults = \$query->paginate(\$this->perPage);

        return [
            'tabledata' => \$paginatedResults->items(),
            'fields' => \$fillable,
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
    
    public function goToPage(int \$page): void
    {
        \$this->gotoPage(\$page);
    }

    public function previousPage(): void
    {
        \$this->previousPage();
    }

    public function nextPage(): void
    {
        \$this->nextPage();
    }

    public function render(): View
    {
        return view('$viewName-cruds', \$this->read())->layout('$layoutName');
    }

    private function fillableAttributes(): array
    {
        return (new $model)->getFillable();
    }
}
EOD);

        Log::info("Controller generated for $model at $controllerFile");
    }
}
