@php
    $created = optional($ad->created_at)->format('Y-m-d');
    $vendor = $ad->as_company ? ($ad->company_name ?? optional($ad->user->firm)->firm_name) : ($ad->person_name ?? optional($ad->user)->name);
    $employment = collect(explode(',', (string) optional($ad->job)->employment_form))
        ->filter(fn($v) => trim($v) !== '')
        ->map(fn($v) => trim($v))
        ->values();
    $expHtml = nl2br(strip_tags(optional($ad->job)->experience_level ?? '', '<ul><ol><li><p><br><strong><em><b><i><a>'));
    $scopeHtml = nl2br(strip_tags($ad->description ?? '', '<ul><ol><li><p><br><strong><em><b><i><a>'));
    $reqHtml = nl2br(strip_tags(optional($ad->job)->requirements ?? '', '<ul><ol><li><p><br><strong><em><b><i><a>'));
    $benefitsHtml = nl2br(strip_tags(optional($ad->job)->benefits ?? '', '<ul><ol><li><p><br><strong><em><b><i><a>'));
@endphp

<div class="flex flex-col gap-4">
    <div class="flex flex-col gap-2 w-full mb-6">
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

    
    @if($expHtml)
        <div>
            <h5 class="mb-2">{{__('ads.show_work.title')}}</h5>
            <div class="text-body-regular-m">{!! $expHtml !!}</div>
        </div>
    @endif

    @if($scopeHtml)
        <div>
            <h5 class="mb-2">{{__('ads.show_work.scope')}}</h5>
            <div class="text-body-regular-m">{!! $scopeHtml !!}</div>
        </div>
    @endif

    @if($reqHtml)
        <div>
            <h5 class="mb-2">{{__('ads.show_work.req')}}</h5>
            <div class="text-body-regular-m">{!! $reqHtml !!}</div>
        </div>
    @endif

    @if($benefitsHtml)
        <div>
            <h5 class="mb-2">{{__('ads.show_work.benefits')}}</h5>
            <div class="text-body-regular-m">{!! $benefitsHtml !!}</div>
        </div>
    @endif
    
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full my-8 bg-gray-100 rounded-xl p-8">
       
        @if($employment->isNotEmpty())
            <div>
                <h5 class="text-body-bold-m">{{__('ads.show_work.employment')}}</h5>
                <p class="text-body-regular-m">{{ $employment->join(', ') }}</p>
            </div>
        @endif

        @php $job = $ad->job; @endphp
        @if($job)
            <div>
                <h5 class="text-body-bold-m">{{__('ads.show_work.specialization')}}</h5>
                <p class="text-body-regular-m">{{ $job->specializations?->pluck('name')->join(', ') ?: '—' }}</p>
            </div>
        @endif

    </div>
</div>
