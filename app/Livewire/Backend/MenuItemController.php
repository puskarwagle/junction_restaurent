<?php

namespace App\Livewire\Backend;

use Livewire\Attributes\Layout;
use App\Models\MenuItem;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class MenuItemController extends Component
{
    use WithFileUploads, WithPagination;

    public bool $showCreateForm = false;
    public array $selectedIds = [];
    public bool $selectAll = false;
    public int $nextId;
    public ?string $editingField = null;
    public string $editingValue = '';
    public array $clickCount = [];
    public string $search = '';
    public string $sortField = 'id';
    public string $sortDirection = 'asc';
    public int $perPage = 10;

    public $name;
    public $price;
    public $image_path;
    public $description;
    public $type;
    public $discount;
    public $is_special;
    
    protected array $rules = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'image_path' => 'nullable|string',
        'description' => 'nullable|string',
        'type' => 'required|string|max:50',
        'discount' => 'nullable|numeric|min:0|max:100',
        'is_special' => 'boolean'
    ];

    public function mount(): void
    {
        $this->calculateNextId();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function calculateNextId(): void
    {
        $maxId = MenuItem::max('id');
        $this->nextId = $maxId ? $maxId + 1 : 1;
    }

    public function create(): void
    {
        $this->validate($this->rules);

        try {
            MenuItem::create($this->only(...$this->fillableAttributes()));
            session()->flash('message', 'MenuItem created successfully.');
            $this->reset([...$this->fillableAttributes(), 'showCreateForm']);
            $this->calculateNextId();
        } catch (Exception $e) {
            session()->flash('error', 'Failed to create ' . MenuItem . ': ' . $e->getMessage());
            Log::error('Error creating ' . MenuItem . ': ' . $e->getMessage());
        }
    }

    public function incrementClick(string $field, int $id, mixed $value): void
    {
        $key = "$field-$id";
        $this->clickCount[$key] = ($this->clickCount[$key] ?? 0) + 1;

        if ($this->clickCount[$key] === 4) {
            $this->editingField = $key;
            $this->editingValue = (string) $value;
            $this->clickCount[$key] = 0;
        }
    }

    public function saveModifiedField(string $field, int $id): void
    {
        $record = MenuItem::find($id);
    
        if (!$record) {
            session()->flash('error', 'Record not found.');
            return;
        }
    
        if (!in_array($field, $record->getFillable(), true)) {
            session()->flash('error', 'Field cannot be edited.');
            return;
        }
    
        $this->validate([
            $field => $this->rules[$field] ?? 'required',
        ]);
    
        $record->$field = $this->editingValue;
        $record->save();
    
        $this->editingField = null;
        $this->editingValue = '';
    
        session()->flash('message', 'Data saved successfully.');
    }
    

    public function delete(): void
    {
        if (!empty($this->selectedIds)) {
            MenuItem::whereIn('id', $this->selectedIds)->delete();
            session()->flash('message', 'Selected records deleted successfully.');
            $this->selectedIds = [];
        } else {
            session()->flash('error', 'No records selected.');
        }
    }

    private function readModel(): array
    {
        $modelInstance = new MenuItem;
        $fillable = $modelInstance->getFillable();
        $query = $this->queryModel($fillable);

        return $this->formatReturn($query, $fillable);
    }

    private function queryModel(array $fillable)
    {
        $query = MenuItem::query();

        if (!empty($this->search)) {
            $query->where(function($q) use ($fillable) {
                foreach ($fillable as $field) {
                    $q->orWhere($field, 'like', '%' . $this->search . '%');
                }
            });
        }

        return $query->orderBy($this->sortField, $this->sortDirection)
                    ->paginate($this->perPage);
    }

    private function formatReturn($query, $fillable): array
    {
        return [
            'tabledata' => $query->map(fn($record) => $record->getAttributes())->toArray(),
            'fields' => $fillable,
            'input_types' => $this->determineInputTypes($fillable),
            'pagination' => [
                'current_page' => $query->currentPage(),
                'per_page' => $query->perPage(),
                'total' => $query->total(),
                'last_page' => $query->lastPage(),
            ],
            'sort' => [
                'field' => $this->sortField,
                'direction' => $this->sortDirection,
            ],
            'search' => [
                'term' => $this->search,
            ],
        ];
    }


    private function determineInputTypes(array $fillable): array
    {
        $typeMapping = [
            'email' => 'email',
            'numeric' => 'number',
            'integer' => 'number',
            'date' => 'date',
            'boolean' => 'checkbox',
        ];

        return array_map(function ($field) use ($typeMapping) {
            $rules = explode('|', $this->rules[$field] ?? '');

            return array_reduce($rules, function ($carry, $rule) use ($typeMapping) {
                return $typeMapping[$rule] ?? $carry;
            }, 'text');
        }, array_combine($fillable, $fillable));
    }

    public function goToPage(int $page): void
    {
        $this->gotoPage($page);
    }

    public function previousPage(): void
    {
        $this->previousPage();
    }

    public function nextPage(): void
    {
        $this->nextPage();
    }

    public function render(): View
    {
        // dd($this->readModel()); // Debug the data before rendering the view
        return view('backend.MenuItem-cruds', $this->readModel())->layout('layouts.app');
    }

    private function fillableAttributes(): array
    {
        return (new MenuItem)->getFillable();
    }
}