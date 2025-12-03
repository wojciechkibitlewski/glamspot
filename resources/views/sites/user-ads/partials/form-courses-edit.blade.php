{{-- tytuł ogłoszenia  --}}
<div class="w-full">
    <flux:field>
        <flux:label class="font-semibold">Tytuł ogłoszenia <span class="text-red-500">*</span></flux:label>
        <flux:input name="title" value="{{ old('title', $ad->title) }}" />
        <flux:error name="title" />
    </flux:field>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-2">

    <flux:field class="mb-2">
        <flux:label class="font-semibold">Kategoria</flux:label>
        <flux:select name="category_id" x-model.number="category_id" @change="onCategoryChange" class="w-full rounded-md border border-zinc-300 p-2">
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" data-slug="{{ $cat->slug }}" @selected($ad->category_id === $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </flux:select>
    </flux:field>

    <flux:field class="mb-2">
        <flux:label class="font-semibold">Typ szkolenia</flux:label>
        <flux:select name="subcategory_id" x-model.number="subcategory_id" class="w-full rounded-md border border-zinc-300 p-2">
            <option value="">Wybierz typ</option>
            <template x-for="sub in filteredSubcategories" :key="sub.id">
                <option :value="sub.id" x-text="sub.name" :selected="sub.id === {{ (int) ($ad->training->subcategory_id ?? 0) }}"></option>
            </template>
        </flux:select>
    </flux:field>
</div>


<div class="w-full">
    <flux:label class="font-semibold block !mb-2">Specjalizacje (możesz wybrać wiele)</flux:label>
    @php
        // Zaznacz zapisane specjalizacje lub te przesłane ponownie po błędzie walidacji
        $checkedSpecs = array_map('intval', old('training_specializations', $selectedTrainingSpecIds ?? []));
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
        @foreach(($trainingSpecs ?? []) as $ts)
            <div class="flex gap-2 items-center">
                <input
                    type="checkbox"
                    value="{{ $ts->id }}"
                    name="training_specializations[]"
                    class="flex size-[1.125rem] rounded-[.3rem] mt-px outline-offset-2"
                    @if(in_array((int) $ts->id, $checkedSpecs, true)) checked @endif
                >
                <label class="text-body-regular-s">{{ $ts->name }}</label>
            </div>
        @endforeach
    </div>
</div>


<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <flux:field class="w-full">
        <flux:label class="font-semibold block">Lokalizacja / miasto</flux:label>
        <flux:input name="location" value="{{ old('location', $ad->location) }}" />
        <flux:error name="location" />
    </flux:field>
    
    <flux:fieldset>
        <flux:legend>Tryb</flux:legend>
        <div class="space-y-3">
            @php
                $isOnlineDefault = (bool) old('is_online', optional($ad->training)->is_online);
            @endphp
            <flux:switch 
                name="is_online" wire:model="is_online"
                :checked="$isOnlineDefault"
                value="1" 
                label="Online" 
                align="left">
            </flux:switch>
        </div>
    </flux:fieldset>
</div>
<div class="flex flex-row gap-4 w-full items-center">
    <flux:field class="w-full">
        <flux:label class="font-semibold block">Cena (PLN)</flux:label>
        <flux:input name="price" value="{{ old('price', optional($ad->training)->price) }}" />
        <flux:error name="price" />
    </flux:field>
    <flux:field class="w-full">
        <flux:label class="font-semibold block">Cena promocyjna (PLN)</flux:label>
        <flux:input name="promo_price" value="{{ old('promo_price', optional($ad->training)->promo_price) }}" />
        <flux:error name="promo_price" />
    </flux:field>
</div>

<div class="flex flex-col gap-4 w-full my-4">
    <div class="flex items-center mb-2 gap-4 justify-between">
        <flux:label class="font-semibold block">Terminy</flux:label>
        <flux:button icon:trailing="plus" @click="trainingDates.push({date: '', time: ''})">Dodaj termin</flux:button>
    </div>
    <template x-for="(t, i) in trainingDates" :key="i">
        <div class="flex items-center mb-2 gap-4 justify-between">
            <input type="date" :name="`training_dates[${i}][date]`" x-model="t.date" class="rounded-md border border-zinc-300 p-2" />
            <flux:button variant="danger" size="sm" @click="trainingDates.splice(i,1)">Usuń</flux:button>
        </div>
    </template>
</div>
{{-- 
<div class="w-full">
    <div class="flex items-center justify-between mb-2">
        <label class="font-semibold">Terminy</label>
        <button type="button" class="px-3 py-1 rounded-md border border-zinc-300" @click="trainingDates.push({date: '', time: ''})">Dodaj termin</button>
    </div>
    <template x-for="(t, i) in trainingDates" :key="i">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-2">
            <input type="date" :name="`training_dates[${i}][date]`" x-model="t.date" class="rounded-md border border-zinc-300 p-2" />
            <input type="time" :name="`training_dates[${i}][time]`" x-model="t.time" class="rounded-md border border-zinc-300 p-2" />
            <button type="button" class="px-3 py-1 rounded-md border border-zinc-300" @click="trainingDates.splice(i,1)">Usuń</button>
        </div>
    </template>
</div> --}}
<div class="flex flex-row gap-4 w-full items-center">
    <flux:field class="w-full">
        <flux:label class="font-semibold block">Liczba miejsc</flux:label>
        <flux:input name="seats" value="{{ old('seats', !is_null(optional($ad->training)->seats) ? optional($ad->training)->seats : (optional($ad->training)->audience ?? '')) }}" />
        <flux:error name="seats" />
    </flux:field>
    <flux:field class="w-full">
        
    </flux:field>
</div>
<div class="w-full">
    <flux:field>
        <flux:label class="font-semibold">Dla kogo?</flux:label>
        <flux:textarea name="audience" class="!min-h-[150px]">{{ old('audience', optional($ad->training)->audience) }}</flux:textarea>
    </flux:field>
    
</div>

<div class="w-full">
    <flux:field>
        <flux:label class="font-semibold">Opis szkolenia</flux:label>
        <flux:textarea name="description" class="!min-h-[150px]">{{ old('description', optional($ad->training)->description) }}</flux:textarea>
    </flux:field>
    
</div>

<div class="w-full">
    <flux:field>
        <flux:label class="font-semibold">Program</flux:label>
        <flux:textarea name="program" class="!min-h-[150px]">{{ old('program', optional($ad->training)->program) }}</flux:textarea>
    </flux:field>
    
</div>
<div class="w-full">
    <flux:field>
        <flux:label class="font-semibold">Bonusy</flux:label>
        <flux:textarea name="bonuses" class="!min-h-[150px]">{{ old('bonuses', optional($ad->training)->bonuses) }}</flux:textarea>
    </flux:field>
    
</div>

<div class="flex flex-row gap-4 w-full items-center">
    <div class="w-full">
        <flux:fieldset>
            <flux:legend>Certyfikat</flux:legend>
            <div class="space-y-3">
                @php
                    $certificateDefault = (bool) old('certificate', optional($ad->training)->has_certificate);
                @endphp
                <flux:switch 
                    name="certificate" wire:model="certificate"
                    :checked="$certificateDefault"
                    value="1" 
                    label="Tak" 
                    align="left">
                </flux:switch>
            </div>
        </flux:fieldset>
    </div>
    <div class="w-full">
    </div>
</div>

<div class="w-full">
    <flux:field>
        <flux:label class="font-semibold block !mb-2">Link do zapisów</flux:label>
        <flux:input.group>
            <flux:input name="signup_url" placeholder="example.com" value="{{ old('signup_url', optional($ad->training)->signup_url) }}" />
        </flux:input.group>
        <flux:error name="signup_url" />
    </flux:field>
</div>
