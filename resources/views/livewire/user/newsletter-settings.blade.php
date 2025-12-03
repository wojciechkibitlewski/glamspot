<div>
    <form wire:submit="save">
        <div class="grid gap-6">
            <!-- Główny newsletter -->
            <flux:field>
                <div class="flex items-start gap-3">
                    <flux:checkbox
                        wire:model="newsletter"
                        id="newsletter-main"
                    />
                    <div>
                        <flux:label for="newsletter-main" class="font-medium">
                            {{ __('user-account.newsletter_main') }}
                        </flux:label>
                        <flux:description>
                            {{ __('user-account.newsletter_main_desc') }}
                        </flux:description>
                    </div>
                </div>
            </flux:field>

            <!-- Kategorie newslettera - zawsze widoczne -->
            <flux:field>
                <flux:label class="font-medium">{{ __('user-account.newsletter_categories') }}</flux:label>
                <flux:description class="mb-4">
                    {{ __('user-account.newsletter_categories_desc') }}
                </flux:description>

                <div class="grid md:grid-cols-2 gap-4 my-4">
                    <div class="flex items-start gap-3">
                        <flux:checkbox
                            wire:model="works"
                            id="newsletter-works"
                        />
                        <div>
                            <flux:label for="newsletter-works">
                                {{ __('user-account.newsletter_works') }}
                            </flux:label>
                            <flux:description class="text-xs">
                                {{ __('user-account.newsletter_works_desc') }}
                            </flux:description>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <flux:checkbox
                            wire:model="courses"
                            id="newsletter-courses"
                        />
                        <div>
                            <flux:label for="newsletter-courses">
                                {{ __('user-account.newsletter_courses') }}
                            </flux:label>
                            <flux:description class="text-xs">
                                {{ __('user-account.newsletter_courses_desc') }}
                            </flux:description>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <flux:checkbox
                            wire:model="devices"
                            id="newsletter-devices"
                        />
                        <div>
                            <flux:label for="newsletter-devices">
                                {{ __('user-account.newsletter_devices') }}
                            </flux:label>
                            <flux:description class="text-xs">
                                {{ __('user-account.newsletter_devices_desc') }}
                            </flux:description>
                        </div>
                    </div>
                </div>
            </flux:field>

            <!-- Przyciski -->
            <div class="flex items-center gap-4">
                <flux:button
                    type="submit"
                    class="px-5"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>{{ __('user-account.save_newsletter') }}</span>
                    <span wire:loading>{{ __('user-account.saving') }}</span>
                </flux:button>

                @if($showSuccessMessage)
                    <div
                        class="text-green-600 text-sm font-medium"
                        x-data="{ show: true }"
                        x-show="show"
                        x-init="setTimeout(() => show = false, 3000)"
                    >
                        ✓ {{ __('user-account.newsletter_saved') }}
                    </div>
                @endif
            </div>
        </div>
    </form>
</div>
