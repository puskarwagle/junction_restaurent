<?php

namespace App\Livewire\Backend;

use Livewire\Attributes\Layout;
use App\Models\MenuItem;
use Livewire\Component;
use Livewire\WithFileUploads;
use Exception;
use Illuminate\Support\Facades\Log;

class MenuManagement extends Component
{
    use WithFileUploads;

    public $menuItems, $name, $price, $image, $description, $type, $discount, $is_special, $itemId;

    protected $rules = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'image' => 'nullable|image|max:1024',
        'description' => 'nullable|string',
        'type' => 'required|string|max:50',
        'discount' => 'nullable|numeric|max:100',
        'is_special' => 'boolean',
    ];

    public function mount()
    {
        Log::info('mount function called');
        try {
            $this->menuItems = MenuItem::all();
        } catch (Exception $e) {
            Log::info('Failed to fetch menu items: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        Log::info("edit function called with id: $id");
        try {
            $item = MenuItem::findOrFail($id);
            $this->itemId = $item->id;
            $this->name = $item->name;
            $this->price = $item->price;
            $this->image = $item->image_path;
            $this->description = $item->description;
            $this->type = $item->type;
            $this->discount = $item->discount;
            $this->is_special = $item->is_special;
        } catch (Exception $e) {
            Log::info('Failed to load menu item: ' . $e->getMessage());
        }
    }

    public function update()
    {
        Log::info("update function called for item ID: $this->itemId");
        try {
            $this->validate();
            $item = MenuItem::findOrFail($this->itemId);

            $imagePath = $item->image_path;
            if ($this->image instanceof \Livewire\TemporaryUploadedFile) {
                $imagePath = $this->image->store('images/menu', 'public');
            }

            $item->update([
                'name' => $this->name,
                'price' => $this->price,
                'image_path' => $imagePath,
                'description' => $this->description,
                'type' => $this->type,
                'discount' => $this->discount,
                'is_special' => $this->is_special,
            ]);

            Log::info('Menu item updated successfully.');
            $this->mount();
        } catch (Exception $e) {
            Log::info('Failed to update menu item: ' . $e->getMessage());
        }
    }

    public function statements()
    {
        Log::info('statements function called');
        try {
            return MenuItem::select('id', 'name', 'price', 'type')->get();
        } catch (Exception $e) {
            Log::info('Failed to retrieve statements: ' . $e->getMessage());
            return collect();
        }
    }

    public function render()
    {
        Log::info('render function called');
        return view('menumanagement')
            ->layout('layouts.app');
    }
}
