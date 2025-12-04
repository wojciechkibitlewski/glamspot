<x-layouts.auth>
    <div class="flex flex-col justify-center border border-slate-200 rounded-2xl gap-6 h-[600px] my-4 max-w-7xl mx-auto p-8 shadow-md bg-white dark:bg-slate-800 dark:border-slate-700">
        <flux:text class="text-center">
            {{ __('auth.verify_desc') }}
        </flux:text>

        @if (session('status') == 'verification-link-sent')
            <flux:text class="text-center font-medium !dark:text-green-400 !text-green-600">
                {{ __('auth.verify_send') }}
            </flux:text>
        @endif

        <div class="flex flex-col items-center justify-between space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <flux:button type="submit" variant="primary" class="w-full">
                    {{ __('auth.verify_button') }}
                </flux:button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
               <flux:button variant="ghost" type="submit" class="text-sm cursor-pointer" data-test="logout-button">
                    {{ __('auth.logout') }}
                </flux:button>
            </form>
        </div>

    </div>
</x-layouts.auth>
