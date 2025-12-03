<div>
    <form wire:submit="subscribe" class="flex flex-col md:flex-row gap-2">
        <input
            type="email"
            wire:model="email"
            placeholder="{{ __('footer.email_placeholder') }}"
            class="newsletter"
            required
        />
        <button
            type="submit"
            class="px-8 py-4 rounded-full bg-linear-to-r from-[#BA75EC] to-[#1FC2D7] text-white font-medium text-body-medium-m hover:opacity-90 transition disabled:opacity-50"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove>{{ __('footer.subskrybuj') }}</span>
            <span wire:loading>{{ __('newsletter.wysylanie') }}</span>
        </button>
    </form>

    @error('email')
        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
    @enderror

    <!-- Modal potwierdzający -->
    @if($showModal)
        <div
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            wire:click="closeModal"
        >
            <div
                class="bg-white rounded-lg p-8 max-w-md mx-4 shadow-xl"
                wire:click.stop
            >
                <div class="text-center">
                    <div class="mb-4 text-6xl">✓</div>
                    <h3 class="text-2xl font-bold mb-4 bg-gradient-to-r from-[#BA75EC] to-[#1FC2D7] bg-clip-text text-transparent">
                        {{ __('newsletter.sukces_tytul') }}
                    </h3>
                    <p class="text-gray-600 mb-6">
                        {{ __('newsletter.sukces_wiadomosc') }}
                    </p>
                    <button
                        wire:click="closeModal"
                        class="px-6 py-3 rounded-full bg-gradient-to-r from-[#BA75EC] to-[#1FC2D7] text-white font-medium hover:opacity-90 transition"
                    >
                        {{ __('newsletter.zamknij') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
