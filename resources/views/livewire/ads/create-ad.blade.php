<div class="space-y-6 w-full md:w-xl" x-data x-on:ad-authenticated.window="$wire.$refresh()">
    @guest
        <div class="space-y-4">
            <livewire:auth.login-ad />
        </div>
    @else
        <div class="space-y-2"> 
            <flux:heading size="xl">Dodaj ogłoszenie</flux:heading> 
            @if ($category_id)
            @else

                <flux:text class="text-sm text-zinc-500">Najpierw wybierz kategorię, a następnie uzupełnij szczegóły.</flux:text> 
            @endif
        </div>
        
        <form wire:submit.prevent="save" class="grid gap-5">
            <flux:field class="mb-2">
                <flux:label class="font-semibold">Kategoria</flux:label>
                <flux:select wire:model.live="category_id" placeholder="">
                    <flux:select.option>Wybierz kategorię</flux:select.option>
                    @foreach($categories as $cat)
                        <flux:select.option value="{{ $cat->id }}">{{ $cat->name }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="category_id" />
            </flux:field>

            @if ($category_id)
                @switch($selectedSlug)
                    @case('praca')
                        @include('livewire.ads.parts.form-job')
                        @break

                    @case('szkolenia')
                        @include('livewire.ads.parts.form-training')
                        @break

                    @case('urzadzenia-i-sprzet')
                        @include('livewire.ads.parts.form-machines')
                        @break

                    @default
                        @include('livewire.ads.parts.form-default')
                @endswitch
            @endif
            
        </form>

    @endguest
</div>
