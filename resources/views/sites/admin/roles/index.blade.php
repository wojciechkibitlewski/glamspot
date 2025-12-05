<x-layouts.administracja
:title="__('Role użytkowników - Panel Administratora')"
:description="__('Zarządzanie rolami użytkowników')"
>
    <div class="min-h-screen">
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-zinc-200">
            <div>
                <flux:heading size="xl">Role użytkowników</flux:heading>
                <flux:text class="mt-2 text-base text-zinc-600">Twórz i edytuj role systemowe.</flux:text>
            </div>
            <flux:button href="{{ route('admin.roles.create') }}" icon="plus" variant="primary">
                Dodaj rolę
            </flux:button>
        </div>

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
                            <th class="px-6 py-3 text-left text-xs font-semibold text-zinc-500 uppercase tracking-wider">Guard</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-zinc-500 uppercase tracking-wider">Akcje</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200">
                        @forelse ($roles as $role)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-800">
                                    {{ $role->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600">
                                    {{ $role->guard_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <flux:button href="{{ route('admin.roles.edit', $role) }}" size="sm" variant="ghost" icon="pencil">
                                            Edytuj
                                        </flux:button>
                                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" onsubmit="return confirm('Usunąć rolę {{ $role->name }}?')">
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
                                <td colspan="3" class="px-6 py-8 text-center text-zinc-500">
                                    Brak ról. Dodaj pierwszą, aby rozpocząć.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">
                {{ $roles->links() }}
            </div>
        </div>
    </div>
</x-layouts.administracja>
