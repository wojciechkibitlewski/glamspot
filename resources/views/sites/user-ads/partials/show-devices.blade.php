@php
    $m = $ad->machines;
    $created = optional($ad->created_at)->format('Y-m-d');
    $vendor = $ad->as_company ? ($ad->company_name ?? optional($ad->user->firm)->firm_name) : ($ad->person_name ?? optional($ad->user)->name);
    $descHtml = nl2br(strip_tags($ad->description ?? '', '<ul><ol><li><p><br><strong><em><b><i><a>'));
@endphp

<div class="flex flex-col gap-4">
    <div class="flex flex-col gap-2 w-full  mb-6">
        <h1 class="text-[28px] md:text-[32px] font-medium">{{ $ad->title }}</h1>
        <div class="flex gap-2">
            <p class="text-xs">{{ $created }}</p>
            <p class="text-xs">{{ $ad->location ?? ' ' }}</p>
            @if($vendor)
                <a href="#" title="{{ $vendor }}" class="text-xs underline underline-offset-2">{{ $vendor }}</a>
            @endif
        </div>
        @if (!empty($priceText))
            <p class="text-[18px] font-semibold">{{ $priceText }}</p>
        @endif
    </div>

    @if($descHtml)
        <div>
            <h5 class="mb-2">{{__('ads.show_device.desc')}}</h5>
            <div class="text-body-regular-m">{!! $descHtml !!}</div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full my-8 bg-gray-100 rounded-xl p-8">
        <div>
            <h5 class="text-body-bold-m">{{__('ads.show_device.type')}}</h5>
            <p class="text-body-regular-m">
                @if($m?->availability_type === 'sale') {{__('ads.show_device.sale')}}
                @elseif($m?->availability_type === 'buy') {{__('ads.show_device.buy')}}
                @elseif($m?->availability_type === 'rent') {{__('ads.show_device.rent')}}
                @else - @endif
            </p>
        </div>
        <div>
            <h5 class="text-body-bold-m">{{__('ads.show_device.condition')}}</h5>
            <p class="text-body-regular-m">
                @switch($m?->state)
                    @case('bardzo_dobry') {{__('ads.show_device.verygood')}} @break
                    @case('dobry') {{__('ads.show_device.good')}} @break
                    @case('wymaga_naprawy') {{__('ads.show_device.fair')}} @break
                    @default —
                @endswitch
            </p>
        </div>
        @if($m?->availability_type === 'rent')
            <div>
                <h5 class="text-body-bold-m">{{__('ads.show_others.okres')}}</h5>
                <p class="text-body-regular-m">
                    @switch($m?->price_unit)
                        @case('hour') {{__('ads.show_others.hourly')}} @break
                        @case('day') {{__('ads.show_others.dayly')}} @break
                        @case('week') {{__('ads.show_others.weekly')}} @break
                        @case('month') {{__('ads.show_others.monthly')}} @break
                        @default —
                    @endswitch
                </p>
            </div>
        @endif

        <div>
            <h5 class="text-body-bold-m">{{__('ads.show_others.leasing')}}</h5>
            <p class="text-body-regular-m">{{ $m?->deposit_required ? __('ads.yes') : __('ads.no') }}</p>
        </div>

    </div>
    
</div>

