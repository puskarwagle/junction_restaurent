<?php

namespace App\Livewire\Backend;

use Livewire\Attributes\Layout;
use App\Models\MenuItem;
use Livewire\Component;
use Livewire\WithFileUploads;
use Exception;
use Illuminate\Support\Facades\Log;

class MenuItemController extends Component
{
    use WithFileUploads;

    public $name;
    public $price;
    public $image_path;
    public $description;
    public $type;
    public $discount;
    public $is_special;

    protected $rulesString = [
        'created_at' => 'nullable|date',
        'description' => 'nullable|string',
        'discount' => 'required|numeric',
        'id' => 'required|numeric',
        'image_path' => 'nullable|image|max:1024',
        'is_special' => 'required|boolean',
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'type' => 'required|string|max:255',
        'updated_at' => 'nullable|date'
    ];

    public function mount()
    {
        Log::info('mount function called');
        try {
            $this->{Str::camel(Str::plural('MenuItem'))} = MenuItem::all();
        } catch (Exception $e) {
            Log::info('Failed to fetch ' . Str::plural('MenuItem') . ': ' . $e->getMessage());
        }
    }

}