<x-layouts.app.admin 
:title="$title ?? null" 
:description="$description ?? null"
>
    
    {{ $slot }} 
    
    @include('partials.footer-menu')
    @include('partials.footer-legal')
          

    
</x-layouts.app.admin>