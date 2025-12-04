<div class="w-full flex flex-col md:flex-row justify-between py-8 gap-8 md:gap-1">
    <div class="w-full">
        <nav class="flex flex-col gap-2">
            <h6 class="text-body-medium-m">{{ __('footer.szybkie_linki') }}</h6>
            
            {{-- <a href="{{ route('firm') }}" title="{{ __('footer.firmy_title') }}" class="">{{ __('footer.firmy') }}</a> --}}
            <a href="{{ route('blog') }}" title="{{ __('footer.blog_title') }}" class="">{{ __('footer.blog') }}</a>
            <a href="{{ route('about') }}" title="{{ __('footer.onas_title') }}" class="">{{ __('footer.onas') }}</a>
            <a href="{{ route('faq') }}" title="{{ __('footer.faq_title') }}" class="">{{ __('footer.faq') }}</a>
            
            <a href="{{ route('user-account') }}" title="{{ __('footer.moje_konto_title') }}" class="">{{ __('footer.moje_konto') }}</a>
            <a href="{{ route('user-ads.create') }}" title="{{ __('footer.dodaj_ogloszenie_title') }}" class="">{{ __('footer.dodaj_ogloszenie') }}</a>
            
        </nav>
    </div>

    <div class="w-full">
        <nav class="flex flex-col gap-2">
            <h6 class="text-body-medium-m">{{ __('footer.ogloszenia') }}</h6>
            
            <a href="{{ route('ads.index') }}" title="{{ __('footer.wszystkie_title') }}" class="">{{ __('footer.wszystkie') }}</a>
            <a href="{{ route('ads.category', 'praca') }}" title="{{ __('footer.praca_title') }}" class="">{{ __('footer.praca') }}</a>
            <a href="{{ route('ads.category', 'urzadzenia-i-sprzet') }}" title="{{ __('footer.sprzet_urzadzenia_title') }}" class="">{{ __('footer.sprzet_urzadzenia') }}</a>
            <a href="{{ route('ads.category', 'szkolenia') }}" title="{{ __('footer.szkolenia_title') }}" class="">{{ __('footer.szkolenia') }}</a>
            <a href="{{ route('ads.category', 'inne') }}" title="{{ __('footer.inne_title') }}" class="">{{ __('footer.inne') }}</a>
            
        </nav>
    </div>
    <div class="flex flex-col w-full gap-4">
        <h6 class="text-body-medium-m">{{ __('footer.newsletter') }}</h6>
        <p class="text-body-regular-xs">{{ __('footer.newsletter_body') }}</p>

        <livewire:newsletter-subscribe />

        <p class="text-body-regular-xs  text-grey-400">{{ __('footer.newsletter_zgoda') }} <a href="{{ route('privacy') }}" title="{{ __('footer.polityka_prywatnosci') }}" class="underline  text-grey-400">{{ __('footer.polityka_prywatnosci') }}</a></p>
    </div>
    
</div>