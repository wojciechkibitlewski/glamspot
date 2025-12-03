<div class="flex flex-col md:flex-row w-full gap-8 my-8">
                
    <div class="flex flex-col gap-6 w-full md:w-2/3 ">
            
        @if (!empty($slides))
            <div class="ad-carousel">
                <x-mary-carousel :slides="$slides" class="h-[450px]" without-indicators/>
            </div>
        @endif
            
        {{-- podział na kategorie --}}
        @switch($ad->category->slug)
            @case('praca')
                @include('sites.user-ads.partials.show-work')
                @break
            @case('szkolenia')
                @include('sites.user-ads.partials.show-courses')
                @break
            @case('urzadzenia-i-sprzet')
                @include('sites.user-ads.partials.show-devices')
                @break
            @default
                @include('sites.user-ads.partials.show-others')
        @endswitch

        {{-- mapa  --}}
        <div class="flex flex-col gap-4 w-full my-4">
            <h2 class="text-[28px] md:text-[32px]  font-medium">{{__('ads.address')}}</h2>
            
            <div class="flex gap-8">
                <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="56" height="56" rx="28" fill="url(#paint0_linear_97_5506)"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M28.0008 29.5922C28.8492 29.5922 29.6629 29.2553 30.263 28.6556C30.863 28.0558 31.2004 27.2423 31.2008 26.3938C31.2008 25.5452 30.8636 24.7312 30.2635 24.1311C29.6634 23.531 28.8495 23.1938 28.0008 23.1938C27.1521 23.1938 26.3382 23.531 25.738 24.1311C25.1379 24.7312 24.8008 25.5452 24.8008 26.3938C24.8012 27.2423 25.1385 28.0558 25.7386 28.6556C26.3387 29.2553 27.1524 29.5922 28.0008 29.5922Z" stroke="white" stroke-width="1.6" stroke-linecap="square"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M37.6004 26.3936C37.6004 34.3888 29.6004 39.1856 28.0004 39.1856C26.4004 39.1856 18.4004 34.3888 18.4004 26.3936C18.4021 23.8487 19.4143 21.4085 21.2144 19.6096C23.0146 17.8106 25.4554 16.8 28.0004 16.8C33.3012 16.8 37.6004 21.096 37.6004 26.3936Z" stroke="white" stroke-width="1.6" stroke-linecap="square"/>
                    <defs>
                    <linearGradient id="paint0_linear_97_5506" x1="56" y1="-6" x2="-37" y2="85.5" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#4FAADE"/>
                    <stop offset="1" stop-color="#9E83E8"/>
                    </linearGradient>
                    </defs>
                </svg>
                @php
                    $displayCity = $ad->as_company ? ($ad->company_city ?? '') : ($ad->location ?? $ad->person_city ?? '');
                    if ($ad->as_company) {
                        $parts = [];
                        if ($ad->company_address) { $parts[] = $ad->company_address; }
                        if ($ad->company_postalcode) { $parts[] = $ad->company_postalcode; }
                        if ($ad->company_phone) { $parts[] = 'tel. '.$ad->company_phone; }
                        $displayLine = implode(', ', $parts);

                        $mapString = trim(
                            ($ad->company_address ? $ad->company_address.' ' : '').
                            ($ad->company_postalcode ? $ad->company_postalcode.' ' : '').
                            ($ad->company_city ?? '')
                        );
                    } else {
                        $displayLine = '';
                        $mapString = trim(($ad->location ?? $ad->person_city ?? ''));
                    }
                    $mapSrc = 'https://www.google.com/maps?q='.urlencode($mapString).'&output=embed';
                @endphp

                <div>
                    <p class="text-body-bold-m">{{ $displayCity ?: '—' }}</p>
                    @if ($ad->as_company && $displayLine !== '')
                        <p class="text-body-regular-s">{{ $displayLine }}</p>
                    @endif
                </div>
            </div>
            <iframe
                src="{{ $mapSrc }}"
                width="100%"
                height="100%"
                class="h-80 w-full border-0"
                style="min-height: 420px;"
                allowfullscreen
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
        </div>

    </div>

    {{-- kontakt z ogłoszeniodawcą  --}}
    <div class="flex flex-col gap-6 w-full md:w-1/3">
        <div class="relative w-full flex flex-row items-center rounded-xl object-cover bg-gray-50 mb-4 p-6 gap-6">
            @if ($ad->as_company)
                @if ($ad->user?->firm?->avatar)
                    <img src="{{ asset('storage/'.$ad->user->firm->avatar) }}" title="" 
                        class="rounded-full size-16" />
                @else
                    <flux:avatar size="xl" circle :name="$ad->company_name ?? $ad->user?->name" />
                @endif

            @else
                @if ($ad->user?->avatar)
                    <img src="{{ asset('storage/'.$ad->user->avatar) }}" title="" 
                        class="rounded-full size-16" />
                @else
                    <flux:avatar size="xl" circle :name="$ad->person_name ?? $ad->user?->name" />
                @endif
            @endif
            <div class="">
                <div class="text-body-bold-m">
                    {{ $ad->as_company ? ($ad->company_name ?? $ad->user?->firm?->firm_name ?? $ad->user?->name) : ($ad->person_name ?? $ad->user?->name) }}
                </div>
                <p class="text-body-regular-s">{{ $displayCity ?? $ad->location ?? '—' }}</p>
            </div>
        </div>
        <div class="w-full flex flex-col rounded-xl mb-4 p-6 gap-6 border border-gray-200 sticky top-6 self-start">
            <div class="flex flex-col gap-1">
                <h4 class="text-body-medium-l text-xl">{{__('ads.contact')}}</h4>
                <p class="text-body-regular-s">{{ __('ads.contact_desc') }}</p>
            </div>
            {{-- kontakt z ogłoszeniodawcą form  --}}
            <livewire:contact-advertiser :ad-id="$ad->id" />
            {{-- END kontakt z ogłoszeniodawcą form  --}}
        </div>
    </div>
        
</div>