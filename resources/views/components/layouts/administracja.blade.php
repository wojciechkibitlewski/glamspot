<x-layouts.app.admin 
:title="$title ?? null" 
:description="$description ?? null"
>
    
    {{ $slot }} 
    
    <div class="border-t border-zinc-200 dark:border-zinc-700 mt-8 px-4 bg-zinc-50 -mx-8">
        @include('partials.footer-menu')
        @include('partials.footer-legal')
    </div>
          

    
</x-layouts.app.admin>