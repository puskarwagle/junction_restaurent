<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\MenuItem;

class Menu extends Component
{
    public function render()
    {
        $menuItems = MenuItem::all()->groupBy('type');

        return view('livewire.pages.front.menu.menu', [
            'menuItems' => $menuItems
        ]);
    }
}



