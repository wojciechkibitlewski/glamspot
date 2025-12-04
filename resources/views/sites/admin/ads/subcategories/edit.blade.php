<x-layouts.administracja
:title="__('Edytuj podkategorię - Panel Administratora')"
:description="__('Edycja podkategorii ogłoszeń')"
>
    <div class="min-h-screen">
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-zinc-200">
            <div>
                <flux:heading size="xl">Edytuj podkategorię</flux:heading>
                <flux:text class="mt-2 text-base text-zinc-600">{{ $subcategory->name }}</flux:text>
            </div>
            <flux:button href="{{ route('admin.ads.subcategories.index') }}" variant="ghost" icon="arrow-left">
                Powrót do podkategorii
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

        <form action="{{ route('admin.ads.subcategories.update', $subcategory) }}" method="POST" class="space-y-6 max-w-3xl">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-lg border border-zinc-200 p-6 space-y-4">
                <flux:heading size="lg" class="mb-2">Dane podkategorii</flux:heading>

                <label class="fieldset-legend mb-0.5 font-semibold">
                    Kategoria <span class="text-error">*</span>
                </label>
                <select
                    name="category_id"
                    class="select w-full"
                    required
                >
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $subcategory->category_id) == $cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                <x-mary-input
                    label="Nazwa podkategorii"
                    name="name"
                    value="{{ old('name', $subcategory->name) }}"
                    required
                />

                <x-mary-input
                    label="Slug (opcjonalnie)"
                    name="slug"
                    value="{{ old('slug', $subcategory->slug) }}"
                    hint="Pozostaw puste, aby wygenerować automatycznie"
                />
            </div>

            <div class="flex justify-end gap-4">
                <flux:button href="{{ route('admin.ads.subcategories.index') }}" variant="ghost">
                    Anuluj
                </flux:button>
                <flux:button type="submit" variant="primary">
                    Zapisz zmiany
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.administracja>
