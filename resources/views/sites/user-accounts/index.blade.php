<x-layouts.site 
:title="__('seo.user-account.title')"
:description="__('seo.user-account.description')"
>
    {{-- header --}}
    <div class="w-full">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10 ">
            {{-- header --}}
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <flux:heading size="xl">{{ __('user-account.title') }}</flux:heading>
                </div>
            </div>
        </div>
    </div>
    {{-- main content --}}
    <div class="w-full bg-gray-100"> 
        <div class="container-glamspot py-10">
            <div class="flex items-start gap-8">
                @include('sites.user-accounts.partials.nav')

                <div class="w-full">
                    {{-- profile index page  --}}
                    <div class="flex flex-row w-full mb-8 justify-between items-center">
                        <div class="flex gap-4 items-center ">
                            <div class="">
                                @if (auth()->user()->avatar)
                                    <img src="{{ asset('storage/'.auth()->user()->avatar) }}" title="" 
                                    class="rounded-full size-20" />
                                @else
                                    <flux:avatar size="xl" circle :name="auth()->user()->name" />
                                @endif
                            </div>
                            <div class="flex flex-col gap-1">
                                <span class="font-bold text-xl">{{ auth()->user()->name }}</span>
                                <span class="">{{ auth()->user()->city }}</span>
                            </div>
                        </div>
                        <div class="">
                            <flux:button href="#" variant="filled">{{__('user-account.see_as_others')}}</flux:button>
                        </div>
                    </div>
                    
                    <section class="w-full rounded-2xl border border-zinc-200 bg-white p-6">

                        {{-- change email  --}}
                        <div class="flex flex-row w-full border-b border-gray-200 pb-4 items-end py-6">
                            <div class="w-full">
                                <h3 class="text-body-medium-l mb-2">{{__('user-account.email')}}</h3>
                                <p class="text-body-regular-s text-zinc-500">{{ __('user-account.email_user') }}<strong>{{ auth()->user()->email }}</strong></p>
                            </div>
                            <div class="w-full text-right">
                                <flux:modal.trigger name="edit-email">
                                    <flux:button variant="ghost" class="text-sm underline text-right !text-blue-600 cursor-pointer">{{ __('user-account.change') }}</flux:button>
                                </flux:modal.trigger>
                            </div>
                        </div>

                        {{-- change data  --}}
                        <div class="flex flex-col w-full border-b border-gray-200 pb-4 items-end py-6">
                            <div class="w-full mb-6">
                                <h3 class="text-body-medium-l mb-2">{{ __('user-account.your_datails') }}</h3>
                            </div>
                            <livewire:user.update-profile />
                        </div>

                        {{-- firm  --}}
                        <div class="flex flex-row w-full border-b border-gray-200 pb-4 items-end py-6">
                            @php $firm = auth()->user()->firm; @endphp
                            <div class="w-full">
                                <h3 class="text-body-medium-l mb-2">{{ __('user-account.company_account') }}</h3>
                                @if ($firm)
                                    <a href="#" title="{{ $firm->firm_name }}" class="text-body-regular-s text-zinc-500 underline">{{ $firm->firm_name }}</a>
                                @else
                                    <p class="text-body-regular-s text-zinc-500">{{ __('user-account.company_account_change') }}</p>
                                @endif
                            </div>
                            <div class="w-full text-right">
                                <a href="#" class="hover:no-underline">
                                    @if ($firm)
                                        <flux:button variant="filled">{{ __('user-account.see_company') }}</flux:button>
                                    @else
                                        <flux:button variant="filled">{{ __('user-account.add_company') }}</flux:button>
                                    @endif
                                </a>
                            </div>
                        </div>

                        {{-- delete account  --}}
                        <div class="flex flex-row w-full border-b border-gray-200 pb-4 items-end py-6">
                            <div class="w-full">
                                <h3 class="text-body-medium-l mb-2">{{ __('user-account.delete_account') }}</h3>
                                <p class="text-body-regular-s text-zinc-500">{{ __('user-account.delete_account_info') }}</p>
                            </div>
                            <div class="w-full text-right">
                                <flux:modal.trigger name="delete-profile">
                                    <flux:button variant="danger" class="cursor-pointer">{{ __('user-account.delete_account') }}</flux:button>
                                </flux:modal.trigger>
                            </div>
                        </div>

                    </section>
                </div>
            </div>
        </div>
    </div>

</x-layouts.site>

