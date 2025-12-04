<x-layouts.administracja
:title="__('Blog. Panel Administratora')"
:description="__('')"
>
    <div class="min-h-screen">
        <flux:heading size="xl" level="1">Blog</flux:heading>
        <flux:text class="mb-6 mt-2 text-base">Zarządzanie artykułami - dodawanie, edycja i usuwanie</flux:text>
        <flux:separator variant="subtle" class="mb-8" />

        <livewire:admin.blog-manager />
    </div>

</x-layouts.administracja>
