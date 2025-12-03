<x-layouts.site 
:title="__($ad->title)" 
:description="Str::limit($ad->description, 120)"
>
    <div class="w-full">
        {{-- wyszukiwarka  --}}
        @include('sites.ads.partials.search_box')

        <div class="max-w-7xl mx-auto px-6 lg:px-8 mb-4">
            {{-- Breadcrumbs --}}
            
            <div class="text-body-regular-s text-zinc-400 flex gap-2 items-center">
                <a href="{{ route('home') }}" 
                    title="{{__('ads.okr.home')}}" 
                    class="hidden md:block text-zinc-800 hover:underline decoration-zinc-800/20 underline-offset-4">
                    {{__('ads.okr.home')}}
                </a>
                
                @if($ad->category)
                    <span class="hidden md:block">|</span>
                    <a href="{{ route('ads.category', [$ad->category->slug]) }}"
                    title="{{ 'Kategoria ' . ($ad->category->name ?? '') }}"
                    class="block text-zinc-800 hover:underline decoration-zinc-800/20 underline-offset-4">
                        {{ $ad->category->name }}
                    </a>
                @endif
                @if(isset($subcategory) && $subcategory)
                    <span class="hidden md:block">|</span>
                    <a href="{{ route('ads.category.sub', [$ad->category->slug, $subcategory->slug]) }}"
                    title="{{ 'Podkategoria ' . ($subcategory->name ?? '') }}"
                    class="block text-zinc-800 hover:underline decoration-zinc-800/20 underline-offset-4">
                        {{ $subcategory->name }}
                    </a>
                @endif
                <span class="hidden md:block">|</span>
                <span class="text-body-regular-s block text-zinc-800">{{ Str::limit($ad->title, 50) }}</span>
            </div>
            

            @php
                $priceText = null;
                
                if($ad->category->slug == 'praca')
                {
                    if (!is_null($ad->price_from) && !is_null($ad->price_to)) {
                    $priceText = __('ads.salary').number_format((float) $ad->price_from, 2, ',', ' ').' - '.number_format((float) $ad->price_to, 2, ',', ' ').' zł';
                    } elseif (!is_null($ad->price_from)) {
                        $priceText = __('ads.od').number_format((float) $ad->price_from, 2, ',', ' ').' zł';
                    } elseif (!is_null($ad->price_to)) {
                        $priceText = __('ads.do').number_format((float) $ad->price_to, 2, ',', ' ').' zł';
                    }

                } else {
                    $priceText = __('ads.price').number_format((float) $ad->price_from, 2, ',', ' ').' zł';
                }
                
            @endphp
            @php
                $slides = $ad->photos
                    ->map(fn($p) => ['image' => asset('storage/'.$p->photo)])
                    ->values()
                    ->all();
            @endphp

            {{-- advertisement detail --}}
            @include('sites.ads.partials.detail', ['ad' => $ad, 'priceText' => $priceText, 'slides' => $slides])


            <script>
                function gallery(items) {
                    return {
                        items: items || [],
                        index: 0,
                        isOpen: false,
                        open(i) { this.index = i; this.isOpen = true; },
                        close() { this.isOpen = false; },
                        next() { if (!this.items.length) return; this.index = (this.index + 1) % this.items.length; },
                        prev() { if (!this.items.length) return; this.index = (this.index - 1 + this.items.length) % this.items.length; },
                        current() { return this.items[this.index] || null; },
                    }
                }
            </script>

            {{-- similar ads --}}
            @include('sites.ads.partials.similar', ['similarAds' => $similarAds])
        </div>

    </div>

</x-layouts.site>