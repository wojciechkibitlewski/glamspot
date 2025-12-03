@php
    $created = optional($ad->created_at)->format('Y-m-d');
    $vendor = $ad->as_company ? ($ad->company_name ?? optional($ad->user->firm)->firm_name) : ($ad->person_name ?? optional($ad->user)->name);
    $city = $ad->as_company ? ($ad->company_city ?? null) : ($ad->location ?? $ad->person_city ?? null);
    $descHtml = nl2br(strip_tags($ad->description ?? '', '<ul><ol><li><p><br><strong><em><b><i><a>'));
@endphp

<div class="flex flex-col gap-4">
    <div class="flex flex-col gap-2 w-full">
        <h1 class="text-[28px] md:text-[32px] font-medium">{{ $ad->title }}</h1>
        <div class="flex gap-2">
            <p class="text-xs">{{ $created }}</p>
            <p class="text-xs">{{ $city ?: '—' }}</p>
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
            <h5 class="mb-2">{{__('ads.show_others.desc')}}</h5>
            <div class="text-body-regular-m">{!! $descHtml !!}</div>
        </div>
    @endif

</div>
