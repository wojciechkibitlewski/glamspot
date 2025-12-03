<div>
    <flux:field>
        <flux:label class="font-semibold">Treść ogłoszenia</flux:label>
        <flux:textarea name="description" class="!min-h-[150px]">{{ old('description') }}</flux:textarea>
        <flux:error name="description" />
    </flux:field>

</div>
