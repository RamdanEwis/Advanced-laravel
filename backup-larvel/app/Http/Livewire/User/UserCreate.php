<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use Livewire\Component;

class UserCreate extends Component
{
    public $name;
    public $email;

    public function render()
    {
        return view('livewire.create-user');
    }

    public function saveUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        // Create a new user record
        User::create([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        // Reset the form fields
        $this->reset(['name', 'email']);

        // Emit an event to notify parent components or listeners
        $this->emit('userAdded');
    }
}
