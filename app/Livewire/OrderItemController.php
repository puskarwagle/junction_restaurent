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