<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

#[Layout('layouts.admin')]
class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    public $showModal = false;
    public $userId;
    public $name;
    public $email;
    public $phone;
    public $password;
    public $role_id;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function createUser()
    {
        $this->reset(['userId', 'name', 'email', 'phone', 'password', 'role_id']);
        $this->showModal = true;
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->role_id = $user->role_id;
        $this->password = '';
        $this->showModal = true;
    }

    public function saveUser()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'phone' => 'required|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'password' => $this->userId ? 'nullable|min:6' : 'required|min:6',
        ]);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role_id' => $this->role_id,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->userId) {
            User::find($this->userId)->update($data);
            session()->flash('message', 'User updated successfully.');
        } else {
            $data['password'] = Hash::make($this->password);
            User::create($data);
            session()->flash('message', 'User created successfully.');
        }

        $this->showModal = false;
        $this->reset(['userId', 'name', 'email', 'phone', 'password', 'role_id']);
    }

    public function deleteUser($id)
    {
        User::find($id)->delete();
        session()->flash('message', 'User deleted successfully.');
    }

    public function render()
    {
        $query = User::with('role');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->roleFilter) {
            $query->where('role_id', $this->roleFilter);
        }

        $users = $query->paginate(10);
        $roles = Role::all();

        return view('livewire.admin.user-management', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }
}
