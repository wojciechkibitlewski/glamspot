<div class="w-full">

    <div
        x-data="{
            isDragging: false,
            onDrop(e) {
                this.isDragging = false;
                if (e.dataTransfer.files && e.dataTransfer.files.length) {
                    this.$refs.file.files = e.dataTransfer.files;
                    this.$refs.file.dispatchEvent(new Event('change'));
                }
            }
        }"
        x-on:dragover.prevent="isDragging = true"
        x-on:dragleave.prevent="isDragging = false"
        x-on:drop.prevent="onDrop($event)"
        x-on:click="$refs.file.click()"
        :class="isDragging ? 'ring-2 ring-accent ring-offset-2' : ''"
        class="flex flex-col gap-2 w-full justify-center items-center bg-gray-100 border border-gray-400 border-dashed rounded-lg p-6 cursor-pointer select-none"
    >
        <flux:icon.cloud-arrow-up variant="solid"/>
        <span class="text-body-medium-xs">Przeciągnij i upuść zdjęcia</span>
        <span class="text-body-regular-xs">PNG, JPG, WEBP, do 10MB (max 10 plików)</span>

        <input type="file" x-ref="file" wire:model="photos" accept="image/png,image/jpeg,image/webp" class="hidden" id="ad-photos-input" multiple>
    </div>

    @php $hasExisting = !empty($existing); @endphp

    @if ($hasExisting)
        <div class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-4">
            @foreach ($existing as $path)
                <div class="relative group">
                    <img src="{{ asset('storage/'.$path) }}" alt="Zdjęcie" class="w-full h-32 object-cover rounded-lg border" />
                    <button type="button" wire:click="removeExisting('{{ $path }}')" class="absolute top-2 right-2 bg-red-600 text-white rounded-md px-2 py-1 opacity-0 group-hover:opacity-100 transition">Usuń</button>
                </div>
            @endforeach
        </div>
    @endif

    <div class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-4" wire:loading.remove wire:target="photos">
        @forelse ($queued as $path)
            <div class="relative group">
                <img src="{{ asset('storage/'.$path) }}" alt="Zdjęcie" class="w-full h-32 object-cover rounded-lg border" />
                <button type="button" wire:click="remove('{{ $path }}')" class="absolute top-2 right-2 bg-red-600 text-white rounded-md px-2 py-1 opacity-0 group-hover:opacity-100 transition">Usuń</button>
            </div>
        @empty
            @unless($hasExisting)
                <div class="col-span-full text-sm text-zinc-500">Brak dodanych zdjęć</div>
            @endunless
        @endforelse
    </div>
    <div wire:loading.flex wire:target="photos" class="text-sm text-zinc-500 mt-2">Wgrywanie zdjęć...</div>

    <flux:error name="photos" />
</div>
