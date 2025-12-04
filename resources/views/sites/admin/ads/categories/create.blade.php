<x-layouts.administracja
:title="__('Dodaj kategorię ogłoszeń - Panel Administratora')"
:description="__('Tworzenie nowej kategorii ogłoszeń')"
>
    <div class="min-h-screen">
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-zinc-200">
            <div>
                <flux:heading size="xl">Dodaj kategorię</flux:heading>
                <flux:text class="mt-2 text-base text-zinc-600">Utwórz nową kategorię dla ogłoszeń.</flux:text>
            </div>
            <flux:button href="{{ route('admin.ads.categories.index') }}" variant="ghost" icon="arrow-left">
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

        <form action="{{ route('admin.ads.categories.store') }}" method="POST" class="space-y-6 max-w-3xl">
            @csrf

            <div class="bg-white rounded-lg border border-zinc-200 p-6 space-y-4">
                <flux:heading size="lg" class="mb-2">Dane kategorii</flux:heading>

                <x-mary-input
                    label="Nazwa kategorii"
                    name="name"
                    value="{{ old('name') }}"
                    required
                />

                <x-mary-input
                    label="Slug (opcjonalnie)"
                    name="slug"
                    value="{{ old('slug') }}"
                    hint="Pozostaw puste, aby wygenerować automatycznie"
                />
            </div>

            <div class="flex justify-end gap-4">
                <flux:button href="{{ route('admin.ads.categories.index') }}" variant="ghost">
                    Anuluj
                </flux:button>
                <flux:button type="submit" variant="primary">
                    Zapisz kategorię
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.administracja>
