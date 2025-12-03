<x-layouts.site 
:title="__('user-account.firm.title')"
:description="__('user-account.firm.description')"
>
    <div class="w-full">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 mb-8 py-8 min-h-[600px]">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-zinc-200">
                <div class="space-y-1">
                    <flux:heading size="xl">{{__('user-account.firm_heading')}}</flux:heading>
                </div>
            </div>
            <div class="flex items-start gap-8">
                @include('sites.user-accounts.partials.nav')

                <section class="w-full rounded-2xl border border-zinc-200 bg-gray-50 p-6" x-data x-on:firm-saved.window="window.location.reload()">
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-zinc-200">
                        <h2 class="text-xl font-medium">{{__('user-account.firm_title')}}</h2>
                        <flux:modal.trigger name="add-firm">
                            <flux:button variant="filled" class="cursor-pointer">{{ auth()->user()->firm ? __('user-account.edit_firm') : __('user-account.add_firm') }} {{__('user-account.firm_dane')}}</flux:button>
                        </flux:modal.trigger>
                    </div>
                    
                    @php $firm = auth()->user()->firm; @endphp
                    @if ($firm)
                        <div class="grid gap-6">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="md:col-span-2 flex items-center gap-4">
                                    <div class="font-medium text-lg">{{ $firm->firm_name }}</div>
                                </div>
                                <div><span class="text-sm text-zinc-500">{{__('user-account.firm_name')}}</span><div class="font-medium">{{ $firm->firm_name }}</div></div>
                                <div><span class="text-sm text-zinc-500">{{__('user-account.firm_email')}}</span><div class="font-medium">{{ $firm->firm_email }}</div></div>
                                <div><span class="text-sm text-zinc-500">{{__('user-account.firm_city')}}</span><div class="font-medium">{{ $firm->firm_city }}</div></div>
                                <div><span class="text-sm text-zinc-500">{{__('user-account.firm_zip')}}</span><div class="font-medium">{{ $firm->firm_postalcode }}</div></div>
                                <div><span class="text-sm text-zinc-500">{{__('user-account.firm_address')}}</span><div class="font-medium">{{ $firm->firm_address }}</div></div>
                                <div><span class="text-sm text-zinc-500">{{__('user-account.firm_region')}}</span><div class="font-medium">{{ $firm->region?->name ?? $firm->firm_region }}</div></div>
                                <div><span class="text-sm text-zinc-500">{{__('user-account.firm_nip')}}</span><div class="font-medium">{{ $firm->firm_nip }}</div></div>
                                <div><span class="text-sm text-zinc-500">{{__('user-account.firm_www')}}</span><div class="font-medium">{{ $firm->firm_www }}</div></div>
                            </div>
                        </div>
                    @else
                        <div class="">
                            <div class="block mb-2">{{__('user-account.dont_have_firm')}}</div>
                            
                            <flux:modal.trigger name="add-firm">
                                <flux:button variant="filled" class="cursor-pointer">{{__('user-account.add_your_firm')}}</flux:button>
                            </flux:modal.trigger>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
    <flux:modal name="add-firm" variant="flyout">
        <livewire:user.firm-form />
    </flux:modal>
</x-layouts.site>
