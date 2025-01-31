<?php

namespace App\Livewire\TestCrud;

use Livewire\Attributes\Layout;
use App\Models\TestCrud;
use Livewire\Component;
use Livewire\WithFileUploads;
use Exception;
use Illuminate\Support\Facades\Log;

class TestCrudController extends Component
{
    use WithFileUploads;

    public $name;
    public $phone;
    public $persons;
    public $date;
    public $time;
    public $price;
    public $image_path;
    public $description;

    protected $rules = [
        
    ];

    public function mount()
    {
        Log::info('mount function called');
        try {
            // Dynamically set the property name (e.g., "testCruds" for "TestCrud")
            $propertyName = lcfirst('TestCrud') . 's';
            
            // Fetch all records from the model
            $this->{$propertyName} = TestCrud::all();
        } catch (Exception $e) {
            Log::error("Failed to fetch TestCrud records: " . $e->getMessage());
        }
    }
    
    public function render()
    {
        Log::info('render function called');
        return view('TestCrud-cruds')
            ->layout('layouts.app');
    }
}