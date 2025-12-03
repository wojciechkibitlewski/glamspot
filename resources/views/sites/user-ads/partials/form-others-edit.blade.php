{{-- tytuł ogłoszenia  --}}
<div class="w-full">
    <flux:field>
        <flux:label class="font-semibold">Tytuł ogłoszenia <span class="text-red-500">*</span></flux:label>
        <flux:input name="title" value="{{ old('title', $ad->title) }}" />
        <flux:error name="title" />
    </flux:field>
</div>

<input type="hidden" name="category_id" value="{{ (int) $ad->category_id }}" />

{{-- treść ogłoszenia --}}
<div class="w-full">
    <flux:field>
        <flux:label class="font-semibold">Treść ogłoszenia</flux:label>
        <flux:textarea name="description" class="!min-h-[150px]">{{ old('description', $ad->description) }}</flux:textarea>
        <flux:error name="description" />
    </flux:field>
</div>
