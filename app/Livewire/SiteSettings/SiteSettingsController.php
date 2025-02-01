<?php

namespace App\Livewire\SiteSettings;

use Livewire\Attributes\Layout;
use App\Models\SiteSettings;
use Livewire\Component;
use Livewire\WithFileUploads;
use Exception;
use Illuminate\Support\Facades\Log;

class SiteSettingsController extends Component
{
    use WithFileUploads;

    public $key;
    public $value;

    protected $rules = [
        'key' => '',
        'value' => ''
    ];

    public function mount()
    {
        Log::info('mount function called');
        try {
            // Dynamically set the property name (e.g., "testCruds" for "TestCrud")
            $propertyName = lcfirst('SiteSettings') . 's';
            
            // Fetch all records from the model
            $this->{$propertyName} = SiteSettings::all();
        } catch (Exception $e) {
            Log::error("Failed to fetch SiteSettings records: " . $e->getMessage());
        }
    }
    
    public function render()
    {
        Log::info('render function called');
        return view('SiteSettings-cruds')
            ->layout('layouts.app');
    }
}