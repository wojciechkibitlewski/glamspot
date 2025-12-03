<div>
    <flux:field>
        <flux:label>Nazwa firmy</flux:label>
        <flux:input readonly name="company_name" value="{{ old('company_name', ($ad->company_name ?? $firm->firm_name)) }}" />
        <flux:error name="company_name" />
    </flux:field>
</div>
@php($companyCity = old('company_city', ($ad->company_city ?? $firm->firm_city)))
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <flux:field>
            <flux:label>Adres</flux:label>
            <flux:input name="company_address" value="{{ old('company_address', ($ad->company_address ?? $firm->firm_address)) }}" />
            <flux:error name="company_address" />
        </flux:field>
    </div>
    <div>
        <flux:field>
            <flux:label>Kod pocztowy</flux:label>
            <flux:input name="company_postalcode" value="{{ old('company_postalcode', ($ad->company_postalcode ?? $firm->firm_postalcode)) }}" />
            <flux:error name="company_postalcode" />
        </flux:field>
    </div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <flux:field>
            <flux:label>Województwo</flux:label>
            @php($regions = App\Models\Region::orderBy('name')->get(['name']))
            @php($selectedCompanyRegion = old('company_region', ($ad->company_region ?? $firm->firm_region)))
            <flux:select name="company_region">
                <option value="">— wybierz —</option>
                @foreach($regions as $r)
                    <option value="{{ $r->name }}" @selected($selectedCompanyRegion === $r->name)>{{ $r->name }}</option>
                @endforeach
            </flux:select>
            <flux:error name="company_region" />
        </flux:field>
    </div>
    <div x-data="citySearch(@js($companyCity ?? ''))" class="relative">
        <flux:field>
            <flux:label>Miasto</flux:label>
            <input
                type="text"
                name="company_city"
                x-model="value"
                @input.debounce.300ms="onInput"
                @focus="open = true"
                @keydown.escape.window="open = false"
                class="w-full rounded-md border border-zinc-300 p-2"
                placeholder="Wpisz miasto"
                value="{{ $companyCity }}"
                autocomplete="off"
            />
            <flux:error name="company_city" />
        </flux:field>
        <div x-show="open && suggestions.length" x-cloak class="absolute z-50 left-0 right-0 top-full mt-1 w-full rounded-md border border-zinc-200 bg-white shadow">
            <template x-for="item in suggestions" :key="item.id">
                <button type="button" class="block w-full text-left px-3 py-2 text-sm hover:bg-zinc-50" @click="select(item.name)" x-text="item.name"></button>
            </template>
        </div>
    </div>
    
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <flux:field>
            <flux:label>Telefon</flux:label>
            <flux:input name="company_phone" value="{{ old('company_phone', ($ad->company_phone ?? $firm->firm_phone ?? auth()->user()->phone)) }}" />
            <flux:error name="company_phone" />
        </flux:field>
     </div>
     <div>
        <flux:field>
            <flux:label>E-mail</flux:label>
            <flux:input name="company_email" value="{{ old('company_email', ($ad->company_email ?? $firm->firm_email ?? auth()->user()->email)) }}" />
            <flux:error name="company_email" />
        </flux:field>
    </div>
</div>
<div class="mt-2">
    <label class="inline-flex items-center gap-2">
        <input type="checkbox" name="update_firm" value="1" class="accent-zinc-800">
        <span class="text-sm text-zinc-600">Zapisz również w danych firmy</span>
    </label>
</div>
