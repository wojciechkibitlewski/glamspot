<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        @include('partials.gtm-noscript')

        <flux:header container class="flex flex-row bg-white justify-between h-[72px]">
            <a href="{{ route('home') }}" class="my-4" title="{{ __('header.logo_title') }}">
                <img src="{{ asset('storage/glamspot_footer_logo.png') }}" alt="{{ __('header.glamspot_logo_alt') }}" class="w-[140px]" />
            </a>
            <flux:spacer />
            
            <flux:navbar class="flex gap-8 -mb-px max-lg:hidden !font-normal !text-grey-900">
                <a href="{{ route('ads.index') }}" class="text-body-regular-m" title="{{__('header.navbar.ads.title')}}">{{__('header.navbar.ads')}}</a>
                <a href="#" class="text-body-regular-m" title="{{__('header.navbar.firm.title')}}">{{__('header.navbar.firm')}}</a>
                <a href="{{ route('blog') }}" class="text-body-regular-m" title="{{__('header.navbar.blog.title')}}">{{__('header.navbar.blog')}}</a>
                <a href="#" class="text-body-regular-m" title="{{__('header.navbar.about.title')}}">{{__('header.navbar.about')}}</a>
            </flux:navbar>

            <flux:spacer />

            <flux:navbar class="flex gap-8 -mb-px max-lg:hidden !font-normal !text-grey-900">
                <a href="{{ route('user-account.index') }}" class="text-body-regular-m" title="{{__('header.navbar.user-account')}}">
                    {{__('header.navbar.user-account')}}
                </a>
                @auth
                    <a href="{{route('user-ads.create')}}" class="text-body-regular-m border border-gray-300 flex justify-center gap-3 px-4 py-2 rounded-full font-medium-l hover:no-underline hover:bg-grey-50 cursor-pointer" title="{{ __('header.navbar.post-ad') }}">
                        {{ __('header.navbar.post-ad') }}
                        <flux:icon.plus variant="solid" />
                    </a>
                @endauth
                @guest
                    <flux:modal.trigger name="add-ad">
                        <button class="text-body-regular-m border border-gray-300 flex justify-center gap-3 px-4 py-2 rounded-full font-medium-l hover:no-underline hover:bg-grey-50 cursor-pointer" title="{{ __('header.navbar.post-ad') }}">
                            {{ __('header.navbar.post-ad') }}
                            <flux:icon.plus variant="solid" />
                        </button>
                    </flux:modal.trigger>
                @endguest
                

                @if( Auth()->user() )
                <flux:dropdown position="bottom" align="end">
                    @php
                        $user = auth()->user();
                        $avatar = $user?->avatar;
                        $avatarUrl = $avatar ? (\Illuminate\Support\Str::startsWith($avatar, ['http://', 'https://']) ? $avatar : asset('storage/' . ltrim($avatar, '/'))) : null;
                    @endphp
                    <button type="button" class="group flex items-center rounded-full p-1 bg-zinc-100 hover:bg-zinc-200 cursor-pointer">
                        <div class="shrink-0">
                            @if (auth()->user()->avatar)
                                <img src="{{ asset('storage/'.auth()->user()->avatar) }}" title="" 
                                class="rounded-full size-10" />
                            @else
                                <flux:avatar size="sm" circle :name="auth()->user()->name" />
                            @endif
                        </div>
                        <div class="shrink-0 ms-auto size-8 flex justify-center items-center">
                            <flux:icon icon="chevron-down" class="text-zinc-400 dark:text-white/80 group-hover:text-zinc-800 size-4" />
                        </div>
                    </button>
                    
                    <flux:menu>
                        <flux:menu.item href="{{ route('user-account.index') }}">{{__('header.navbar.user-account')}}</flux:menu.item>
                        <flux:menu.item href="{{ route('user-ads.index') }}" >{{__('header.navbar.my-ads')}}</flux:menu.item>
                        <flux:menu.item href="{{ route('user-account.billing') }}" >{{__('header.navbar.payments')}}</flux:menu.item>
                        <flux:menu.separator />
                        {{-- @role('Administrator')
                        <flux:menu.item href="{{ route('admin.index') }}" >Panel administratora</flux:menu.item>
                        <flux:menu.separator />
                        @endrole --}}
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" class="w-full cursor-pointer" data-test="logout-button">
                                {{ __('header.navbar.logout') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
                @endif
            </flux:navbar>

            <flux:sidebar.toggle class="lg:hidden !size-12" icon="bars-3" inset="right" />
        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar stashable sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 w-[80%]!">
            <flux:sidebar.header>
                <flux:sidebar.brand
                    href="{{ route('home') }}"
                    logo="{{ asset('storage/glamspot_footer_logo.png') }}"
                    logo:dark="{{ asset('storage/glamspot_footer_logo.png') }}"
                />
                <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
            </flux:sidebar.header>
            <flux:sidebar.nav class="text-body-regular-l flex flex-col my-6">
                <a href="{{  route('ads.index') }}" class="py-4 border-b border-grey-200" title="{{__('header.navbar.ads.title')}}">{{__('header.navbar.ads')}}</a>
                <a href="#" class="py-4 border-b border-grey-200" title="{{__('header.navbar.firm.title')}}">{{__('header.navbar.firm')}}</a>
                <a href="{{ route('blog')}}" class="py-4 border-b border-grey-200" title="{{__('header.navbar.blog.title')}}">{{__('header.navbar.blog')}}</a>
                <a href="#" class="py-4 border-b border-grey-200" title="{{__('header.navbar.about.title')}}">{{__('header.navbar.about')}}</a>
            </flux:sidebar.nav>
            <flux:sidebar.nav class="flex flex-col my-6 justify-center gap-6">
                <a href="{{route ('user-ads.create')}}" class="flex justify-center gap-3 px-8 py-4 rounded-full bg-gradient-to-r from-[#BA75EC] to-[#1FC2D7] text-white font-medium-l text-body-medium-m hover:opacity-90 transition  cursor-pointer">
                    {{ __('header.navbar.post-ad') }}
                    <flux:icon.plus variant="solid" />
                </a>
                @if( Auth()->user() )
                @else
                    <a href="{{ route('user-account') }}" class="text-body-regular-l py-4 text-center" title="{{__('header.navbar.user-account')}}">{{__('header.navbar.user-account')}}</a>
                @endif
            </flux:sidebar.nav>
            <flux:sidebar.spacer />
            @if( Auth()->user() )
                @php
                    $user = auth()->user();
                    $avatar = $user?->avatar;
                    $avatarUrl = $avatar ? (\Illuminate\Support\Str::startsWith($avatar, ['http://', 'https://']) ? $avatar : asset(ltrim($avatar, '/'))) : null;
                @endphp
                <flux:sidebar.nav class="text-body-regular-l flex flex-col my-6">
                    <div class="mt-6 flex items-center gap-4">
                        <flux:avatar circle :src="$avatarUrl" :name="$user->name" />
                        <div>
                            <flux:heading size="lg">{{ $user->name }}</flux:heading>
                        </div>
                    </div>
                    <a href="{{ route('user-account.index') }}" title="{{__('header.navbar.user-account')}}" class="py-4 border-b border-grey-400">{{__('header.navbar.user-account')}}</a>
                    <a href="{{ route('user-ads.index') }}" title="{{__('header.navbar.my-ads')}}" class="py-4 border-b border-grey-400">{{__('header.navbar.my-ads')}}</a>
                    <a href="{{ route('user-account.billing') }}" title="{{__('header.navbar.payments')}}" class="py-4 ">{{__('header.navbar.payments')}}</a>
                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" class="w-full cursor-pointer mx-0 px-0" data-test="logout-button">
                            {{ __('header.navbar.logout') }}
                        </flux:menu.item>
                    </form>
                </flux:sidebar.nav>

            @endif

        </flux:sidebar>

        {{ $slot }}

        <flux:modal name="add-ad" variant="flyout">
            <livewire:ads.create-ad />
        </flux:modal> 

        @fluxScripts
        @livewireScripts
    </body>
</html>
