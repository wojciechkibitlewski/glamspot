<div class="flex flex-row justify-between items-center mb-8 w-full">
    <div class="w-full text-body-regular-xs">
        {{ trans_choice('ads.ads.count', $ads->total(), ['count' => $ads->total()]) }}
        
    </div>
    <div class="w-full text-body-regular-xs flex justify-end">
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="btn m-1">{{__('ads.sort')}}</div>
            <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-60 p-2 shadow-sm">
                <li>
                    <a class="@if(($selectedSort ?? 'newest') === 'newest') active @endif" href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">Najnowsze</a>
                </li>
                <li>
                    <a class="@if(($selectedSort ?? 'newest') === 'oldest') active @endif" href="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}">Najstarsze</a>
                </li>
                @if(($selectedCategory?->slug ?? null) === 'urzadzenia-i-sprzet')
                    <li>
                        <a class="@if(($selectedSort ?? 'newest') === 'price_asc') active @endif" href="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}">Od najtańszego</a>
                    </li>
                    <li>
                        <a class="@if(($selectedSort ?? 'newest') === 'price_desc') active @endif" href="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}">Od najdroższego</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
