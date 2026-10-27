<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Customer;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

#[Layout('layouts.admin')]
class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    public $showModal = false;
    #[\Livewire\Attributes\Locked]
    public $userId;
    public $name;
    public $email;
    public $phone;
    public $password;
    public $role_id;

    /** Inline notice shown above the list: ['type' => 'message'|'error', 'text' => ...] */
    public $notice = null;

    protected function notify(string $type, string $text): void
    {
        $this->notice = ['type' => $type, 'text' => $text];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function createUser()
    {
        $this->resetValidation();
        $this->reset(['userId', 'name', 'email', 'phone', 'password', 'role_id']);
        $this->showModal = true;
    }

    public function editUser($id)
    {
        $this->resetValidation();
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
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'phone' => 'required|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'password' => $this->userId ? 'nullable|min:8' : 'required|min:8',
        ]);

        // Never let the last super admin lose that role
        if ($this->userId && $this->isLastSuperAdmin((int) $this->userId) &&Role::find($this->role_id)?->name !== 'super_admin') {
            $this->addError('role_id', 'This is the only Super Admin. Make someone else a Super Admin first.');
            return;
        }

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role_id' => $this->role_id,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        DB::transaction(function () use ($data) {
            $user = $this->userId ? tap(User::findOrFail($this->userId))->update($data) : User::create($data);

            // Customer accounts need a customer profile, or the customer pages refuse to open
            if (Role::find($this->role_id)?->name === 'customer' && ! $user->customer()->exists()) {
                Customer::create(['user_id' => $user->id]);
            }
        });

        $this->notify('message', $this->userId ? 'User updated.' : 'User created.');

        $this->showModal = false;
        $this->reset(['userId', 'name', 'email', 'phone', 'password', 'role_id']);
    }

    protected function isLastSuperAdmin(int $userId): bool
    {
        $superAdminRoleId = Role::where('name', 'super_admin')->value('id');

        return User::where('id', $userId)->where('role_id', $superAdminRoleId)->exists()
            && User::where('role_id', $superAdminRoleId)->count() === 1;
    }

    public function deleteUser($id)
    {
        $user = User::with('customer')->findOrFail($id);

        if ($user->id === Auth::id()) {
            $this->notify('error', "You can't delete your own account while signed in.");
            return;
        }

        if ($this->isLastSuperAdmin($user->id)) {
            $this->notify('error', "{$user->name} is the only Super Admin and can't be deleted.");
            return;
        }

        // Deleting a customer would also erase their cars, bookings and revenue history
        $bookingCount = $user->customer ? $user->customer->bookings()->count() : 0;
        if ($bookingCount > 0) {
            $this->notify('error', "{$user->name} has {$bookingCount} " . str('booking')->plural($bookingCount) . ". Deleting them would erase that history, so the account was kept.");
            return;
        }

        $user->delete();
        $this->notify('message', 'User deleted.');
    }

    public function render()
    {
        $query = User::with(['role', 'customer']);

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

        $users = $query->orderBy('name')->paginate(10);
        $roles = Role::withCount('users')->orderBy('id')->get();

        // Bookings per customer, for the "can delete" hint in the list
        $bookingCounts = DB::table('bookings')
            ->join('customers', 'customers.id', '=', 'bookings.customer_id')
            ->whereIn('customers.user_id', $users->pluck('id'))
            ->groupBy('customers.user_id')
            ->pluck(DB::raw('COUNT(*)'), 'customers.user_id');

        return view('livewire.admin.user-management', [
            'users' => $users,
            'roles' => $roles,
            'bookingCounts' => $bookingCounts,
        ]);
    }
}
