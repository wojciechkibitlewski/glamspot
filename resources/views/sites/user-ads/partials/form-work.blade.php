<div class="flex flex-col gap-4 w-full">
    <div class="w-full">
        <flux:field>
            <flux:label class="font-semibold">Specjalizacje (możesz wybrać wiele)</flux:label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                @foreach($industries as $industry)
                    <div class="flex gap-2 items-center">
                        <input 
                        type="checkbox" 
                        value="{{ $industry->id }}" 
                        name="job_specializations[]" 
                        class="flex size-[1.125rem] rounded-[.3rem] mt-px outline-offset-2"
                        @if(in_array($industry->id, old('job_specializations', []))) checked @endif
                        >
                        <label class="text-body-regular-s">{{ $industry->name }}</label>
                    </div>
                    
                    
                @endforeach
            </div>
        </flux:field>
    </div>

    <div class="w-full">
        <flux:field>
            <flux:label class="font-semibold">Forma współpracy (możesz wybrać wiele)</flux:label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                
                <div class="flex gap-2 items-center">
                    <input 
                    type="checkbox" 
                    value="Umowa o pracę" 
                    name="employment_form[]" 
                    class="flex size-[1.125rem] rounded-[.3rem] mt-px outline-offset-2"
                    @if(in_array('Umowa o pracę', old('employment_form', []))) checked @endif
                    >
                    <label class="text-body-regular-s">Umowa o pracę</label>
                </div>
                <div class="flex gap-2 items-center">
                    <input 
                    type="checkbox" 
                    value="Zlecenie" 
                    name="employment_form[]" 
                    class="flex size-[1.125rem] rounded-[.3rem] mt-px outline-offset-2"
                    @if(in_array('Zlecenie', old('employment_form', []))) checked @endif
                    >
                    <label class="text-body-regular-s">Zlecenie</label>
                </div>
                <div class="flex gap-2 items-center">
                    <input 
                    type="checkbox" 
                    value="Umowa B2B" 
                    name="employment_form[]" 
                    class="flex size-[1.125rem] rounded-[.3rem] mt-px outline-offset-2"
                    @if(in_array('Umowa B2B', old('employment_form', []))) checked @endif
                    >
                    <label class="text-body-regular-s">Umowa B2B</label>
                </div>
                <div class="flex gap-2 items-center">
                    <input 
                    type="checkbox" 
                    value="Wynajem stanowiska" 
                    name="employment_form[]" 
                    class="flex size-[1.125rem] rounded-[.3rem] mt-px outline-offset-2"
                    @if(in_array('Wynajem stanowiska', old('employment_form', []))) checked @endif
                    >
                    <label class="text-body-regular-s">Wynajem stanowiska</label>
                </div>
            </div>
        </flux:field>
    </div>

    <div class="w-full flex flex-col gap-2">
        <flux:field>
            <flux:label class="font-semibold">Wynagrodzenie brutto</flux:label>
            <div class="flex flex-row gap-4 w-full">
                <div class="w-full flex gap-2 items-center">
                    <flux:label class="">Od</flux:label>
                    <flux:input name="salary_from" type="number" value="{{ old('salary_from') }}"/>
                </div>
                <div class="w-full flex gap-2 items-center">
                    <flux:label class="">Do</flux:label>
                    <flux:input name="salary_to" type="number" value="{{ old('salary_to') }}"/>
                </div>
            </div>
        </flux:field>
    </div>
    <div class="w-full flex flex-col gap-2">
        <flux:field>
            <flux:label class="font-semibold">Lokalizacja/miasto</flux:label>
            <flux:input name="location" value="{{ old('location') }}" />
        </flux:field>
    </div>
    <div class="w-full flex flex-col gap-2">
        <flux:field>
            <flux:label class="font-semibold">Opis stanowiska / doświadczenia</flux:label>
            <flux:textarea name="experience_level" value="{{ old('experience_level') }}" class="!min-h-[150px]"/>
        </flux:field>
    </div>
    <div class="w-full flex flex-col gap-2">
        <flux:field>
            <flux:label class="font-semibold">Zakres obowiązków</flux:label>
            <flux:textarea name="scope" value="{{ old('scope') }}" class="!min-h-[150px]"/>
        </flux:field>
    </div>
    <div class="w-full flex flex-col gap-2">
        <flux:field>
            <flux:label class="font-semibold">Wymagania wobec kandydata / oczekiwania wobec pracodawcy</flux:label>
            <flux:textarea name="requirements" value="{{ old('requirements') }}" class="!min-h-[150px]"/>
        </flux:field>
    </div>
    <div class="w-full flex flex-col gap-2">
        <flux:field>
            <flux:label class="font-semibold">Co oferujemy?</flux:label>
            <flux:textarea name="benefits" value="{{ old('benefits') }}" class="!min-h-[150px]"/>
        </flux:field>
    </div>
</div>
