@php
    $roleLabel = fn ($name) => match ($name) {
        'super_admin' => 'Super Admin',
        'business_owner' => 'Owner',
        'customer' => 'Customer',
        default => ucwords(str_replace('_', ' ', (string) $name)),
    };
    $roleStyle = fn ($name) => match ($name) {
        'super_admin' => 'bg-sky-400/10 text-sky-300 ring-sky-400/30',
        'business_owner' => 'bg-violet-400/10 text-violet-300 ring-violet-400/30',
        default => 'bg-emerald-400/10 text-emerald-300 ring-emerald-400/30',
    };
@endphp

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-sky-300">Users</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-white">User accounts</h1>
            <p class="mt-1 text-gray-400">Customers, owners and super admins who can sign in.</p>
        </div>
        <button wire:click="createUser" class="inline-flex items-center gap-2 h-11 px-5 rounded-xl bg-sky-400 text-gray-950 font-semibold hover:bg-sky-300 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add user
        </button>
    </div>

    <x-cp-notice :notice="$notice" />

    <!-- Role tiles (also filters) -->
    <section class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <button wire:click="$set('roleFilter', '')" @class(['rounded-2xl p-4 text-left ring-1 transition', 'bg-sky-400/10 ring-sky-400/50' => $roleFilter === '' || $roleFilter === null, 'bg-white/[0.03] ring-white/[0.07] hover:ring-white/20' => $roleFilter !== '' && $roleFilter !== null])>
            <p class="text-sm text-gray-400">All users</p>
            <p class="mt-1 text-2xl font-bold text-white">{{ $roles->sum('users_count') }}</p>
        </button>
        @foreach ($roles as $role)
            <button wire:click="$set('roleFilter', '{{ $role->id }}')" @class(['rounded-2xl p-4 text-left ring-1 transition', 'bg-sky-400/10 ring-sky-400/50' => (string) $roleFilter === (string) $role->id, 'bg-white/[0.03] ring-white/[0.07] hover:ring-white/20' => (string) $roleFilter !== (string) $role->id])>
                <p class="text-sm text-gray-400">{{ \Illuminate\Support\Str::plural($roleLabel($role->name)) }}</p>
                <p class="mt-1 text-2xl font-bold text-white">{{ $role->users_count }}</p>
            </button>
        @endforeach
    </section>

    <section class="cp-card">
        <div class="p-4 sm:p-5 border-b border-white/[0.06] flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search name, email or phone" class="cp-input !pl-12">
            </div>
            <x-cp-select model="roleFilter" live class="sm:w-56" label="Role" placeholder="All roles" :searchable="false"
                :options="['' => 'All roles'] + $roles->mapWithKeys(fn ($r) => [$r->id => $roleLabel($r->name)])->all()" />
        </div>

        <ul class="divide-y divide-white/[0.05]">
            @forelse ($users as $user)
                @php
                    $bookings = (int) ($bookingCounts[$user->id] ?? 0);
                    $isMe = $user->id === auth()->id();
                @endphp
                <li wire:key="u-{{ $user->id }}" class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 px-4 sm:px-5 py-4 hover:bg-white/[0.02]">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <span class="w-10 h-10 flex-shrink-0 rounded-full bg-white/[0.06] flex items-center justify-center font-bold text-gray-200">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-semibold text-white truncate">{{ $user->name }}</span>
                                <span class="cp-badge {{ $roleStyle($user->role?->name) }}">{{ $roleLabel($user->role?->name ?? 'none') }}</span>
                                @if ($isMe)
                                    <span class="cp-badge bg-white/10 text-gray-200 ring-white/20">You</span>
                                @endif
                                @if ($user->google_id)
                                    <span class="text-xs text-gray-500">Google sign-in</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-400 truncate">{{ $user->email }} · {{ $user->phone ?: 'no phone' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between sm:justify-end gap-4">
                        <span class="text-xs text-gray-500 text-right">
                            @if ($user->role?->name === 'customer')
                                {{ $bookings }} {{ \Illuminate\Support\Str::plural('booking', $bookings) }} ·
                            @endif
                            joined {{ $user->created_at->format('M j, Y') }}
                        </span>
                        <div class="flex gap-2">
                            <button wire:click="editUser({{ $user->id }})" class="h-9 px-3.5 rounded-lg text-sm font-semibold bg-white/[0.06] text-white ring-1 ring-white/10 hover:bg-white/10">Edit</button>
                            @unless ($isMe)
                                <button wire:click="deleteUser({{ $user->id }})"
                                        wire:confirm="Delete {{ $user->name }}? This can't be undone."
                                        @disabled($bookings > 0)
                                        title="{{ $bookings > 0 ? 'Has bookings, so it can\'t be deleted' : 'Delete user' }}"
                                        class="h-9 px-3.5 rounded-lg text-sm font-semibold text-rose-300 ring-1 ring-rose-500/30 hover:bg-rose-500/10 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-transparent">
                                    Delete
                                </button>
                            @endunless
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-5 py-16 text-center">
                    <p class="text-lg font-semibold text-white">No users found</p>
                    <p class="mt-1 text-sm text-gray-400">Try a different search or role.</p>
                </li>
            @endforelse
        </ul>

        @if ($users->hasPages())
            <div class="p-4 sm:p-5 border-t border-white/[0.06]">{{ $users->links('livewire.cp-pagination') }}</div>
        @endif
    </section>

    <!-- Add / edit user -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4" wire:click.self="$set('showModal', false)">
            <div class="w-full max-w-lg rounded-3xl bg-[#111417] ring-1 ring-white/10 shadow-2xl" x-data x-trap.noscroll="true" @keydown.escape.window="$wire.set('showModal', false)">
                <div class="flex items-center justify-between p-6 pb-2">
                    <div>
                        <h2 class="text-xl font-bold text-white">{{ $userId ? 'Edit user' : 'Add user' }}</h2>
                        <p class="text-sm text-gray-400">{{ $userId ? 'Leave the password empty to keep the current one.' : 'They can sign in right away with this email and password.' }}</p>
                    </div>
                    <button wire:click="$set('showModal', false)" class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/5" aria-label="Close">✕</button>
                </div>
                <form wire:submit="saveUser" class="p-6 pt-4 space-y-4">
                    <div>
                        <label for="u-name" class="cp-label">Full name</label>
                        <input id="u-name" wire:model="name" type="text" class="cp-input" autofocus>
                        @error('name') <span class="cp-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="u-email" class="cp-label">Email</label>
                            <input id="u-email" wire:model="email" type="email" class="cp-input">
                            @error('email') <span class="cp-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="u-phone" class="cp-label">Phone</label>
                            <input id="u-phone" wire:model="phone" type="text" class="cp-input">
                            @error('phone') <span class="cp-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="cp-label">Role</label>
                        <x-cp-select model="role_id" label="Role" placeholder="Choose a role" :searchable="false"
                            :options="$roles->mapWithKeys(fn ($r) => [$r->id => $roleLabel($r->name)])->all()" />
                        @error('role_id') <span class="cp-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="u-password" class="cp-label">Password {{ $userId ? '(optional)' : '' }}</label>
                        <input id="u-password" wire:model="password" type="password" class="cp-input" autocomplete="new-password" placeholder="At least 8 characters">
                        @error('password') <span class="cp-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" wire:click="$set('showModal', false)" class="cp-btn-ghost">Cancel</button>
                        <button type="submit" class="inline-flex items-center justify-center h-12 px-6 rounded-xl font-semibold text-gray-950 bg-sky-400 hover:bg-sky-300" wire:loading.attr="disabled" wire:target="saveUser">
                            {{ $userId ? 'Save changes' : 'Create user' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
