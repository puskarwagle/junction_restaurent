<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class Dynamic extends Component
{
    public string $saman = 'nothing';
    protected $listeners = ['notifyer' => 'serveSaman'];

    #[On('notifyer')]
    public function serveSaman()
    {
        $this->saman = 'Here is your Saman';
    }

    public function render()
    {
        return view('livewire.dynamic.dynamic');
    }
}


// <div>
//         <livewire:header />
//             {{$slot}}
//         <livewire:footer />
//     </div>