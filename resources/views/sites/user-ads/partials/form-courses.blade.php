<div class="flex flex-col gap-4 w-full">
    <div class="w-full">
        <flux:label class="font-semibold block !mb-2">Specjalizacje (możesz wybrać wiele)</flux:label>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
            @foreach(($trainingSpecs ?? []) as $ts)
                <div class="flex gap-2 items-center">
                    <input 
                    type="checkbox" 
                    value="{{ $ts->id }}" 
                    name="training_specializations[]" 
                    class="flex size-[1.125rem] rounded-[.3rem] mt-px outline-offset-2"
                    @if(in_array($ts->id, old('training_specializations', []))) checked @endif
                    >
                    <label class="text-body-regular-s">{{ $ts->name }}</label>
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex flex-row gap-4 w-full items-center">
        <flux:field class="w-full">
            <flux:label class="font-semibold block">Lokalizacja / miasto</flux:label>
            <flux:input name="location" x-model="location" value="{{ old('location') }}" />
            <flux:error name="location" />
        </flux:field>
        
        <div class="w-full">
            <flux:fieldset>
                <flux:legend class="text-sm! pb-1!">Tryb</flux:legend>
                <div class="space-y-3">
                    @php $isOnlineDefault = (bool) old('is_online'); @endphp
                    <flux:switch
                        name="is_online"
                        :checked="$isOnlineDefault"
                        value="1"
                        label="Online"
                        align="left"
                    />
                </div>
            </flux:fieldset>
        </div>
    </div>


    <div class="flex flex-row gap-4 w-full items-center">
        <flux:field class="w-full">
            <flux:label class="font-semibold block">Cena (PLN)</flux:label>
            <flux:input name="price" x-model="price" value="{{ old('price') }}" />
            <flux:error name="price" />
        </flux:field>
        <flux:field class="w-full">
            <flux:label class="font-semibold block">Cena promocyjna (PLN)</flux:label>
            <flux:input name="promo_price" x-model="promo_price" value="{{ old('promo_price') }}" />
            <flux:error name="promo_price" />
        </flux:field>
    </div>

    <div class="flex flex-col gap-4 w-full my-4">
        <div class="flex items-center mb-2 gap-4">
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

    <div class="flex flex-row gap-4 w-full items-center">
        <flux:field class="w-full">
            <flux:label class="font-semibold block">Liczba miejsc</flux:label>
            <flux:input name="seats" value="{{ old('seats') }}" />
            <flux:error name="seats" />
        </flux:field>
        <flux:field class="w-full">
            
        </flux:field>
    </div>

    <div class="w-full">
        <flux:field>
            <flux:label class="font-semibold">Dla kogo?</flux:label>
            <flux:textarea name="audience" x-model="audience" class="!min-h-[150px]">{{ old('audience') }}</flux:textarea>
        </flux:field>
        
    </div>

    <div class="w-full">
        <flux:field>
            <flux:label class="font-semibold">Opis szkolenia</flux:label>
            <flux:textarea name="description" x-model="description" class="!min-h-[150px]">{{ old('description') }}</flux:textarea>
        </flux:field>
        
    </div>

    <div class="w-full">
        <flux:field>
            <flux:label class="font-semibold">Program</flux:label>
            <flux:textarea name="program" x-model="program" class="!min-h-[150px]">{{ old('program') }}</flux:textarea>
        </flux:field>
       
    </div>

    <div class="w-full">
         <flux:field>
            <flux:label class="font-semibold">Bonusy</flux:label>
            <flux:textarea name="bonuses" x-model="bonuses" class="!min-h-[150px]">{{ old('bonuses') }}</flux:textarea>
        </flux:field>
        
    </div>


    <div class="w-full">
        <flux:fieldset>
            <flux:legend class="text-sm! pb-1!">Certyfikat</flux:legend>
            <div class="space-y-3">
                @php $certificateDefault = (bool) old('certificate'); @endphp
                <flux:switch
                    name="certificatee"
                    :checked="$certificateDefault"
                    value="1"
                    label="Tak"
                    align="left"
                />
            </div>
        </flux:fieldset>
    </div>


    <div class="w-full">
        <flux:field>
            <flux:label class="font-semibold block !mb-2">Link do zapisów</flux:label>
            <flux:input.group>
                <flux:input.group.prefix>https://</flux:input.group.prefix>
                <flux:input name="signup_url" placeholder="example.com" value="{{ old('signup_url') }}" />
            </flux:input.group>
            <flux:error name="signup_url" />
        </flux:field>
    </div>
</div>
