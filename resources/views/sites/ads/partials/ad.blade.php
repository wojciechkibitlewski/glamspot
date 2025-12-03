<div class="md:flex md:flex-row w-full rounded-2xl md:border md:border-gray-200 md:p-2">
    <a href="{{ route('ads.show', [$ad->code, $ad->slug]) }}" 
    class="relative w-full flex items-center justify-center rounded-xl aspect-4/3 object-cover bg-gray-100"
    title="{{ $ad->title }}"
    >
        @php $firstPhoto = $ad->photos->first()->photo ?? null; @endphp
        @if ($firstPhoto)
            <img src="{{ asset('storage/'.$firstPhoto) }}" alt="{{ $ad->title }}" class="absolute inset-0 w-full h-full object-cover rounded-xl" />
        @endif
        @if($ad->is_featured)
            <div class="absolute top-2 left-2 bg-[#9E83E8] rounded-md p-1 px-2 text-white text-xs ">{{__('ads.promote')}}</div>
        @endif
    </a>
    <div class="flex w-full py-6 md:p-6 items-center md:h-full">
        <div class="flex flex-col gap-2 w-full">
            <p class="text-xs uppercase">{{ $ad->category?->name }}</p>
            <a href="{{ route('ads.show', [$ad->code, $ad->slug]) }}" title="{{ $ad->title }}">
                <h5 class="text-[20px] font-medium">{{ $ad->title }}</h5>
            </a>
            <div class="flex gap-2 ">
                <p class="text-xs">{{ optional($ad->created_at)->format('Y-m-d') }}</p>
                @php $city = $ad->as_company ? ($ad->company_city ?? null) : ($ad->location ?? $ad->person_city ?? null); @endphp
                <p class="text-xs">{{ $city ?: '—' }}</p>
                <p class="text-xs">{{ $ad->as_company ? ($ad->company_name ?? ($ad->user->firm->firm_name ?? '')) : ($ad->person_name ?? '') }}</p>
            </div>
            @php
                $slug = $ad->category?->slug;
                $priceLine = null;
                if ($slug === 'praca') {
                    $from = optional($ad->job)->salary_from; $to = optional($ad->job)->salary_to;
                    if (!is_null($from) && !is_null($to)) {
                        $priceLine = __('ads.salary').number_format((float) $from, 2, ',', ' ').' - '.number_format((float) $to, 2, ',', ' ').' zł';
                    } elseif (!is_null($from)) {
                        $priceLine = __('ads.salary').number_format((float) $from, 2, ',', ' ').' zł';
                    } elseif (!is_null($to)) {
                        $priceLine = __('ads.salary_max').number_format((float) $to, 2, ',', ' ').' zł';
                    }
                } elseif ($slug === 'szkolenia') {
                    $price = optional($ad->training)->promo_price ?? optional($ad->training)->price;
                    if (!is_null($price)) {
                        $priceLine = __('ads.price').number_format((float) $price, 2, ',', ' ').' zł';
                    }
                } else {
                    $priceFrom = $ad->price_from;
                    if (!is_null($priceFrom)) {
                        $priceLine = __('ads.price').number_format((float) $priceFrom, 2, ',', ' ').' zł';
                    }
                }
            @endphp
            @if ($priceLine)
                <p class="">{{ $priceLine }}</p>
            @endif
        </div>
    </div>
    <div class="w-full md:p-6 flex md:justify-center items-center">
        <div class="py-4">
            <a href="{{ route('ads.show', [$ad->code, $ad->slug]) }}" class="inline p-2 px-4 border text-xs border-gray-300 rounded-full hover:no-underline hover:bg-gray-100" title="{{ $ad->title }}">{{__('ads.see_details')}}</a>
        </div>
    </div>
</div>