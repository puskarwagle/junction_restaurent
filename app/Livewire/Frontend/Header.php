<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class Header extends Component
{
    public function rollSaman()
    {
        $this->dispatch('notifyer');
    }

    public function render()
    {
        return view('livewire.pages.front.header.header');
    }
}
