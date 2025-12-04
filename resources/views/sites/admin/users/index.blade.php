<x-layouts.administracja
:title="__('Uzytkownicy. Panel Administratora')"
:description="__('')"
>
    <div class="min-h-screen">
        <flux:heading size="xl" level="1">Użytkownicy</flux:heading>
        <flux:text class="mb-6 mt-2 text-base">Dodawanie, edycja i usuwanie użytkowników</flux:text>
        <flux:separator variant="subtle" class="mb-8" />

        <livewire:admin.user-manager />
    </div>

</x-layouts.administracja>