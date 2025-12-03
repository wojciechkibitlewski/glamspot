@php
    $phCategoryName = $categoryName ?? ($selectedCategory->name ?? null);
    $placeholder = $phCategoryName
        ? 'Szukaj ogłoszeń w kategorii ' . $phCategoryName
        : 'Szukaj ogłoszeń';
    $currentCity = $city ?? request('city');
    $currentSearch = $search ?? request('q');
@endphp


<div class="w-full flex items-center justify-center bg-linear-to-l from-[#E446B4] to-[#6A80CE] py-8 mb-8">
    <div class="flex flex-row gap-4 w-full max-w-7xl mx-auto px-6 lg:px-8">
        <form method="GET" action="{{ route('ads.index') }}" class="flex flex-col md:flex-row w-full  bg-white p-0 md:p-3 md:px-6 md:pr-3 rounded-3xl md:rounded-full gap-6">
            <div class="flex flex-row w-full gap-2 items-center border-gray-200 border-b md:border-0 md:border-r p-2 px-4 md:p-0">
                <flux:icon.magnifying-glass class="text-gray-400 size-5" />
                <input 
                type="search" 
                name="q" 
                class="w-full rounded-lg block text-base py-2 h-10 leading-[1.375rem] text-zinc-700  placeholder-zinc-400 bg-white p-4 pl-2 outline-transparent"
                placeholder="{{ isset($selectedCategory) && $selectedCategory ? ('Szukaj ogłoszeń w kategorii ' . ($selectedCategory->name ?? '')) : 'Szukaj ogłoszeń' }}" 
                value="{{ $search ?? request('q') }}"
                />
            </div>

            @php($currentCity = request('city'))
            <div x-data="citySearch(@js($currentCity ?? ''))" class="relative flex flex-row w-full md:max-w-[250px] gap-2 items-center px-4 md:p-0">
                <flux:icon.map-pin />
                <input
                    type="text"
                    name="city"
                    x-model="value"
                    @input.debounce.300ms="onInput"
                    @focus="open = true"
                    @keydown.escape.window="open = false"
                    class="w-full rounded-lg block text-base py-2 h-10 leading-5.5 text-zinc-700 placeholder-zinc-400 bg-white p-4 pl-2 outline-transparent transition duration-300 ease focus:outline-none"
                    placeholder="Wpisz miasto"
                    value="{{ $currentCity }}"
                    autocomplete="off"
                />
                <div x-show="open && suggestions.length" x-cloak class="absolute left-0 right-0 top-full mt-1 w-full rounded-md border border-zinc-200 bg-white shadow z-50">
                    <template x-for="item in suggestions" :key="item.id">
                        <button type="button" class="block w-full text-left px-3 py-2 text-sm hover:bg-zinc-50" @click="select(item.name)" x-text="item.name"></button>
                    </template>
                </div>
    


            </div>
            
            <div class="flex items-center justify-end p-4 md:p-0">
                <button 
                type="submit" 
                class="w-full px-6 py-3 rounded-full bg-gradient-to-r from-[#BA75EC] to-[#1FC2D7] text-white font-medium text-body-medium-m hover:opacity-90 transition">
                Szukaj
                </button>                       
            </div>
        </form>
    </div>
</div>