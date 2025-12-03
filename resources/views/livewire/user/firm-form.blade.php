<div class="grid gap-6">
    <form wire:submit="save" class="grid md:grid-cols-2 gap-4" enctype="multipart/form-data">
        <div class="md:col-span-2">
            <flux:input wire:model="firm_name" :label="__('user-account.firm_name')" required placeholder="{{__('user-account.firm_name')}}" />
            <flux:error name="firm_name" />
        </div>
        <div x-data="citySearchFirm('firm_region_id', @js($firm_city))" class="relative">
            <flux:label>{{__('user-account.city')}}</flux:label>
            <input
                type="text"
                name="firm_city"
                wire:model.live="firm_city"
                x-model="value"
                @input.debounce.300ms="onInput"
                @focus="open = true"
                @keydown.escape.window="open = false"
                class="w-full rounded-md border border-zinc-300 p-2"
                placeholder="{{__('user-account.firm_city.placeholder')}}"
            />
            <flux:error name="firm_city" />
            <div x-show="open && suggestions.length" x-cloak class="absolute z-50 mt-1 w-full rounded-md border border-zinc-200 bg-white shadow">
                <template x-for="item in suggestions" :key="item.id">
                    <button type="button" class="block w-full text-left px-3 py-2 text-sm hover:bg-zinc-50" @click="select(item.name)" x-text="item.name"></button>
                </template>
            </div>
        </div>
        <div>
            <flux:input wire:model="firm_postalcode" :label="__('user-account.firm_zip')" />
            <flux:error name="firm_postalcode" />
        </div>
        <div class="md:col-span-2">
            <flux:input wire:model="firm_address" :label="__('user-account.firm_address')" />
            <flux:error name="firm_address" />
        </div>
        <div>
            <flux:label>{{__('user-account.firm_region')}}</flux:label>
            <flux:select name="firm_region_id" wire:model="firm_region_id" required>
                <option value="">{{__('user-account.select')}}</option>
                @php($regions = App\Models\Region::orderBy('name')->get(['id','name']))
                @foreach($regions as $r)
                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                @endforeach
            </flux:select>
            <flux:error name="firm_region_id" />
        </div>
        <div>
            <flux:input wire:model="firm_nip" :label="__('user-account.firm_nip')" />
            <flux:error name="firm_nip" />
        </div>
        <div class="md:col-span-2">
            <flux:input wire:model="firm_www" :label="__('user-account.firm_www')" placeholder="https://example.com" />
            <flux:error name="firm_www" />
        </div>
        <div class="md:col-span-2">
            <flux:input wire:model="firm_email" :label="__('user-account.firm_email')" type="email" placeholder="firma@example.com" />
            <flux:error name="firm_email" />
        </div>
        
        <div class="md:col-span-2">
            <label class="block text-body-medium-s mb-2">{{__('user-account.firm_logo')}}</label>
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
                <span class="text-body-medium-xs">{{__('user-account.drag_drop_logo')}}</span>
                <span class="text-body-regular-xs">{{__('user-account.jpg_png_webp')}}</span>

                <input type="file" x-ref="file" wire:model="avatarUpload" accept="image/png,image/jpeg,image/webp" class="hidden" id="firm-avatar-input">
            </div>
            <div class="mt-2 flex gap-2" wire:loading.remove wire:target="avatarUpload">
                <div class="flex flex-row gap-2 w-full border border-gray-200 rounded-xl p-4 my-4 items-center">
                    <div class="flex flex-row w-full gap-4 items-center">
                        @if ($currentAvatar)
                            <flux:avatar size="lg" circle src="{{ asset('storage/'.$currentAvatar) }}" />
                        @else
                            <flux:avatar size="lg" circle :name="auth()->user()->firm?->firm_name ?? auth()->user()->name" />
                        @endif
                    </div>
                </div>
            </div>
            <div wire:loading.flex wire:target="avatarUpload" class="text-sm text-zinc-500">{{__('user-account.uploading')}}</div>
            <flux:error name="avatarUpload" />
        </div>

        <div class="md:col-span-2 flex justify-end gap-2">
            <flux:button type="submit" variant="primary" class="my-btn">{{__('user-account.save_firm')}}</flux:button>
        </div>
    </form>

    @if ($saved)
        <flux:callout variant="success">{{__('user-account.saved_firm')}}</flux:callout>
    @endif
</div>

<script>
    window.citySearchFirm = window.citySearchFirm || function(regionFieldName, initialValue) {
        return {
            value: initialValue || '',
            open: false,
            suggestions: [],
            regionName() {
                const sel = document.querySelector(`select[name="${regionFieldName}"]`);
                return sel ? sel.value : '';
            },
            onInput() {
                const q = (this.value || '').trim();
                if (q.length < 2) { this.suggestions = []; return; }
                const params = new URLSearchParams({ q, region_id: this.regionName() });
                fetch(`/api/cities?${params.toString()}`)
                    .then(r => r.json())
                    .then(j => { this.suggestions = Array.isArray(j.data) ? j.data : []; this.open = true; })
                    .catch(() => { this.suggestions = []; });
            },
            select(name) { this.value = name; this.open = false; const input = document.querySelector('input[name="firm_city"]'); if (input) { input.value = name; input.dispatchEvent(new Event('input')); } },
        };
    }
</script>
