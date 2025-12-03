<x-layouts.site 
:title="__('seo.user-account.notifications.title')"
:description="__('seo.user-account.notifications.description')">
    <div class="w-full">
        
        <div class="max-w-7xl mx-auto px-6 lg:px-8 mb-8 py-8 min-h-[600px]">
            
            {{-- header --}}
            <div class="flex items-center justify-between  pb-4 mb-4 border-b border-zinc-200">
                <div class="space-y-1">
                    <flux:heading size="xl">{{__('user-account.notifications_heading')}}</flux:heading>
                </div>
            </div>
            
            <div class="flex items-start gap-8">
                @include('sites.user-accounts.partials.nav')

                <section class="w-full rounded-2xl border border-zinc-200 bg-gray-50 p-6">
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-zinc-200">
                        <h2 class="text-xl font-medium">{{ __('user-account.notifications_title') }}</h2>
                    </div>

                    <div class="grid gap-4">
                        <flux:field variant="inline">
                            <flux:switch />
                            <flux:label class="!text-body-regular-s">{{__('user-account.email_notifications')}}</flux:label>
                        </flux:field>
                        <flux:field variant="inline">
                            <flux:switch />
                            <flux:label>{{__('user-account.email_promotions')}}</flux:label>
                        </flux:field>
                        
                        <flux:field variant="inline">
                            <flux:switch />
                            <flux:label>{{__('user-account.email_adds')}}</flux:label>
                        </flux:field>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-layouts.site>

