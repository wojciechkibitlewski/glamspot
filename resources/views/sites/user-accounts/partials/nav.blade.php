@php
    $items = [
        ['label' => __('user-account.nav_profile'), 'route' => 'user-account.index'],
        ['label' => __('user-account.nav_firm'), 'route' => 'user-account.firm'],
        ['label' => __('user-account.nav_password'), 'route' => 'user-account.password'],
        ['label' => __('user-account.nav_billings'), 'route' => 'user-account.billing'],
        ['label' => __('user-account.nav_notifications'), 'route' => 'user-account.notifications'],
        ['label' => __('user-account.nav_newsletter'), 'route' => 'user-account.newsletter'],
        
    ];
    //  $items = [
    //     ['label' => __('user-account.nav_profile'), 'route' => 'user-account.index'],
    //     ['label' => __('user-account.nav_firm'), 'route' => 'user-account.firm'],
    //     ['label' => __('user-account.nav_password'), 'route' => 'user-account.password'],
    //     ['label' => __('user-account.nav_billings'), 'route' => 'user-account.billing'],
    //     ['label' => __('user-account.nav_notifications'), 'route' => 'user-account.notifications'],
    //     ['label' => __('user-account.nav_newsletter'), 'route' => 'user-account.newsletter'],
    // ];
    try {
        $hasAds = \App\Models\Add::query()->where('user_id', auth()->id())->exists();
    } catch (Throwable $e) {
        $hasAds = false;
    }
@endphp

<nav class="w-full md:w-64 shrink-0">
    <ul class="flex md:flex-col gap-2 md:gap-1">
        @foreach ($items as $i)
            @php $active = request()->routeIs($i['route']); @endphp
            <li>
                <a href="{{ route($i['route']) }}" class="block px-4 py-2 rounded-lg text-sm {{ $active ? 'bg-zinc-200 text-zinc-900' : 'text-zinc-600 hover:bg-zinc-50' }} hover:no-underline">
                    {{ $i['label'] }}
                </a>
            </li>
        @endforeach

        @if ($hasAds)
            <li class="my-1">
                <flux:separator />
            </li>
            @php $active = request()->routeIs('user-ads.index'); @endphp
            <li>
                <a href="#" class="block px-4 py-2 rounded-lg text-sm {{ $active ? 'bg-zinc-200 text-zinc-900' : 'text-zinc-600 hover:bg-zinc-50' }} hover:no-underline">
                    {{__('user-account.nav_ads')}}
                </a>
            </li>
        @endif
    </ul>
</nav>
