<x-layouts.site 
:title="__('seo.user-account.newsletter.title')"
:description="__('seo.user-account.newsletter.description')"
>
     <div class="w-full">
        
        <div class="max-w-7xl mx-auto px-6 lg:px-8 mb-8 py-8 min-h-[600px]">
            {{-- header --}}
            <div class="flex items-center justify-between  pb-4 mb-4 border-b border-zinc-200">
                <div class="space-y-1">
                    <flux:heading size="xl">{{__('user-account.newsletter_heading')}}</flux:heading>
                </div>
            </div>
            
            <div class="flex items-start gap-8">
                @include('sites.user-accounts.partials.nav')

                <section class="w-full rounded-2xl border border-zinc-200 bg-gray-50 p-6">

                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-zinc-200">
                        <h2 class="text-xl font-medium">{{__('user-account.newsletter_title')}}</h2>
                    </div>

                    <livewire:user.newsletter-settings />

                </section>
            </div>
        </div>
    </div>
</x-layouts.site>

