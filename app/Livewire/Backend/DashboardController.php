<?php
namespace App\Livewire\Backend;
use Illuminate\Http\Request;
use Livewire\Component;
use Illuminate\View\View;
use Livewire\Attributes\Layout;

class DashboardController extends Component
{
    public function render()
    {
        return view('backend.dashboard')->layout('layouts.app');
    }
}
