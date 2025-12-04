<x-layouts.administracja
:title="__('Kategorie bloga - Panel Administratora')"
:description="__('Zarządzanie kategoriami wpisów')"
>
    <div class="min-h-screen">
        {{-- Header --}}
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-zinc-200">
            <div>
                <flux:heading size="xl">Kategorie bloga</flux:heading>
                <flux:text class="mt-2 text-base text-zinc-600">Przeglądaj, dodawaj i edytuj kategorie wpisów.</flux:text>
            </div>
            <flux:button href="{{ route('admin.blog.categories.create') }}" icon="plus" variant="primary">
                Dodaj kategorię
            </flux:button>
        </div>

        {{-- Messages --}}
        @if (session('message'))
            <x-mary-alert icon="o-check-circle" class="alert-success mb-4">
                {{ session('message') }}
            </x-mary-alert>
        @endif

        @if ($errors->any())
            <x-mary-alert icon="o-exclamation-triangle" class="alert-error mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-mary-alert>
        @endif

        <div class="bg-white rounded-lg border border-zinc-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-zinc-200">
                    <thead class="bg-zinc-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider">Nazwa</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider">Slug</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider">Posty</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-zinc-500 uppercase tracking-wider">Akcje</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200">
                        @forelse ($categories as $category)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-800">
                                    {{ $category->category }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600">
                                    {{ $category->slug ?: '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600">
                                    {{ $category->posts_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <flux:button href="{{ route('admin.blog.categories.edit', $category) }}" size="sm" variant="ghost" icon="pencil">
                                            Edytuj
                                        </flux:button>
                                        <form action="{{ route('admin.blog.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Usunąć kategorię {{ $category->category }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <flux:button type="submit" size="sm" variant="ghost" icon="trash" class="text-red-600">
                                                Usuń
                                            </flux:button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-zinc-500">
                                    Brak kategorii. Dodaj pierwszą, aby rozpocząć.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</x-layouts.administracja>
