<x-layouts.administracja
:title="__('Administracja - Panel Administratora')" 
:description="__('')"
>
    <div class="min-h-screen">
        <flux:heading size="xl" level="1">Dzień dobry {{ auth()->user()->name }}</flux:heading>
        <flux:text class="mb-6 mt-2 text-base">Co dziś robimy?</flux:text>
        <flux:separator variant="subtle" />

    </div>
</x-layouts.administracja>