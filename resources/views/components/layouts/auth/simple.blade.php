<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-white dark:!text-zinc-900">
          {{ $slot }}
    @livewireScripts
    @fluxScripts
</body>
</html>
