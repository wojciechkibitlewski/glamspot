<div x-data @user-created.window="$wire.$refresh()" @user-updated.window="$wire.$refresh()">
    {{-- Flash messages --}}
    @if (session()->has('message'))
        <x-mary-alert icon="o-check-circle" class="alert-success mb-4">
            {{ session('message') }}
        </x-mary-alert>
    @endif

    @if (session()->has('error'))
        <x-mary-alert icon="o-exclamation-triangle" class="alert-error mb-4">
            {{ session('error') }}
        </x-mary-alert>
    @endif

    {{-- Search and Add button --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div class="w-full md:w-96">
            <x-mary-input
                wire:model.live.debounce.300ms="search"
                placeholder="Szukaj użytkownika..."
                icon="o-magnifying-glass"
                clearable
            />
        </div>
        <flux:button wire:click="openCreateModal" icon="plus" variant="primary" class="bg-slate-400! cursor-pointer!">
            Dodaj użytkownika
        </flux:button>
    </div>

    {{-- Users table --}}
    <x-mary-table :headers="$headers" :rows="$users" with-pagination>
        @scope('cell_id', $user)
            <span class="text-gray-600">{{ $user->id }}</span>
        @endscope

        @scope('cell_name', $user)
            <div class="flex items-center gap-3">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" class="w-10 h-10 rounded-full object-cover" alt="{{ $user->name }}">
                @else
                    <flux:avatar size="sm" circle :name="$user->name" />
                @endif
                <div>
                    <div class="font-medium">{{ $user->name }}</div>
                </div>
            </div>
        @endscope

        @scope('cell_email', $user)
            <a href="mailto:{{ $user->email }}" class="text-blue-600 hover:text-blue-800">
                {{ $user->email }}
            </a>
        @endscope

        @scope('cell_city', $user)
            <span class="text-gray-700">{{ $user->city ?? '—' }}</span>
        @endscope

        @scope('cell_email_verified_at', $user)
            @if($user->email_verified_at)
                <flux:badge color="lime">Aktywny</flux:badge>
            @else
                <flux:badge color="red">Nieaktywny</flux:badge>
            @endif
        @endscope

        @scope('cell_ads_count', $user)
            <div class="text-center">
                @if($user->ads_count > 0)
                    <flux:badge color="fuchsia">{{ $user->ads_count }}</flux:badge>
                @else
                    <span class="text-gray-400">—</span>
                @endif
            </div>
        @endscope

        @scope('cell_actions', $user)
            <div class="flex justify-end gap-2">
                <x-mary-button icon="o-pencil" wire:click="openEditModal({{ $user->id }})" spinner class="btn-sm" />
                <x-mary-button icon="o-trash" wire:click="delete({{ $user->id }})" spinner class="btn-sm" wire:confirm="Czy na pewno chcesz usunąć użytkownika {{ $user->name }}?" />
                
            </div>
        @endscope
    </x-mary-table>

    {{-- Modal for Create/Edit --}}
    <x-mary-modal wire:model="showModal" title="{{ $editMode ? 'Edytuj użytkownika' : 'Dodaj użytkownika' }}" class="backdrop-blur" persistent>
        <form id="user-form" wire:submit.prevent="save" x-on:submit="console.log('Form submitting...')">
            <div class="space-y-4">
                <x-mary-input
                    label="Imię i nazwisko"
                    wire:model.defer="name"
                />
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                <x-mary-input
                    label="Email"
                    type="email"
                    wire:model.defer="email"
                />
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                <x-mary-input
                    label="Miasto"
                    wire:model.defer="city"
                />
                @error('city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                <x-mary-input
                    label="Telefon"
                    wire:model.defer="phone"
                />
                @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                <div class="border-t pt-4 mt-4">
                    <p class="text-sm text-gray-600 mb-3">
                        {{ $editMode ? 'Pozostaw puste, jeśli nie chcesz zmieniać hasła' : 'Ustaw hasło dla nowego użytkownika' }}
                    </p>

                    <x-mary-input
                        label="Hasło"
                        type="password"
                        wire:model.defer="password"
                    />
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    <x-mary-input
                        label="Potwierdź hasło"
                        type="password"
                        wire:model.defer="password_confirmation"
                        class="mt-4"
                    />
                    @error('password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <x-slot:actions>
                <flux:button type="button" wire:click="closeModal" variant="ghost">
                    Anuluj
                </flux:button>
                <flux:button type="submit" variant="primary" form="user-form">
                    {{ $editMode ? 'Zapisz zmiany' : 'Dodaj użytkownika' }}
                </flux:button>
            </x-slot:actions>
        </form>
    </x-mary-modal>
</div>
