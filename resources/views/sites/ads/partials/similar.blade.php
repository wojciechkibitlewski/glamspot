<div class="flex flex-col w-full gap-8 my-8" >
    <h3>{{__('ads.similar_ads')}}</h3>
    {{-- podobne ogłoszenia  --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-4 mb-12">
        @forelse(($similarAds ?? collect()) as $s)
            <div>
                <a href="{{ route('ads.show', [$s->code, $s->slug]) }}" class="relative w-full flex items-center justify-center rounded-xl aspect-4/3 object-cover bg-gray-100 mb-4" title="{{ $s->title }}">
                    @php $firstPhoto = $s->photos->first()->photo ?? null; @endphp
                    @if ($firstPhoto)
                        <img src="{{ asset('storage/'.$firstPhoto) }}" alt="{{ $s->title }}" class="absolute inset-0 w-full h-full object-cover rounded-xl" />
                    @endif
                    @if($s->is_featured)
                        <div class="absolute top-2 left-2 bg-blue rounded-md p-1 px-2 text-white text-xs">Promowane</div>
                    @endif
                </a>
                <div class="flex flex-col gap-2 w-full">
                    <p class="text-xs uppercase">{{ $s->category?->name }}</p>
                    <a href="{{ route('ads.show', [$s->code, $s->slug]) }}" title="{{ $s->title }}">
                        <h5 class="text-[20px] font-medium">{{ $s->title }}</h5>
                    </a>
                    <div class="flex gap-2 ">
                        <p class="text-xs">{{ optional($s->created_at)->format('Y-m-d') }}</p>
                        @php $sCity = $s->as_company ? ($s->company_city ?? null) : ($s->location ?? $s->person_city ?? null); @endphp
                        <p class="text-xs">{{ $sCity ?: '—' }}</p>
                        <p class="text-xs">{{ $s->as_company ? ($s->company_name ?? ($s->user->firm->firm_name ?? '')) : ($s->person_name ?? '') }}</p>
                    </div>
                    @php
                        $slug = $s->category?->slug;
                        $priceLine = null;
                        if ($slug === 'praca') {
                            $from = optional($s->job)->salary_from; $to = optional($s->job)->salary_to;
                            if (!is_null($from) && !is_null($to)) {
                                $priceLine = 'Wynagrodzenie: '.number_format((float) $from, 2, ',', ' ').' - '.number_format((float) $to, 2, ',', ' ').' zł';
                            } elseif (!is_null($from)) {
                                $priceLine = 'Wynagrodzenie: '.number_format((float) $from, 2, ',', ' ').' zł';
                            } elseif (!is_null($to)) {
                                $priceLine = 'Wynagrodzenie: do '.number_format((float) $to, 2, ',', ' ').' zł';
                            }
                        } elseif ($slug === 'szkolenia') {
                            $price = optional($s->training)->promo_price ?? optional($s->training)->price;
                            if (!is_null($price)) {
                                $priceLine = 'Cena: '.number_format((float) $price, 2, ',', ' ').' zł';
                            }
                        } else {
                            $priceFrom = $s->price_from;
                            if (!is_null($priceFrom)) {
                                $priceLine = 'Cena: '.number_format((float) $priceFrom, 2, ',', ' ').' zł';
                            }
                        }
                    @endphp
                    @if ($priceLine)
                        <p class="font-semibold">{{ $priceLine }}</p>
                    @endif
                    <div class="py-4">
                        <a href="{{ route('ads.show', [$s->code, $s->slug]) }}" class="inline p-2 px-4 border text-xs border-gray-300 rounded-full hover:no-underline hover:bg-gray-100" title="{{ $s->title }}">Zobacz więcej</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-sm text-zinc-500">{{__('ads.no_similar_ads')}}</p>
        @endforelse
    </div>
    {{-- END podobne ogłoszenia  --}}
</div>