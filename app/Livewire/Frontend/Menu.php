<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\MenuItem;

class Menu extends Component
{
    // public function updateCartCounter($menuItemId = null)
    // {
    //     $this->dispatch('itemAddedToCart', $menuItemId);
    // }

    public function updateCartCounter($menuItemId)
    {
        $this->dispatch('itemAddedToCart', ['id' => $menuItemId])->to(Header::class);
    }

    public function render()
    {
        $menuItems = MenuItem::select('id', 'name', 'price', 'image_path', 'description', 'type', 'discount', 'is_special')
        ->get()
        ->groupBy('type');
    
        return view('livewire.pages.front.menu.menu', [
            'menuItems' => $menuItems
        ]);
    }
}



