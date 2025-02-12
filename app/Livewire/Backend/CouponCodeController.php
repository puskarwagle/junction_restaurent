<?php

namespace App\Livewire\Backend;

use Livewire\Attributes\Layout;
use App\Models\CouponCode;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class CouponCodeController extends Component
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

    public $coupon;
    public $amount;
    public $expiration_date;

    public array $rules = [
        'coupon' => 'required|string|unique:coupons,code|max:255',
        'amount' => 'required|numeric|min:0',
        'expiration_date' => 'required|date|after_or_equal:today'
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
        $maxId = CouponCode::max('id');
        $this->nextId = $maxId ? $maxId + 1 : 1;
    }

    public function create(): void
    {
        $this->validate($this->rules);

        try {
            CouponCode::create($this->only(...$this->fillableAttributes()));
            session()->flash('message', 'CouponCode created successfully.');
            $this->reset([...$this->fillableAttributes(), 'showCreateForm']);
            $this->calculateNextId();
        } catch (Exception $e) {
            session()->flash('error', 'Failed to create CouponCode: ' . $e->getMessage());
            Log::error('Error creating CouponCode: ' . $e->getMessage());
        }
    }

    public function incrementClick(string $field, int $id, mixed $value): void
    {
        $key = "{$field}-{$id}";
        $this->clickCount[$key] = ($this->clickCount[$key] ?? 0) + 1;

        if ($this->clickCount[$key] === 4) {
            $this->editingField = $key;
            $this->editingValue = (string) $value;
            $this->clickCount[$key] = 0;
        }
    }

    public function saveModifiedField(string $field, int $id): void
    {
        $record = CouponCode::find($id);

        if (!$record) {
            session()->flash('error', 'Record not found.');
            return;
        }

        if (!in_array($field, $record->getFillable(), true)) {
            session()->flash('error', 'Field cannot be edited.');
            return;
        }

        // If the field exists in the rules, validate it
        $validationRule = $this->rules[$field] ?? 'required';
        
        // Manually validate the input
        $validator = Validator::make([$field => $this->editingValue], [
            $field => $validationRule
        ]);
    
        if ($validator->fails()) {
            session()->flash('error', 'Validation failed: ' . $validator->errors()->first());
            return;
        }

        $record->$field = $this->editingValue;
        $record->save();

        $this->editingField = null;
        $this->editingValue = '';

        session()->flash('message', 'Saved successfully.');
    }

    public function delete(): void
    {
        if (!empty($this->selectedIds)) {
            CouponCode::whereIn('id', $this->selectedIds)->delete();
            session()->flash('message', 'Selected records deleted successfully.');
            $this->selectedIds = [];
        } else {
            session()->flash('error', 'No records selected.');
        }
    }

    private function readModel(): array
    {
        $modelInstance = new CouponCode;
        $fillable = $modelInstance->getFillable();
        $query = $this->queryModel($fillable);

        return $this->formatReturn($query, $fillable);
    }

    private function queryModel(array $fillable)
    {
        $query = CouponCode::query();

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
        return view('backend.CouponCode-cruds', $this->readModel())->layout('layouts.app');
    }

    private function fillableAttributes(): array
    {
        return (new CouponCode)->getFillable();
    }
}