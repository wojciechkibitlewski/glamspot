{{-- tytuł ogłoszenia  --}}
<div class="w-full">
    <flux:field>
        <flux:label class="font-semibold">Tytuł ogłoszenia <span class="text-red-500">*</span></flux:label>
        <flux:input name="title" value="{{ old('title', $ad->title) }}" />
        <flux:error name="title" />
    </flux:field>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-2">
    {{-- kategoria  --}}
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
        <flux:select name="subcategory_id" x-model.number="subcategory_id" @change="onCategoryChange" class="w-full rounded-md border border-zinc-300 p-2">
            <option value="">Wybierz podkategorię</option>
            <template x-for="sub in filteredSubcategories" :key="sub.id">
                <option :value="sub.id" :data-type-slug="sub.slug" x-text="sub.name" :selected="Number(sub.id) === Number(subcategory_id)"></option>
            </template>
        </flux:select>
    </flux:field>
</div>
{{-- Devices: fields --}}
<template x-if="selectedSlug === 'urzadzenia-i-sprzet'">
    @include('site.user-ads.partials.form-devices')
</template>
