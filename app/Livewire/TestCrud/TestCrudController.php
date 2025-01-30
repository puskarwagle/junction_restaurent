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
            $this->{Str::camel(Str::plural('TestCrud'))} = TestCrud::all();
        } catch (Exception $e) {
            Log::error('Failed to fetch ' . Str::plural('TestCrud') . ': ' . $e->getMessage());
        }
    }

}