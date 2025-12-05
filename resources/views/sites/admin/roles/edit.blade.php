<x-layouts.administracja
:title="__('Edytuj rolę - Panel Administratora')"
:description="__('Edycja roli użytkownika')"
>
    <div class="min-h-screen">
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-zinc-200">
            <div>
                <flux:heading size="xl">Edytuj rolę</flux:heading>
                <flux:text class="mt-2 text-base text-zinc-600">{{ $role->name }}</flux:text>
            </div>
            <flux:button href="{{ route('admin.roles.index') }}" variant="ghost" icon="arrow-left">
                Powrót do listy
            </flux:button>
        </div>

        @if ($errors->any())
            <x-mary-alert icon="o-exclamation-triangle" class="alert-error mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-mary-alert>
        @endif

        <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="space-y-6 max-w-xl">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-lg border border-zinc-200 p-6 space-y-4">
                <flux:heading size="lg" class="mb-2">Dane roli</flux:heading>

                <x-mary-input
                    label="Nazwa roli"
                    name="name"
                    value="{{ old('name', $role->name) }}"
                    required
                />
            </div>

            <div class="flex justify-end gap-4">
                <flux:button href="{{ route('admin.roles.index') }}" variant="ghost">
                    Anuluj
                </flux:button>
                <flux:button type="submit" variant="primary">
                    Zapisz zmiany
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.administracja>
