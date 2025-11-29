<x-layouts.site 
:title="__('seo.faq.title')" 
:description="__('seo.faq.description')"
>
    {{-- okr--}}
    <div class="w-full">
        <div class="container-glamspot">
            <div class="text-body-regular-s flex gap-2 items-center">
                <a href="{{ route('home') }}" title="{{ __('seo.welcome.title') }}" 
                class="hidden md:block text-zinc-600 hover:underline decoration-zinc-800/20 underline-offset-4">{{__('faq.okr.home')}}</a>
                <span class="hidden md:block text-zinc-400 ">|</span>
                <p class="text-zinc-600 text-body-regular-s hidden md:flex ">{{__('faq.okr.faq')}}</p>
            </div>
        </div>
    </div>

    {{-- hero  --}}
    <div class="full-colour">
        <div class="full-colour-container">
            <div class="w-full">
                <h1 class="text-white">
                    {{__('faq.hero.title')}}
                </h1>
            </div>
            <div class="text-body-regular-l text-white">
                {{__('faq.hero.subtitle')}}
            </div>
        </div>
    </div>


    <div class="w-full">
        <div class="container-glamspot flex w-full justify-center items-center">
            <div class="collapse collapse-arrow border-b border-base-300 rounded-none">
                <input type="radio" name="my-accordion-2" checked="checked" />
                <div class="collapse-title font-semibold">1. Czym jest GlamSpot?</div>
                <div class="collapse-content text-sm">
                    GlamSpot to innowacyjna platforma marketplace dla branży beauty, która łączy producentów, dystrybutorów i właścicieli salonów kosmetycznych. Umożliwia wynajem, zakup lub sprzedaż nowoczesnych urządzeń kosmetycznych HITECH w jednym miejscu, a także znalezienie pracownika czy interesujących szkoleń.
                </div>
            </div>
            
            
        </div> 
    </div> 


</x-layouts.site>