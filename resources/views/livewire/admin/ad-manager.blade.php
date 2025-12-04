<div>
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

    {{-- Search and Filters --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div class="w-full md:w-96">
            <x-mary-input
                wire:model.live.debounce.300ms="search"
                placeholder="Szukaj ogłoszenia..."
                icon="o-magnifying-glass"
                clearable
            />
        </div>

        <div class="flex gap-2 flex-wrap">
            <flux:button
                wire:click="$set('filterStatus', 'all')"
                variant="{{ $filterStatus === 'all' ? 'primary' : 'ghost' }}"
                size="sm"
            >
                Wszystkie
            </flux:button>
            <flux:button
                wire:click="$set('filterStatus', 'active')"
                variant="{{ $filterStatus === 'active' ? 'primary' : 'ghost' }}"
                size="sm"
            >
                Aktywne
            </flux:button>
            <flux:button
                wire:click="$set('filterStatus', 'pending_payment')"
                variant="{{ $filterStatus === 'pending_payment' ? 'primary' : 'ghost' }}"
                size="sm"
            >
                Nieopłacone
            </flux:button>
            <flux:button
                wire:click="$set('filterStatus', 'expired')"
                variant="{{ $filterStatus === 'expired' ? 'primary' : 'ghost' }}"
                size="sm"
            >
                Wygasłe
            </flux:button>
            <flux:button
                wire:click="$set('filterStatus', 'inactive')"
                variant="{{ $filterStatus === 'inactive' ? 'primary' : 'ghost' }}"
                size="sm"
            >
                Nieaktywne
            </flux:button>
        </div>
    </div>

    {{-- Ads table --}}
    <x-mary-table :headers="$headers" :rows="$ads" with-pagination>
        @scope('cell_id', $ad)
            <span class="text-gray-600">{{ $ad->id }}</span>
        @endscope

        @scope('cell_title', $ad)
            <div class="font-medium">{{ $ad->title }}</div>
            @if($ad->code)
                <div class="text-xs text-gray-500">Kod: {{ $ad->code }}</div>
            @endif
        @endscope

        @scope('cell_category', $ad)
            <flux:badge color="zinc">{{ $ad->category->name ?? '—' }}</flux:badge>
        @endscope

        @scope('cell_user', $ad)
            <div>
                <div class="font-medium text-sm">{{ $ad->user->name ?? '—' }}</div>
                <div class="text-xs text-gray-500">{{ $ad->user->email ?? '' }}</div>
            </div>
        @endscope

        @scope('cell_location', $ad)
            <span class="text-gray-700">{{ $ad->location ?? '—' }}</span>
        @endscope

        @scope('cell_status', $ad)
            @if($ad->status === 'active')
                <flux:badge color="lime">Aktywne</flux:badge>
            @elseif($ad->status === 'pending_payment')
                <flux:badge color="red">Nieopłacone</flux:badge>
            @elseif($ad->status === 'expired')
                <flux:badge color="orange">Wygasłe</flux:badge>
            @elseif($ad->status === 'inactive')
                <flux:badge color="zinc">Nieaktywne</flux:badge>
            @else
                <flux:badge color="zinc">{{ ucfirst($ad->status) }}</flux:badge>
            @endif
        @endscope

        @scope('cell_created_at', $ad)
            <span class="text-sm text-gray-600">{{ $ad->created_at->format('d.m.Y') }}</span>
        @endscope

        @scope('cell_actions', $ad)
            <div class="flex justify-end gap-2">
                @if($ad->status === 'active')
                    <flux:button
                        wire:click="deactivate({{ $ad->id }})"
                        wire:confirm="Czy na pewno chcesz dezaktywować to ogłoszenie?"
                        size="sm"
                        variant="ghost"
                        icon="x-mark"
                        class="text-orange-600 hover:text-orange-800"
                    >
                    </flux:button>
                @else
                    <flux:button
                        wire:click="activate({{ $ad->id }})"
                        wire:confirm="Czy na pewno chcesz aktywować to ogłoszenie?"
                        size="sm"
                        variant="ghost"
                        icon="check"
                        class="text-green-600 hover:text-green-800"
                    >
                    </flux:button>
                @endif

                <flux:button
                    wire:click="delete({{ $ad->id }})"
                    wire:confirm="Czy na pewno chcesz usunąć ogłoszenie '{{ $ad->title }}'? Ta operacja jest nieodwracalna."
                    size="sm"
                    variant="ghost"
                    icon="trash"
                    class="text-red-600 hover:text-red-800"
                >
                </flux:button>
            </div>
        @endscope
    </x-mary-table>
</div>
