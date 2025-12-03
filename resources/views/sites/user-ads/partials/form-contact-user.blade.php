<div>
    <flux:field>
        <flux:label>Imię i nazwisko <span class="text-red-500">*</span></flux:label>
        <flux:input name="person_name" value="{{ old('person_name', ($ad->person_name ?? auth()->user()->name)) }}" />
        <flux:error name="person_name" />
    </flux:field>
</div>
@php($personCity = old('person_city', ($ad->person_city ?? auth()->user()->city)))
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <flux:field>
            <flux:label>Miasto <span class="text-red-500">*</span></flux:label>
            <div x-data="citySearch(@js($personCity ?? ''))" class="relative">
                <input
                    type="text"
                    name="person_city"
                    x-model="value"
                    @input.debounce.300ms="onInput"
                    @focus="open = true"
                    @keydown.escape.window="open = false"
                    class="w-full rounded-md border border-zinc-300 p-2"
                    placeholder="Wpisz miasto"
                    value="{{ $personCity }}"
                    autocomplete="off"
                />
                <div x-show="open && suggestions.length" x-cloak class="absolute z-50 left-0 right-0 top-full mt-1 w-full rounded-md border border-zinc-200 bg-white shadow">
                    <template x-for="item in suggestions" :key="item.id">
                        <button type="button" class="block w-full text-left px-3 py-2 text-sm hover:bg-zinc-50" @click="select(item.name)" x-text="item.name"></button>
                    </template>
                </div>
            </div>
            <flux:error name="person_city" />
        </flux:field>
    </div>
    <div>
        <flux:field>
            <flux:label>Telefon <span class="text-red-500">*</span></flux:label>
            <flux:input name="person_phone" value="{{ old('person_phone', ($ad->person_phone ?? auth()->user()->phone)) }}"  />
            <flux:error name="person_phone" />
        </flux:field>
    </div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <flux:field>
            <flux:label>E-mail</flux:label>
            <flux:input name="person_email" value="{{ old('person_email', ($ad->person_email ?? auth()->user()->email)) }}" />
            <flux:error name="person_email" />
        </flux:field>
    </div>
</div>
<div class="mt-2">
    <label class="inline-flex items-center gap-2">
        <input type="checkbox" name="update_user" value="1" class="accent-zinc-800">
        <span class="text-sm text-zinc-600">Zapisz również w danych użytkownika</span>
    </label>
</div>
