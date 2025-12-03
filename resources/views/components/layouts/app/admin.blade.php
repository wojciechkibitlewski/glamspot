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
            
            <flux:sidebar.nav>
                <flux:sidebar.item icon="arrow-right" href="{{ route('admin.dashboard') }}" current>Dashboard</flux:sidebar.item>
                <flux:sidebar.item icon="arrow-right" href="{{ route('admin.users')}}">Użytkownicy</flux:sidebar.item>
                <flux:sidebar.item icon="arrow-right" href="{{  route('admin.ads') }}">Ogłoszenia</flux:sidebar.item>
                <flux:sidebar.item icon="arrow-right" href="{{ route('admin.firms') }}">Firmy</flux:sidebar.item>

                <flux:sidebar.item icon="arrow-right" href="{{ route('admin.blog') }}">Blog</flux:sidebar.item>
                <flux:sidebar.item icon="arrow-right" href="{{ route('admin.payments') }}">Płatności</flux:sidebar.item>
                <flux:sidebar.item icon="arrow-right" href="{{ route('admin.settings')}}">Ustawienia</flux:sidebar.item>
                
            </flux:sidebar.nav>
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
            {{-- <flux:heading size="xl" level="1">Good afternoon, Olivia</flux:heading>
            <flux:text class="mb-6 mt-2 text-base">Here's what's new today</flux:text>
            <flux:separator variant="subtle" /> --}}

            {{ $slot }}
        </flux:main>
        @fluxScripts
    </body>
</html>


