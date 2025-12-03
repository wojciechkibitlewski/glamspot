<x-layouts.site 
:title="__('seo.ads.title')" 
:description="__('seo.ads.description')"
>
    <div class="w-full">
        @include('sites.ads.partials.search_box')
        {{-- ogłoszenia  --}}
        <div class="container-glamspot">
            <div class="flex flex-col md:flex-row  w-full min-h-[400px] p-0 gap-8">

                <flux:sidebar class="!p-0 !m-0 hidden md:block">
                    <flux:sidebar.header>
                        <div class="flex justify-between w-full">
                             <div class="uppercase text-body-bold-xs">{{__('ads.filter.title')}}</div>
                             <a href="{{ route('ads.index') }}" class="text-body-regular-xs underline cursor-pointer">{{ __('ads.clear_all') }}</a>
                        </div>
                    </flux:sidebar.header>
                    
                    @include('sites.ads.partials.sidebar')
                </flux:sidebar>
                <flux:modal.trigger name="edit-profile" class="block md:hidden">
                    <flux:button icon:trailing="adjustments-horizontal">{{__('ads.filter.title')}}</flux:button>
                </flux:modal.trigger>
                
                <main class="w-full">
                    @include('sites.ads.partials.filters')
                    
                    <div class="grid grid-cols-1 gap-6 md:gap-4 w-full">
                        @foreach ($ads as $ad)
                        {{-- Ogłoszenie   --}}
                            @include('sites.ads.partials.ad', ['ad' => $ad])
                        @endforeach
                    </div>

                </main>

            </div>
        </div>

        {{-- SEO treść  --}}
        <div class="max-w-7xl mx-auto px-6 lg:px-8 my-8">
            <h2 class="text-body-bold-l mb-2 text-2xl">{{ $selectedCategory?->name ?? __('ads.seo.header') }}</h2>
            <p class="text-body-regular-s">
                {{ __('ads.seo.description') }}
            </p>
        </div>

        <flux:modal name="edit-profile" variant="flyout">
            <div class="space-y-6">
                <div class="flex justify-between w-full">
                    <div class="uppercase text-body-bold-xs">{{__('ads.filter.title')}}</div>
                    <div class="text-body-regular-xs underline cursor-pointer">{{ __('ads.clear_all') }}</div>
                </div>
            </div>
        </flux:modal>

    </div>

    {{-- scripts  --}}
    @if(($selectedCategory?->slug ?? null) === 'praca')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('apply-filters');
            if (!btn) return;
            btn.addEventListener('click', function () {
                const cat = @json($selectedCategory?->slug ?? '');
                const sub = @json($selectedSubcategory->slug ?? 'all');
                const spec = Array.from(document.querySelectorAll('input[name="spec[]"]:checked')).map(el => el.value).join(',') || 'all';
                const forms = Array.from(document.querySelectorAll('input[name="form[]"]:checked')).map(el => el.value).join(',') || 'all';
                const region = (document.querySelector('select[name="region"]')?.value || '').trim() || 'all';
                if (!cat) { return; }
                const path = `/ogloszenia/${cat}/${sub || 'all'}/${spec}/${region}/${forms}`;
                window.location.href = path;
            });
        });
    </script>
    @endif

    @if(($selectedCategory?->slug ?? null) === 'szkolenia')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('apply-training-filters');
            if (!btn) return;
            btn.addEventListener('click', function () {
                const base = "{{ isset($selectedSubcategory) ? route('ads.category.sub', [$selectedCategory->slug, $selectedSubcategory->slug]) : route('ads.category', [$selectedCategory->slug]) }}";
                const params = new URLSearchParams();
                const tspec = Array.from(document.querySelectorAll('input[name="tspec[]"]:checked')).map(el => el.value);
                tspec.forEach(v => params.append('tspec[]', v));
                const cert = document.querySelector('input[name="cert"]:checked') ? '1' : '';
                if (cert) params.set('cert', '1');
                const pf = (document.querySelector('input[name="price_from"]').value || '').trim();
                const pt = (document.querySelector('input[name="price_to"]').value || '').trim();
                if (pf) params.set('price_from', pf);
                if (pt) params.set('price_to', pt);
                const ds = (document.querySelector('input[name="date_start"]').value || '').trim();
                const de = (document.querySelector('input[name="date_end"]').value || '').trim();
                if (ds) params.set('date_start', ds);
                if (de) params.set('date_end', de);
                const region = (document.querySelector('select[name="t_region"]').value || '').trim();
                if (region) params.set('region', region);
                const url = params.toString() ? `${base}?${params.toString()}` : base;
                window.location.href = url;
            });
        });
    </script>
    @endif

    @if(($selectedCategory?->slug ?? null) === 'urzadzenia-i-sprzet')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('apply-devices-filters');
            if (!btn) return;
            btn.addEventListener('click', function () {
                const base = "{{ isset($selectedSubcategory) ? route('ads.category.sub', [$selectedCategory->slug, $selectedSubcategory->slug]) : route('ads.category', [$selectedCategory->slug]) }}";
                const params = new URLSearchParams();
                const pf = (document.querySelector('input[name="d_price_from"]').value || '').trim();
                const pt = (document.querySelector('input[name="d_price_to"]').value || '').trim();
                if (pf) params.set('price_from', pf);
                if (pt) params.set('price_to', pt);
                const region = (document.querySelector('select[name="d_region"]').value || '').trim();
                if (region) params.set('region', region);
                const states = Array.from(document.querySelectorAll('input[name="state[]"]:checked')).map(el => el.value);
                states.forEach(v => params.append('state[]', v));
                const availEl = document.querySelector('input[name="availability"]:checked');
                if (availEl) params.set('availability', availEl.value);
                const financing = document.querySelector('input[name="financing"]:checked') ? '1' : '';
                if (financing) params.set('financing', '1');
                const url = params.toString() ? `${base}?${params.toString()}` : base;
                window.location.href = url;
            });
        });
    </script>
    @endif
</x-layouts.site>