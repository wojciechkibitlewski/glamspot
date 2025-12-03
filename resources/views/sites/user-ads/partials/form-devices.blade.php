@php $machine = isset($ad) ? optional($ad->machines) : null; @endphp
<div class="flex flex-col gap-6 w-full">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        {{-- Nowe / Używane → wybór rodzaju ogłoszenia (Sprzedam / Kupię) --}}
        <template x-if="selectedSubSlug === 'urzadzenia-nowe' || selectedSubSlug === 'urzadzenia-uzywane'">
            <div class="w-full">
                <flux:field>
                    <flux:label class="font-semibold">Rodzaj ogłoszenia</flux:label>
                    <flux:select name="availability_type" class="w-full rounded-md border border-zinc-300 p-2">
                        <option value="">Wybierz…</option>
                        <option value="sale" @selected(old('availability_type', $machine?->availability_type)==='sale')>Sprzedam</option>
                        <option value="buy" @selected(old('availability_type', $machine?->availability_type)==='buy')>Kupię</option>
                    </flux:select>
                </flux:field>
            </div>
        </template>
        {{-- Stan urządzenia (dla używanych dostępna dodatkowa opcja „wymaga naprawy”) --}}
        <template x-if="selectedSubSlug === 'urzadzenia-uzywane' || selectedSubSlug === 'urzadzenia-na-wynajem'">
            <div class="w-full">
                <flux:field>
                    <flux:label class="font-semibold">Stan urządzenia</flux:label>
                    <flux:select name="state" class="w-full rounded-md border border-zinc-300 p-2">
                        <option value="">Wybierz stan…</option>
                        <option value="bardzo_dobry" @selected(old('state', $machine?->state)==='bardzo_dobry')>bardzo dobry</option>
                        <option value="dobry" @selected(old('state', $machine?->state)==='dobry')>dobry</option>
                        <template x-if="selectedSubSlug === 'urzadzenia-uzywane'">
                            <option value="wymaga_naprawy" @selected(old('state', $machine?->state)==='wymaga_naprawy')>wymaga naprawy</option>
                        </template>
                        
                    </flux:select>
                </flux:field>
            </div>
        </template>
    </div>
    {{-- Opis ogłoszenia --}}
    <div class="w-full">
        <flux:field>
            <flux:label class="font-semibold">Treść ogłoszenia</flux:label>
            <flux:textarea name="description" class="!min-h-[150px]">{{ old('description', $ad->description ?? '') }}</flux:textarea>
        </flux:field>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        {{-- Cena --}}
        <div class="w-full">
            <flux:field>
                <flux:label class="font-semibold">Cena (PLN)</flux:label>
                <flux:input name="price_from" type="number" step="0.01" min="0" value="{{ old('price_from', $ad->price_from ?? null) }}" />
            </flux:field>
        </div>
        {{-- Leasing / raty --}}
        <div class="w-full">
            <flux:fieldset>
                <flux:legend>Leasing / raty</flux:legend>
                <div class="space-y-3">
                    @php
                        $isChecked = (bool) old('deposit_required', $machine?->deposit_required);
                    @endphp
                    <flux:switch 
                        {{-- checked data-checked aria-checked="true" --}}
                        name="deposit_required" wire:model="deposit_required"
                        :checked="$isChecked"
                        value="1" 
                        label="Tak, istnieje taka możliwość" 
                        align="left">
                    </flux:switch>
                </div>
            </flux:fieldset>
        </div>
    </div>
    

    {{-- Na wynajem → jednostka rozliczenia (godziny/dni/tygodnie/miesiące) --}}
    <template x-if="selectedSubSlug === 'urzadzenia-na-wynajem'">
        <div class="w-full">
            <flux:label class="font-semibold block mb-2!">Okres rozliczenia</flux:label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <label class="flex items-center gap-2">
                    <input type="radio" name="price_unit" value="hour" class="flex size-4.5 rounded-full mt-px outline-offset-2" @checked(old('price_unit', $machine?->price_unit)==='hour')>
                    <span>za godzinę</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="price_unit" value="day" class="flex size-4.5 rounded-full mt-px outline-offset-2" @checked(old('price_unit', $machine?->price_unit)==='day')>
                    <span>za dzień</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="price_unit" value="week" class="flex size-4.5 rounded-full mt-px outline-offset-2" @checked(old('price_unit', $machine?->price_unit)==='week')>
                    <span>za tydzień</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="price_unit" value="month" class="flex size-4.5 rounded-full mt-px outline-offset-2" @checked(old('price_unit', $machine?->price_unit)==='month')>
                    <span>za miesiąc</span>
                </label>
            </div>
        </div>
    </template>
    

</div>
