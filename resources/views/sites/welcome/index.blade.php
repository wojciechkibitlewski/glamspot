<x-layouts.site 
:title="__('seo.welcome.title')" 
:description="__('seo.welcome.description')"
>
    {{-- hero --}}
    <div class="container-glamspot mb-12">
        @include('sites.welcome.partials.hero')
    </div> 
    {{-- przeglądaj kategorie --}}
    @include('sites.welcome.partials.categories') 

    {{-- FAQ  --}}
    @include('sites.welcome.partials.faq')
</x-layouts.site>