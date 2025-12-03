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
        <flux:label class="font-semibold">Podkategoria</flux:label>
        <flux:select name="subcategory_id" x-model.number="subcategory_id" class="w-full rounded-md border border-zinc-300 p-2">
            <option value="">Wybierz podkategorię</option>
            <template x-for="sub in filteredSubcategories" :key="sub.id">
                <option :value="sub.id" x-text="sub.name" :selected="sub.id === {{ (int) ($selectedSubcategoryId ?? 0) }}"></option>
            </template>
        </flux:select>
    </flux:field>
</div>

<div class="mb-2 w-full">
    <label class="font-semibold block mb-2">Specjalizacje (możesz wybrać wiele)</label>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
        @php $jobSpecIds = optional($ad->job)->specializations?->pluck('id')->all() ?? []; @endphp
        @foreach($industries as $industry)
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="job_specializations[]" value="{{ $industry->id }}" class="accent-zinc-800" @checked(in_array($industry->id, $jobSpecIds))>
                <span>{{ $industry->name }}</span>
            </label>
        @endforeach
    </div>
</div>

<div class="w-full">
    <label class="font-semibold block mb-2">Forma współpracy (możesz wybrać wiele)</label>
    @php $efList = array_filter(array_map('trim', explode(',', optional($ad->job)->employment_form ?? ''))); @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="employment_form[]" value="Umowa o pracę" class="accent-zinc-800" @checked(in_array('Umowa o pracę', $efList))>
            <span>Umowa o pracę</span>
        </label>
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="employment_form[]" value="Zlecenie" class="accent-zinc-800" @checked(in_array('Zlecenie', $efList))>
            <span>Zlecenie</span>
        </label>
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="employment_form[]" value="B2B" class="accent-zinc-800" @checked(in_array('B2B', $efList))>
            <span>B2B</span>
        </label>
        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="employment_form[]" value="Wynajem stanowiska" class="accent-zinc-800" @checked(in_array('Wynajem stanowiska', $efList))>
            <span>Wynajem stanowiska</span>
        </label>
    </div>
</div>

<div class="w-full">
    <label class="font-semibold block mb-1">Lokalizacja/miasto</label>
    <input type="text" name="location" value="{{ old('location', $ad->location) }}" class="w-full rounded-md border border-zinc-300 p-2" />
</div>

<div class="w-full">
    <label class="font-semibold block mb-1">Wynagrodzenie</label>
    <div class="flex flex-row gap-4 w-full">
        <div class="w-full">
            <label class="block mb-1">Od (w zł)</label>
            <input type="number" step="0.01" name="salary_from" value="{{ old('salary_from', optional($ad->job)->salary_from ?? $ad->price_from) }}" class="w-full rounded-md border border-zinc-300 p-2" />
        </div>
        <div class="w-full">
            <label class="block mb-1">Do (w zł)</label>
            <input type="number" step="0.01" name="salary_to" value="{{ old('salary_to', optional($ad->job)->salary_to ?? $ad->price_to) }}" class="w-full rounded-md border border-zinc-300 p-2" />
        </div>
    </div>
</div>

<div class="w-full">
    <label class="font-semibold block mb-1">Opis stanowiska / doświadczenia</label>
    <textarea name="experience_level" class="w-full rounded-md border border-zinc-300 p-2" rows="5">{{ old('experience_level', optional($ad->job)->experience_level) }}</textarea>
</div>
<div class="w-full">
    <label class="font-semibold block mb-1">Zakres obowiązków</label>
    <textarea name="scope" class="w-full rounded-md border border-zinc-300 p-2" rows="5">{{ old('scope', $ad->description) }}</textarea>
</div>
<div class="w-full">
    <label class="font-semibold block mb-1">Wymagania wobec kandydata</label>
    <textarea name="requirements" class="w-full rounded-md border border-zinc-300 p-2" rows="5">{{ old('requirements', optional($ad->job)->requirements) }}</textarea>
</div>
<div class="w-full">
    <label class="font-semibold block mb-1">Co oferujemy?</label>
    <textarea name="benefits" class="w-full rounded-md border border-zinc-300 p-2" rows="5">{{ old('benefits', optional($ad->job)->benefits) }}</textarea>
</div>