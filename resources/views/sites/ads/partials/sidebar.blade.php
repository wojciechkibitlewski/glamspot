<flux:sidebar.nav class="">
    <ul class="menu menu-sm bg-slate-50 rounded-box w-56">
        {{-- kategorie  --}}
        <li>
            <details open>
                <summary class="">{{__('ads.category.title')}}</summary>
                <ul>
                    @foreach(($categories ?? []) as $cat)
                        @php $isActiveCat = ($selectedCategory?->slug ?? null) === $cat->slug; @endphp
                        <li>
                            <a href="{{ route('ads.category', [$cat->slug]) }}"
                                title="Ogłoszenia {{ $cat->name }}"
                                class=" {{ $isActiveCat ? 'menu-active' : '' }}">
                                {{ $cat->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </details>
        </li>
        {{-- podkategorie  --}}
        @if(($selectedCategory ?? null) && ($subcategories ?? collect())->count())
            <li class="mt-2">
                <details open>
                    <summary class="">{{__('ads.subcategory.title')}}</summary>
                    <ul>
                        @foreach($subcategories as $sub)
                            @php $isActiveSub = ($selectedSubcategory->slug ?? null) === $sub->slug; @endphp
                            <li>
                                <a href="{{ route('ads.category.sub', [$selectedCategory->slug, $sub->slug]) }}"
                                    title="{{ $sub->name }}"
                                    class=" {{ $isActiveSub ? 'menu-active' : '' }}">
                                    {{ $sub->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </details>
            </li>
        @endif

        
    </ul>
</flux:sidebar.nav>