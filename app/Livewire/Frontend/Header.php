<?php

namespace App\Livewire\Frontend;
use Livewire\Component;

class Header extends Component
{
    public $cartCount = 0;
    public $addedItems = [];

    protected $listeners = ['itemAddedToCart' => 'incrementCartCount'];

    public function incrementCartCount($data)
    {
        if (!in_array($data['id'], $this->addedItems)) {
            $this->addedItems[] = $data['id'];
            $this->cartCount++;
        }
    }

    // public function incrementCartCount()
    // {
    //     $this->cartCount++;
    // }

    public function render()
    {
        return view('livewire.pages.front.header.header');
    }
}
