<x-layouts.administracja
:title="__('Ogłoszenia. Panel Administratora')"
:description="__('')"
>
    <div class="min-h-screen">
        <flux:heading size="xl" level="1">Ogłoszenia</flux:heading>
        <flux:text class="mb-6 mt-2 text-base">Zarządzanie ogłoszeniami - edycja, dezaktywacja i usuwanie</flux:text>
        <flux:separator variant="subtle" class="mb-8" />

        <livewire:admin.ad-manager />
    </div>

</x-layouts.administracja>