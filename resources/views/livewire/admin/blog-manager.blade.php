<div x-data @post-created.window="$wire.$refresh()" @post-updated.window="$wire.$refresh()">
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
                placeholder="Szukaj artykułu..."
                icon="o-magnifying-glass"
                clearable
            />
        </div>

        <div class="flex gap-2">
            <flux:button
                wire:click="$set('filterPublished', 'all')"
                variant="{{ $filterPublished === 'all' ? 'primary' : 'ghost' }}"
                size="sm"
            >
                Wszystkie
            </flux:button>
            <flux:button
                wire:click="$set('filterPublished', 'published')"
                variant="{{ $filterPublished === 'published' ? 'primary' : 'ghost' }}"
                size="sm"
            >
                Opublikowane
            </flux:button>
            <flux:button
                wire:click="$set('filterPublished', 'draft')"
                variant="{{ $filterPublished === 'draft' ? 'primary' : 'ghost' }}"
                size="sm"
            >
                Szkice
            </flux:button>
            <flux:button href="{{ route('admin.blog.create') }}" icon="plus" variant="primary" class="bg-slate-400! cursor-pointer!">
                Dodaj artykuł
            </flux:button>
        </div>
    </div>

    {{-- Posts table --}}
    <x-mary-table :headers="$headers" :rows="$posts" with-pagination>
        @scope('cell_id', $post)
            <span class="text-gray-600">{{ $post->id }}</span>
        @endscope

        @scope('cell_title', $post)
            <div class="font-medium">{{ $post->title }}</div>
            @if($post->code)
                <div class="text-xs text-gray-500">Kod: {{ $post->code }}</div>
            @endif
        @endscope

        @scope('cell_category', $post)
            <flux:badge color="zinc">{{ $post->category->category ?? '—' }}</flux:badge>
        @endscope

        @scope('cell_is_published', $post)
            @if($post->is_published)
                <flux:badge color="lime">Opublikowany</flux:badge>
            @else
                <flux:badge color="zinc">Szkic</flux:badge>
            @endif
        @endscope

        @scope('cell_published_at', $post)
            @if($post->published_at)
                <span class="text-sm text-gray-600">{{ $post->published_at->format('d.m.Y H:i') }}</span>
            @else
                <span class="text-sm text-gray-400">—</span>
            @endif
        @endscope

        @scope('cell_actions', $post)
            <div class="flex justify-end gap-2">
                <flux:button
                    href="{{ route('admin.blog.edit', $post->id) }}"
                    size="sm"
                    variant="ghost"
                    icon="pencil"
                >
                </flux:button>

                <flux:button
                    wire:click="delete({{ $post->id }})"
                    wire:confirm="Czy na pewno chcesz usunąć artykuł '{{ $post->title }}'? Ta operacja jest nieodwracalna."
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
