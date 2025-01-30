<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class AdminManagement extends Component
{
    public $users;
    public $msgst;
    public $msg;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';

    protected $listeners = ['userTypeUpdated' => 'refreshUsers'];

    public function mount()
    {
        $this->refreshUsers();
        $this->msgst = Session::get('msgst');
        $this->msg = Session::get('msg');
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function refreshUsers()
    {
        $this->users = User::when($this->search, function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
        })->get();
    }

    public function updateUserType($userId, $newType)
    {
        $user = User::find($userId);
        if ($user) {
            $user->type = $newType;
            $user->save();
            $this->msgst = 'success';
            $this->msg = 'User type updated successfully.';
            $this->dispatch('userTypeUpdated');
        } else {
            $this->msgst = 'error';
            $this->msg = 'User not found.';
        }
    }

    public function deleteUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->delete();
            $this->msgst = 'success';
            $this->msg = 'User deleted successfully.';
            $this->emit('userTypeUpdated');
        } else {
            $this->msgst = 'error';
            $this->msg = 'User not found.';
        }
    }

    public function updatedSearch()
    {
        $this->refreshUsers();
    }

    public function render()
    {
        return view('admin', [
            'users' => User::when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->get(),
        ])->layout('layouts.app');
    }
}
