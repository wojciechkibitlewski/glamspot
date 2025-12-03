<div class="flex flex-col gap-2 mt-2 font-light">
    @if($sent)
        <div class="p-3 rounded-md bg-green-50 text-green-700 text-sm my-6">
           {{__('ads.contact.mail_result_success')}}
        </div>
    @endif

    <form wire:submit.prevent="send" class="flex flex-col gap-2">
        <flux:input
            wire:model.live="name"
            type="text"
            required
            autocomplete="name"
            placeholder="{{__('ads.contact.form.name')}}"
        />
        <flux:error name="name" />

        <flux:input
            wire:model.live="email"
            type="email"
            required
            autocomplete="email"
            placeholder="{{__('ads.contact.form.email')}}"
        />
        <flux:error name="email" />

        <flux:input
            wire:model.live="phone"
            type="text"
            autocomplete="tel"
            placeholder="{{__('ads.contact.form.phone')}}"
        />
        <flux:error name="phone" />

        <flux:textarea
            wire:model.live="message"
            rows="5"
            placeholder="{{ __('ads.contact.form.message') }}"
            required
        />
        <div class="flex flex-col gap-1">
            <div class="flex gap-2 items-center">
                <input type="checkbox" wire:model="terms" id="terms" value="1" />
                <label for="terms" class="text-body-regular-xs cursor-pointer">
                    {{__('ads.contact.form.wyrazam')}}<a href="{{ route('privacy') }}" title="{{__('ads.contact.form.privacy_policy')}}" class="underline underline-offset-2">{{__('ads.contact.form.privacy_policy')}}</a>
                </label>
            </div>
            <flux:error name="terms" />
        </div>

        <div class="flex gap-2 items-center">
            <input type="checkbox" wire:model="newsletter" id="newsletter" value="1" />
            <label for="newsletter" class="text-body-regular-xs cursor-pointer">
                {{__('ads.contact.form.newsletter')}}
            </label>
        </div>
        
        

        <div class="flex items-center justify-end">
            <button type="submit" class="my-btn w-full px-8 py-4 rounded-full bg-linear-to-r from-[#BA75EC] to-[#1FC2D7] !text-white font-medium text-body-medium-m hover:opacity-90 transition cursor-pointer"
                wire:loading.attr="disabled">
                <span class="text-white" wire:loading.remove>{{__('ads.contact.form.send')}}</span>
                <span class="text-white" wire:loading>{{__('ads.contact.form.sending')}}</span>
            </button>
        </div>
    </form>
</div>
