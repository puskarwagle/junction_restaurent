<?php

namespace App\Livewire\SiteSettings;

use Livewire\Attributes\Layout;
use App\Models\SiteSettings;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Exception;
use Illuminate\Support\Facades\Log;

class SiteSettingsController extends Component
{
    use WithFileUploads, WithPagination;

    public $key;
    public $value;

    public $showCreateForm = false; // Tracks form visibility
    public $selectedIds = []; // Stores selected IDs for deletion
    public $nextId; // Stores the next ID for display

    public $editingField = null; // Tracks which field is being edited (e.g., 'key-1')
    public $editingValue = ''; // Stores the value being edited
    public $clickCount = []; // Tracks click counts for each field

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;

    protected $rules = [
        'key' => 'required|string',
        'value' => 'required|string',
    ];

    public function mount()
    {
        Log::info('mount function called');
        $this->calculateNextId();
    }

    public function calculateNextId()
    {
        $maxId = SiteSettings::max('id'); // Get the maximum ID from the database
        $this->nextId = $maxId ? $maxId + 1 : 1; // Calculate the next ID
    }

    public function create()
    {
        $this->validate();

        try {
            SiteSettings::create([
                'key' => $this->key,
                'value' => $this->value,
            ]);

            session()->flash('message', 'Site setting created successfully.');
            $this->reset(['key', 'value', 'showCreateForm']); // Reset form fields and hide the form
            $this->calculateNextId(); // Recalculate the next ID
        } catch (Exception $e) {
            session()->flash('error', 'Failed to create site setting.');
            Log::error('Error creating site setting: ' . $e->getMessage());
        }
    }

    // Increment click count and toggle editing mode
    public function incrementClick($field, $id, $value)
    {
        $key = $field . '-' . $id;

        // Initialize click count if not set
        if (!isset($this->clickCount[$key])) {
            $this->clickCount[$key] = 0;
        }

        $this->clickCount[$key]++;

        // If clicked 4 times, enable editing
        if ($this->clickCount[$key] === 4) {
            $this->editingField = $key;
            $this->editingValue = $value;
            $this->clickCount[$key] = 0; // Reset click count
        }
    }

    // Save the edited field
    public function saveModifiedField($field, $id)
    {
        // Find the record and update the field
        $record = SiteSettings::find($id);
        $record->$field = $this->editingValue;
        $record->save();

        // Reset editing state
        $this->editingField = null;
        $this->editingValue = '';
    }

    public function delete()
    {
        // Handle deletion of selected records
        if (!empty($this->selectedIds)) {
            SiteSettings::whereIn('id', $this->selectedIds)->delete();
            session()->flash('message', 'Selected records deleted successfully.');
            $this->selectedIds = []; // Clear selected IDs
        } else {
            session()->flash('error', 'No records selected.');
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function read()
    {
        // Dynamically retrieve fillable fields from the model
        $modelInstance = new SiteSettings;
        $fillable = $modelInstance->getFillable();

        // Build the query
        $query = SiteSettings::query();

        // Apply filtering if a search term is provided
        if (!empty($this->search)) {
            $query->where(function($q) use ($fillable) {
                foreach ($fillable as $field) {
                    $q->orWhere($field, 'like', '%' . $this->search . '%');
                }
            });
        }

        // Apply sorting
        $query->orderBy($this->sortField, $this->sortDirection);

        // Return paginated results as an array
        $paginatedResults = $query->paginate($this->perPage);

        // Dynamically get the columns and their data
        $tabledata = $paginatedResults->map(function ($item) {
            return $item->toArray(); // Convert each item to an array
        });

        return [
            'tabledata' => $tabledata, 
            'pagination' => [
                'current_page' => $paginatedResults->currentPage(),
                'per_page' => $paginatedResults->perPage(),
                'total' => $paginatedResults->total(),
                'last_page' => $paginatedResults->lastPage(),
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

    public function render()
    {
        Log::info('render function called');
        $data = $this->read();
        return view('SiteSettings-cruds', [
            'tabledata' => $data['tabledata'],
            'pagination' => $data['pagination'],
            'sort' => $data['sort'],
            'search' => $data['search'],
        ])->layout('layouts.app');
    }
}