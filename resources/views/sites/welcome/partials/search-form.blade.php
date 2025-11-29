<form method="GET" action="#" 
class="md:absolute flex flex-col md:flex-row md:w-[600px] lg:w-[800px] bg-white p-0 md:p-3 md:px-6 md:pr-3 rounded-3xl md:rounded-full gap-6 shadow-xl border border-zinc-100 md:mt-[350px] lg:mt-[400px]">
    <div class="flex flex-row w-full gap-2 items-center border-gray-200 border-b md:border-0 md:boder-r p-2 px-4 md:p-0">
        <flux:icon.magnifying-glass class="text-gray-400 size-5" />
        <input 
            type="search" 
            name="q" 
            class="w-full rounded-lg block text-base py-2 h-10 leading-5.5 text-zinc-700  placeholder-zinc-400 bg-white p-4 pl-2 outline-transparent"
            placeholder="{{ __('welcome.search_search_ads') }}" 
            value="{{ request('q') }}"
        />
    </div>
    @php($currentCity = request('city'))
    <div x-data="citySearch('search', @js($currentCity ?? ''))" class="relative flex flex-row w-full  border-gray-200 border-b md:border-0 md:boder-r md:max-w-[250px] gap-2 items-center p-2 px-4 md:p-0">
        <flux:icon.map-pin />
        <input
            type="text"
            name="city"
            x-model="value"
            @input.debounce.300ms="onInput"
            @focus="open = true"
            @keydown.escape.window="open = false"
            class="w-full rounded-lg block text-base py-2 h-10 leading-5.5 text-zinc-700 placeholder-zinc-400 bg-white p-4 pl-2 outline-transparent transition duration-300 ease focus:outline-none"
            placeholder="{{__('welcome.search_city')}}"
            value="{{ $currentCity }}"
            autocomplete="off"
        />
        <div x-show="open && suggestions.length" x-cloak class="absolute left-0 right-0 top-full mt-1 w-full rounded-md border border-zinc-200 bg-white shadow z-50">
            <template x-for="item in suggestions" :key="item.id">
                <button type="button" class="block w-full text-left px-3 py-2 text-sm hover:bg-zinc-50" @click="select(item.name)" x-text="item.name"></button>
            </template>
        </div>
    </div>
    <div class="flex items-center justify-end p-4 pt-0 md:p-0">
        <button 
            type="submit" 
            class="w-full px-6 py-3 rounded-2xl md:rounded-full bg-linear-to-r from-[#BA75EC] to-[#1FC2D7] text-white font-medium text-body-medium-m hover:opacity-90 transition">
            {{__('welcome.search_search')}}
        </button>
    </div>
</form>
