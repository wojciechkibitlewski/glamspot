<x-layouts.site
:title="__('newsletter.unsubscribe_title')"
:description="__('newsletter.unsubscribe_description')"
>
    <div class="w-full py-16">
        <div class="container-glamspot">

            <div class="flex flex-col w-full max-w-2xl mx-auto bg-white rounded-2xl shadow-lg p-8 md:p-12 text-center">
                @if($wasSubscribed)
                    <!-- Ikona sukcesu -->
                    <div class="mb-6">
                        <div class="w-20 h-20 mx-auto bg-gradient-to-r from-[#BA75EC] to-[#1FC2D7] rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>

                    <h1 class="text-3xl md:text-4xl font-bold mb-4 bg-gradient-to-r from-[#BA75EC] to-[#1FC2D7] bg-clip-text text-transparent">
                        {{ __('newsletter.unsubscribe_success_title') }}
                    </h1>

                    <p class="text-lg text-gray-600 mb-4">
                        {{ __('newsletter.unsubscribe_success_message') }}
                    </p>

                    <p class="text-gray-500 mb-8">
                        <strong>{{ $email }}</strong>
                    </p>

                    <p class="text-sm text-gray-500 mb-8">
                        {{ __('newsletter.unsubscribe_can_resubscribe') }}
                    </p>
                @else
                    <!-- Ikona informacji -->
                    <div class="mb-6">
                        <div class="w-20 h-20 mx-auto bg-gray-200 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <h1 class="text-3xl md:text-4xl font-bold mb-4 text-gray-700">
                        {{ __('newsletter.unsubscribe_not_found_title') }}
                    </h1>

                    <p class="text-lg text-gray-600 mb-4">
                        {{ __('newsletter.unsubscribe_not_found_message') }}
                    </p>

                    <p class="text-gray-500 mb-8">
                        <strong>{{ $email }}</strong>
                    </p>
                @endif

                <!-- Przycisk powrotu -->
                <div class="mt-8">
                    <a href="{{ route('home') }}" class="inline-block px-8 py-4 rounded-full bg-gradient-to-r from-[#BA75EC] to-[#1FC2D7] text-white font-medium hover:opacity-90 transition">
                        {{ __('newsletter.back_to_home') }}
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-layouts.site>
