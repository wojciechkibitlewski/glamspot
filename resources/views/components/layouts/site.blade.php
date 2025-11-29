<x-layouts.app.header 
:title="$title ?? null" 
:description="$description ?? null"
>
    <flux:main class="glamspot-main">
        {{ $slot }} 
        <div container class="container-glamspot">
            @include('partials.footer-menu')
            {{-- @include('partials.footer-legal') --}}
            {{-- @include('partials.footer-logo') --}}
        </div>

    </flux:main>
</x-layouts.app.header>