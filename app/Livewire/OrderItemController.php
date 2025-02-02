<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use App\Models\OrderItem;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Exception;
use Illuminate\Support\Facades\Log;

class OrderItemController extends Component
{
    use WithFileUploads, WithPagination;

    public $order_id;
    public $menu_item_name;
    public $quantity;
    public $price;

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
        'order_id' => '',
        'menu_item_name' => '',
        'quantity' => '',
        'price' => ''
    ];

    public function mount()
    {
        Log::info('mount function called');
        $this->calculateNextId();
        // Any initialization can be added here if needed.
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

    public function calculateNextId()
    {
        $maxId = OrderItem::max('id');
        $this->nextId = $maxId ? $maxId + 1 : 1;
    }

    public function create()
    {
        $this->validate();

        try {
            OrderItem::create([
                'order_id' => $this->order_id,
                'menu_item_name' => $this->menu_item_name,
                'quantity' => $this->quantity,
                'price' => $this->price
            ]);

            session()->flash('message', 'OrderItem created successfully.');
            $this->reset(['order_id', 'menu_item_name', 'quantity', 'price', 'showCreateForm']);
            $this->calculateNextId();
        } catch (Exception $e) {
            session()->flash('error', 'Failed to create OrderItem.');
            Log::error('Error creating OrderItem: ' . $e->getMessage());
        }
    }

    public function incrementClick($field, $id, $value)
    {
        $key = $field . '-' . $id;

        if (!isset($this->clickCount[$key])) {
            $this->clickCount[$key] = 0;
        }

        $this->clickCount[$key]++;

        if ($this->clickCount[$key] === 4) {
            $this->editingField = $key;
            $this->editingValue = $value;
            $this->clickCount[$key] = 0;
        }
    }

    public function saveModifiedField($field, $id)
    {
        $record = OrderItem::find($id);
        $record->$field = $this->editingValue;
        $record->save();

        $this->editingField = null;
        $this->editingValue = '';
    }

    public function delete()
    {
        if (!empty($this->selectedIds)) {
            OrderItem::whereIn('id', $this->selectedIds)->delete();
            session()->flash('message', 'Selected records deleted successfully.');
            $this->selectedIds = [];
        } else {
            session()->flash('error', 'No records selected.');
        }
    }
        
    public function read()
    {
        // Dynamically retrieve fillable fields from the model
        $modelInstance = new OrderItem;
        $fillable = $modelInstance->getFillable();

        // Build the query
        $query = OrderItem::query();

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
        return view('OrderItem-cruds', [
            'tabledata' => $data['tabledata'],
            'pagination' => $data['pagination'],
            'sort' => $data['sort'],
            'search' => $data['search'],
        ])->layout('layouts.app');
    }
}