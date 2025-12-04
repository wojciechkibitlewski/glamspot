<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Zdjęcie wyróżniające</label>
        <p class="text-sm text-gray-500 mb-3">Format: JPG, PNG, WEBP. Maksymalnie 10MB</p>
    </div>

    @if($existingImage)
        <div class="relative inline-block">
            <img src="{{ asset('storage/' . $existingImage) }}" alt="Featured image" class="h-48 w-auto rounded-lg border border-gray-200">
            <button
                type="button"
                wire:click="removeExisting"
                class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-colors shadow-lg"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @elseif($tempImagePath)
        <div class="relative inline-block">
            <img src="{{ asset('storage/' . $tempImagePath) }}" alt="Featured image" class="h-48 w-auto rounded-lg border border-gray-200">
            <button
                type="button"
                wire:click="remove"
                class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-colors shadow-lg"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @else
        <div
            x-data="{
                isDragging: false,
                onDrop(e) {
                    isDragging = false;
                    let files = e.dataTransfer.files;
                    if (files.length > 0) {
                        $refs.file.files = files;
                        $refs.file.dispatchEvent(new Event('change', {bubbles: true}));
                    }
                }
            }"
            x-on:dragover.prevent="isDragging = true"
            x-on:dragleave.prevent="isDragging = false"
            x-on:drop.prevent="onDrop($event)"
            x-on:click="$refs.file.click()"
            :class="isDragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:border-gray-400'"
            class="border-2 border-dashed rounded-lg p-8 text-center cursor-pointer transition-colors"
        >
            <input
                type="file"
                wire:model="image"
                accept="image/png,image/jpeg,image/webp"
                class="hidden"
                x-ref="file"
            >

            <div class="space-y-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-600 font-medium">Kliknij lub przeciągnij zdjęcie tutaj</p>
                <p class="text-sm text-gray-500">PNG, JPG, WEBP (maks. 10MB)</p>
            </div>

            <div wire:loading wire:target="image" class="mt-4">
                <div class="flex items-center justify-center space-x-2">
                    <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-sm text-gray-600">Wgrywanie zdjęcia...</span>
                </div>
            </div>
        </div>
    @endif

    @error('image')
        <p class="text-red-500 text-sm">{{ $message }}</p>
    @enderror
</div>
