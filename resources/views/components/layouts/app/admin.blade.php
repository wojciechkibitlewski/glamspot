<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        @include('partials.head-admin')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800 antialiased">
        <flux:sidebar sticky collapsible="mobile" class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.header>
                <a href="{{ route('admin.dashboard') }}" class="my-4" title="{{ __('header.logo_title') }}">
                    <img src="{{ asset('storage/glamspot_footer_logo.png') }}" alt="{{ __('header.glamspot_logo_alt') }}" class="w-[180px]" />
                </a>
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>
            
            <x-mary-menu activate-by-route>
                <x-mary-menu-item title="Użytkownicy" link="{{ route('admin.users')}}" />
                <x-mary-menu-item title="Role użytkowników" link="{{ route('admin.roles.index') }}" />
                <x-mary-menu-separator />
                <x-mary-menu-sub title="Ogłoszenia">
                    <x-mary-menu-item title="Ogłoszenia" link="{{  route('admin.ads') }}" />
                    <x-mary-menu-item title="Kategorie ogłoszeń" link="{{ route('admin.ads.categories.index') }}" />
                    <x-mary-menu-item title="Podkategorie ogłoszeń" link="{{ route('admin.ads.subcategories.index') }}" />
                    <x-mary-menu-item title="Szkolenia. Branże" link="#" />
                    <x-mary-menu-item title="Praca. Branże" link="#" />
                    <x-mary-menu-item title="Filtry" link="#" />
                </x-mary-menu-sub>
                <x-mary-menu-item title="Firmy" link="#" />
                <x-mary-menu-separator />
                <x-mary-menu-sub title="Blog">
                    <x-mary-menu-item title="Blog" link="{{ route('admin.blog') }}" />
                    <x-mary-menu-item title="Kategorie bloga" link="{{ route('admin.blog.categories.index') }}" />
                </x-mary-menu-sub>
                <x-mary-menu-item title="Płatności" link="{{ route('admin.payments') }}" />
                <x-mary-menu-sub title="Ustawienia">
                    <x-mary-menu-item title="Ustawienia" link="{{ route('admin.settings')}}" />
                    <x-mary-menu-item title="Województwa" link="#" />
                    <x-mary-menu-item title="Miasta" link="#" />
                    <x-mary-menu-item title="Szablony wiadomości" link="#" />
                    <x-mary-menu-item title="Tłumaczenia" link="#" />
                </x-mary-menu-sub>
                
            </x-mary-menu>
            
            <flux:sidebar.spacer />
            <flux:sidebar.nav>
                <flux:sidebar.item icon="arrow-right" href="{{ route('home')}}" target="__blank">Serwis ogłoszeniowy</flux:sidebar.item>
            </flux:sidebar.nav>

            <flux:dropdown position="top" align="start" class="max-lg:hidden">
                @php
                    $user = auth()->user();
                    $avatar = $user?->avatar;
                    $avatarUrl = $avatar ? (\Illuminate\Support\Str::startsWith($avatar, ['http://', 'https://']) ? $avatar : asset('storage/' . ltrim($avatar, '/'))) : null;
                @endphp

                @if (auth()->user()->avatar)
                    <flux:sidebar.profile avatar="{{ asset('storage/'.auth()->user()->avatar) }}" name="{{ auth()->user()->name }}" />
                @else
                    <flux:sidebar.profile size="sm" circle :name="auth()->user()->name" />
                @endif
                
                
                <flux:menu>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item type="submit" class="cursor-pointer" data-test="logout-button" icon="arrow-right-start-on-rectangle">
                            {{ __('header.navbar.logout') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <flux:header class="block! bg-white lg:bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
            <flux:navbar class="lg:hidden w-full">
                <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
                <flux:spacer />
                <flux:dropdown position="top" align="start">
                     @php
                        $user = auth()->user();
                        $avatar = $user?->avatar;
                        $avatarUrl = $avatar ? (\Illuminate\Support\Str::startsWith($avatar, ['http://', 'https://']) ? $avatar : asset('storage/' . ltrim($avatar, '/'))) : null;
                    @endphp
                    @if (auth()->user()->avatar)
                        <flux:profile avatar="{{ asset('storage/'.auth()->user()->avatar) }}" name="{{ auth()->user()->name }}" />
                    @else
                        <flux:profile size="sm" circle :name="auth()->user()->name" />
                    @endif
                    <flux:menu>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" class="cursor-pointer" data-test="logout-button" icon="arrow-right-start-on-rectangle">
                                {{ __('header.navbar.logout') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </flux:navbar>
            
            <flux:navbar scrollable>
                {{--                 
                <flux:navbar.item href="#" current>Dashboard</flux:navbar.item>
                <flux:navbar.item badge="32" href="#">Orders</flux:navbar.item>
                <flux:navbar.item href="#">Catalog</flux:navbar.item>
                <flux:navbar.item href="#">Configuration</flux:navbar.item>
                 --}}
            </flux:navbar>

        </flux:header>
        <flux:main>

            {{ $slot }}
        </flux:main>
        @fluxScripts
        @stack('scripts')
    </body>
</html>


