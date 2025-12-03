<div class="flex flex-col gap-6 w-full">
    @if ($saved)
        <flux:callout variant="secondary" icon="information-circle" heading="{{ __('user-account.is_saved') }}" />

    @endif
    <form wire:submit="save" class="flex flex-col gap-6 w-full" enctype="multipart/form-data">
        <div class="flex flex-row w-full gap-4">
            <div class="w-full flex flex-col gap-4">
                <flux:input
                    wire:model="name"
                    :label="__('user-account.name')"
                    type="text"
                    required
                    autocomplete="name"
                    placeholder="Jan Kowalski"
                />
                <flux:error name="name" />

                <flux:input
                    wire:model="phone"
                    :label="__('user-account.phone')"
                    type="text"
                    autocomplete="tel"
                    placeholder="+48"
                />
                <flux:error name="phone" />

                <flux:input
                    wire:model="city"
                    :label="__('user-account.city')"
                    type="text"
                    autocomplete="city"
                    placeholder=""
                />
                <flux:error name="city" />
            </div>

            <div class="w-full">
                <label class="block text-body-medium-s mb-2">{{ __('user-account.avatar') }}</label>
                {{-- upload photo  --}}
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
                    <span class="text-body-medium-xs">{{ __('user-account.photo_info') }}</span>
                    <span class="text-body-regular-xs">{{ __('user-account.photo_format') }}</span>

                    <input type="file" x-ref="file" wire:model="avatarUpload" accept="image/png,image/jpeg,image/webp" class="hidden" id="avatar-input">
                </div>
                <div class="mt-2 flex gap-2" wire:loading.remove wire:target="avatarUpload">
                    <div class="flex flex-row gap-2 w-full border border-gray-200 rounded-xl p-4 my-4 items-center">
                        <div class="flex flex-row w-full gap-4 items-center">
                            @if ($currentAvatar)
                                <img src="{{ asset('storage/'.auth()->user()->avatar) }}" title="" 
                                    class="rounded-full size-16" />
                            
                            @else
                                <flux:avatar size="lg" circle :name="auth()->user()->name" />
                            @endif
                        </div>
                        <div class="w-full text-right">
                            <flux:button icon="x-mark" variant="danger" class="cursor-pointer" wire:click="removeAvatar" type="button"/>
                        </div>
                    </div>
                </div>
                <div wire:loading.flex wire:target="avatarUpload" class="text-sm text-zinc-500">{{ __('user-account.is_upload') }}</div>
                <flux:error name="avatarUpload" />
            </div>
        </div>

        <div class="flex flex-row w-full gap-4">
            <div class="w-full">
                <flux:button type="submit" variant="primary" class="w-full my-btn">
                    {{ __('user-account.save') }}
                </flux:button>
            </div>
            <div class="w-full"></div>
        </div>
    </form>
</div>
