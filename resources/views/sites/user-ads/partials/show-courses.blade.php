@php
    $t = $ad->training;
    $created = optional($ad->created_at)->format('Y-m-d');
    $vendor = $ad->as_company ? ($ad->company_name ?? optional($ad->user->firm)->firm_name) : ($ad->person_name ?? optional($ad->user)->name);
    $descHtml = nl2br(strip_tags(optional($t)->description ?? '', '<ul><ol><li><p><br><strong><em><b><i><a>'));
    $programHtml = nl2br(strip_tags(optional($t)->program ?? '', '<ul><ol><li><p><br><strong><em><b><i><a>'));
    $bonusesHtml = nl2br(strip_tags(optional($t)->bonuses ?? '', '<ul><ol><li><p><br><strong><em><b><i><a>'));
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
            <p class="text-body-medum-m">{{ $priceText }}</p>
        @endif
    </div>

    @if($descHtml)
        <div>
            <h5>{{__('ads.show_course.title')}}</h5>
            <div class="text-body-regular-m">{!! $descHtml !!}</div>
        </div>
    @endif

    @if($programHtml)
        <div>
            <h5>{{__('ads.show_course.program')}}</h5>
            <div class="text-body-regular-m">{!! $programHtml !!}</div>
        </div>
    @endif

    @if($bonusesHtml)
        <div>
            <h5>{{__('ads.show_course.bonus')}}</h5>
            <div class="text-body-regular-m">{!! $bonusesHtml !!}</div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full my-8 bg-gray-100 rounded-xl p-8">
        <div>
            <h5 class="text-body-bold-m">{{__('ads.show_course.tryb')}}</h5>
            <p class="text-body-regular-m">{{ $t?->is_online ? __('ads.show_course.tryb_online') : __('ads.show_course.tryb_stacjonarne') }}</p>
        </div>
        <div>
            <h5 class="text-body-bold-m">{{__('ads.show_course.miejsca')}}</h5>
            <p class="text-body-regular-m">{{ $t?->seats ?? '—' }}</p>
        </div>
        @if($t?->dates && $t->dates->count())
            <div>
                <h5 class="text-body-bold-m">{{__('ads.show_course.dates')}}</h5>
                <ul class="list-disc ms-5 text-body-regular-m">
                    @foreach($t->dates as $d)
                        <li>{{ optional($d->start_date)->format('Y-m-d') }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @php $specs = $t?->specializations?->pluck('name')->all() ?? []; @endphp
        @if(!empty($specs))
            <div>
                <h5 class="text-body-bold-m">{{__('ads.show_course.specialization')}}</h5>
                <p class="text-body-regular-m">{{ implode(', ', $specs) }}</p>
            </div>
        @endif

        @if($t?->audience)
            <div>
                <h5 class="text-body-bold-m">{{__('ads.show_course.audience')}}</h5>
                <p class="text-body-regular-m">{{ $t->audience }}</p>
            </div>
        @endif
        <div>
            <h5 class="text-body-bold-m">{{__('ads.show_course.certificates')}}</h5>
            <p class="text-body-regular-m">{{ $t?->has_certificate ? __('ads.yes') : __('ads.no') }}</p>
        </div>
        @if($t?->signup_url)
            <div>
                <h5 class="text-body-bold-m">{{__('ads.show_course.signup')}}</h5>
                <a href="{{ $t->signup_url }}" target="_blank" rel="noopener" class="underline underline-offset-2">{{ $t->signup_url }}</a>
            </div>
        @endif
    </div>
</div>
