<div class="flex flex-col-reverse md:flex-row md:justify-between w-full rounded-2xl items-stretch my-6">
    <div class="w-full flex flex-col justify-center gap-8 pb-[60px]">
        <h1 class="md:text-xl lg:text-3xl mt-10 md:mt-0">
            <img src="{{ asset('storage/glamspot_footer_logo.png') }}" alt="{{ __('welcome.glamspot_footer_logo_alt') }}" 
            class="w-60 md:w-60 lg:w-[340px] mb-2" /> 
            {{__('welcome.hero_subtitle')}}
        </h1>
        <div class="md:text-body-regular-m lg:text-body-regular-l pr-10">
            {{ __('welcome.hero_text') }}
        </div>

        {{-- wyszukiwarka welcome --}}
        @include('sites.welcome.partials.search-form')

    </div>
    
    <div class="w-full">
        <img src="{{ asset('storage/glamspot_hero_image.png') }}" alt="{{ __('welcome.glamspot_hero_image_alt') }}" class="w-full" />
    </div>
</div>