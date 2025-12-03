<x-layouts.site 
:title="__('seo.user-ads.title')"
:description="__('seo.user-ads.description')"
>
<div class="w-full">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 mb-8 py-8 min-h-[600px]">
            {{-- header --}}
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <flux:heading size="xl">{{ __('user-ads.title') }}</flux:heading>
                </div>
            </div>

            {{-- main content --}}
            <div class="flex items-start gap-8">
                @include('sites.user-accounts.partials.nav')

                <div class="w-full">
                    <section class="w-full rounded-2xl border border-zinc-200 bg-gray-50 p-6">
                        <div class="flex items-center justify-between pb-4 mb-4 border-b border-zinc-200">
                            <h2 class="text-xl font-medium">{{__('user-ads.ads_list')}}</h2>
                            <flux:button href="{{ route('user-ads.create') }}" variant="filled" class="cursor-pointer">{{__('user-ads.create_new_ad')}}</flux:button>
                        </div>

                        @if ($ads->isEmpty())
                            <flux:callout title="{{__('user-ads.no_ads_title')}}" icon="information-circle">
                                {{__('user-ads.no_ads')}}
                            </flux:callout>
                        @else
                            <div class="grid gap-3">
                                @foreach ($ads as $ad)
                                    <div class="flex items-start justify-between gap-4 rounded-lg bg-white border border-zinc-200 p-4">
                                        <div class="flex ">
                                            {{-- photo --}}
                                            <div class="w-24 h-16 mr-3">
                                                @php $firstPhoto = $ad->photos->first(); @endphp
                                                @if($firstPhoto)
                                                    <img src="{{ asset('storage/'.$firstPhoto->photo) }}" alt="{{ $ad->title }}" class="w-24 h-16 object-cover rounded-md border" />
                                                @endif
                                            </div>
                                            {{-- info  --}}
                                            <div>
                                                <div class="font-medium text-zinc-800">{{ $ad->title }}</div>
                                                <div class="flex gap-2 my-2">
                                                    <flux:badge color="zinc" class="text-body-regular-xs !font-normal">{{ $ad->category?->name }}</flux:badge>
                                                    @php
                                                        $city = $ad->as_company ? ($ad->company_city ?? null) : ($ad->location ?? $ad->person_city ?? null);
                                                    @endphp
                                                    <flux:badge color="zinc" class="text-body-regular-xs !font-normal">{{ $city ?: '—' }}</flux:badge>
                                                    @php
                                                        $statusMap = [
                                                            'pending_payment' => __('user-ads.pending_payment'),
                                                            'active' => __('user-ads.active'),
                                                            'in_review' => __('user-ads.in_review'),
                                                            'expired' => __('user-ads.expired'),
                                                        ];
                                                        $statusLabel = $statusMap[$ad->status] ?? ucfirst($ad->status);
                                                    @endphp
                                                    @if($ad->status === 'pending_payment')
                                                        <flux:badge color="fuchsia" class="text-body-regular-xs !font-normal">{{ $statusLabel }}</flux:badge>
                                                    @else
                                                        <flux:badge color="lime" class="text-body-regular-xs !font-normal">{{ $statusLabel }}</flux:badge>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        {{-- actions --}}
                                        <div class="flex items-center gap-2">
                                            <flux:button href="{{ route('user-ads.show', [$ad->code, $ad->slug]) }}" variant="ghost" size="xs" class="cursor-pointer">{{__('user-ads.see_ad')}}</flux:button>
                                            <flux:button href="{{ route('user-ads.edit', [$ad->code, $ad->slug]) }}" variant="outline" size="xs" class="cursor-pointer">{{ __('user-ads.edit_ad') }}</flux:button>
                                            @if ($ad->status === 'pending_payment')
                                                <flux:button href="{{ route('user-ads.checkout', [$ad->code, $ad->slug]) }}" variant="primary" size="xs" color="fuchsia" class="cursor-pointer">{{ __('user-ads.pay') }}</flux:button>
                                            @endif
                                            <flux:button href="#" variant="primary" color="teal" size="xs" class="cursor-pointer">{{ __('user-ads.highlight')}}</flux:button>

                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6">
                                {{ $ads->links() }}
                            </div>
                        @endif

                    </section>
                </div>
            </div>
    </div>
</div>



</x-layouts.site>