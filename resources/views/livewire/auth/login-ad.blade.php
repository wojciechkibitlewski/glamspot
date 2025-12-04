<div class="w-full">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 mb-4 gap-4 my-8 flex flex-col md:flex-row">
        <div class="flex justify-center items-center w-full text-left mb-6 lg:mb-0">
            <div class="p-4 lg:m-14 w-full">
                <x-auth-header-left class="dark:!text-zinc-800"
                    :title="__('Zaloguj się, aby dodać ogłoszenie')" 
                    :description="__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique. ')" 
                    />
                <!-- Session Status -->
                <x-auth-session-status class="text-left" :status="session('status')" />

                <form method="POST" wire:submit="login" class="flex flex-col gap-6 mt-6 font-light">
                    @csrf
                    <!-- Email Address -->
                    <flux:input
                        wire:model="email"
                        :label="__('Adres e-mail')"
                        type="email"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="email@example.com"
                    />

                    <!-- Password -->
                    <div class="relative">
                        <flux:input
                            wire:model="password"
                            :label="__('Hasło')"
                            type="password"
                            required
                            autocomplete="current-password"
                            :placeholder="__('Hasło')"
                            viewable
                        />

                        @if (Route::has('password.request'))
                            <a class="absolute top-0 text-sm end-0" href="route('password.request')" wire:navigate>
                                {{ __('Nie pamiętasz hasła?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Remember Me -->
                    <flux:checkbox wire:model="remember" :label="__('zapamiętaj mnie')" />

                    <div class="flex items-center justify-end">
                        <button data-test="login-button" type="submit" class="w-full px-8 py-4 rounded-full bg-linear-to-r from-[#BA75EC] to-[#1FC2D7] text-white font-medium text-body-medium-m hover:opacity-90 transition cursor-pointer">
                            {{ __('Zaloguj się') }}
                        </button>
                        
                    </div>
                    <div class="space-x-1 rtl:space-x-reverse text-left text-sm text-zinc-600 dark:text-zinc-400">
                        <span>{{ __('Nie masz konta?') }}</span>
                        <a href="route('register')" class="" wire:navigate>{{ __('Zarejestruj się') }}</a>
                    </div>
                </form>            
            </div>
        </div>
           
    </div>
</div>
