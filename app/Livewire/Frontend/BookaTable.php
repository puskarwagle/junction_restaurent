<?php

namespace App\Livewire\Frontend;

use App\Models\TableBookings;
use Livewire\Component;

class BookaTable extends Component
{
    public $name;
    public $phone;
    public $persons;
    public $date;
    public $time;

    public function submitReservation()
    {
        // dd($this->name, $this->phone, $this->persons, $this->date, $this->time);

        // Validation
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'persons' => 'required|integer|min:1',
            'date' => 'required|date',
            'time' => 'required|string',
        ]);

        // Save to TableBookings model
        TableBookings::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'persons' => $this->persons,
            'date' => $this->date,
            'time' => $this->time,
        ]);

        session()->flash('success', 'Booking successfully submitted!');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.pages.front.bookaTable.bookaTable');
    }
}
