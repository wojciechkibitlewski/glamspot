<div class="flex flex-col items-center justify-center w-full bg-linear-to-l from-[#E446B4] to-[#6A80CE] py-12 px-6 h-[200px]">
            {{-- newsletter box --}}
            
        </div>
        <div class="max-w-7xl w-full mx-auto -mt-[125px]">
            <div class="bg-white rounded-3xl shadow-lg p-12 md:p-16">
                <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                    {{-- Left side - Text --}}
                    <div class="w-full md:w-1/2 text-left">
                        <h5 class="text-[28px] text-zinc-900 mb-4">{{__('newsletter.title')}}</h5>
                        <p class="text-body-regular-l leading-relaxed">
                            {{__('newsletter.desc_short')}}
                        </p>
                    </div>

                    {{-- Right side - Form --}}
                    <div class="w-full md:w-1/2">
                        <form action="#" method="POST" class="space-y-4">
                            @csrf
                            <div class="flex gap-4">
                                <div class="relative w-3/4">
                                    <input
                                        type="email"
                                        name="email"
                                        placeholder="{{__('newsletter.email_placeholder')}}"
                                        required
                                        class="w-full px-6 py-4 rounded-full border border-zinc-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 focus:outline-none text-zinc-900 placeholder-zinc-400"
                                    >
                                </div>
                                <div class="relative w-1/4">
                                    <button
                                        type="submit"
                                        class="w-full md:w-auto px-8 py-4 bg-linear-to-r from-[#BA75EC] to-[#1FC2D7] text-white font-semibold rounded-full hover:opacity-90 transition-opacity shadow-lg"
                                    >
                                        {{__('newsletter.subscribe_button')}}
                                    </button>
                                </div>
                            </div>
                            <p class="text-xs text-zinc-500 mt-3">
                                {{__('newsletter.consent')}}
                                <a href="{{ route('privacy') }}" class="underline hover:text-purple-600">{{__('newsletter.privacy_policy')}}</a>.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>