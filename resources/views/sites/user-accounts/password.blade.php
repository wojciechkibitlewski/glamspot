<x-layouts.site 
:title="__('seo.user-account.password.title')"
:description="__('seo.user-account.password.description')"
>
    <div class="w-full">
        
        <div class="max-w-7xl mx-auto px-6 lg:px-8 mb-8 py-8 min-h-[600px]">
            {{-- header --}}
            <div class="flex items-center justify-between  pb-4 mb-4 border-b border-zinc-200">
                <div class="space-y-1">
                    <flux:heading size="xl">{{__('user-account.password_heading')}}</flux:heading>
                </div>
            </div>
            
            <div class="flex items-start gap-8">
                @include('sites.user-accounts.partials.nav')

                <section class="w-full rounded-2xl border border-zinc-200 bg-gray-50 p-6">
                    
                    {{-- change password  --}}
                    <div class="flex flex-col w-full border-b border-gray-200 pb-4 ">
                        <div class="w-full mb-6">
                            <h3 class="text-body-medium-l mb-2">{{__('user-account.password_title')}}</h3>
                            <!-- Session Status -->
                            <x-auth-session-status class="text-center" :status="session('status')" />
                        </div>
                        <form method="POST" wire:submit="resetPassword" class="flex flex-col gap-6 mb-6">
                            <div class="flex flex-row w-full gap-4">
                                <div class="w-full">
                                    <!-- Password -->
                                    <flux:input
                                        wire:model="password"
                                        :label="__('user-account.new_password')"
                                        type="password"
                                        required
                                        autocomplete="new-password"
                                        :placeholder="__('user-account.new_password')"
                                        viewable
                                    />
                                </div>
                                <div class="w-full">
                                    <!-- Confirm Password -->
                                    <flux:input
                                        wire:model="password_confirmation"
                                        :label="__('user-account.confirm_new_password')"
                                        type="password"
                                        required
                                        autocomplete="new-password"
                                        :placeholder="__('user-account.confirm_new_password')"
                                        viewable
                                    />
                                </div>

                            </div>
                            <div class="flex flex-row w-full gap-4">
                                <div class="w-full">
                                    <flux:button type="submit" variant="primary" class="w-full my-btn">
                                        {{ __('user-account.save_password') }}
                                    </flux:button>
                                </div>
                                <div class="w-full"></div>
                            </div>
                            
                        </form>

                    </div>
                    
                    

                </section>
            </div>
        </div>
    </div>
</x-layouts.site>
