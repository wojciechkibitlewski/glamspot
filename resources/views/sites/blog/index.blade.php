<x-layouts.site 
:title="__('seo.blog.title')" 
:description="__('seo.blog.description')"
>
    <div class="w-full">
        {{-- Breadcrumbs --}}
        <div class="max-w-7xl mx-auto px-6 lg:px-8 my-6">
            <div class="text-body-regular-s text-zinc-400 flex gap-2 items-center">
                <a href="{{ route('home') }}" 
                    title="{{__('blog.okr.home')}}" 
                    class="hidden md:block text-zinc-800 hover:underline decoration-zinc-800/20 underline-offset-4">
                    {{__('blog.okr.home')}}
                </a>
                <span class="hidden md:block">|</span>
                <a href="{{ route('blog') }}" 
                    title="{{__('blog.okr.blog')}}" 
                    class="block text-zinc-800 hover:underline decoration-zinc-800/20 underline-offset-4">
                    {{__('blog.okr.blog')}}
                </a>
            </div>
        </div>

        <div class="flex flex-col gap-8 w-full bg-linear-to-l from-[#E446B4] to-[#6A80CE] py-8 h-[300px] mb-[300px]">
            {{-- header  --}}
            <div class="max-w-7xl mx-auto px-6 lg:px-8 w-full gap-10">
                <div class="flex flex-col md:flex-row md:justify-between w-full">
                    <div class="w-full">
                        <h1 class="text-white">{{ __('blog.hero.title') }}</h1>
                    </div>
                    <div class="w-full">
                        <p class="text-body-regular-l text-white/90 md:text-right">
                            {{__('blog.hero.subtitle')}}
                        </p>
                    </div>
                </div>
                {{-- first post  --}}
                @if($latestPost)
                    <div class="flex justify-between max-w-7xl mx-auto bg-white rounded-lg shadow-lg w-full mt-8 overflow-hidden hover:shadow-xl transition">
                        <div class="w-full">
                            <a href="{{ route('blog.show', ['slug' => $latestPost->category?->slug ?? 'uncategorized', 'code' => $latestPost->code, 'postSlug' => $latestPost->slug]) }}" 
                                title="{{ $latestPost->title }}">
                                @if($latestPost->featured_image)
                                    <img src="{{ $latestPost->featured_image_url }}" alt="{{ $latestPost->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-linear-to-br from-purple-100 to-blue-100"></div>
                                @endif
                            </a>
                        </div>
                        <div class="flex flex-col justify-center w-full p-16 text-left gap-4">
                            @if($latestPost->category)
                                <h5 class="text-body-regular-s text-purple-600">
                                    <a href="{{ route('blog.category', $latestPost->category->slug) }}" title="{{ $latestPost->category->category }}">
                                        {{ $latestPost->category->category }}
                                    </a>
                                </h5>
                            @endif
                            <h2 class="text-body-medium-l text-[20px]">
                                <a href="{{ route('blog.show', ['slug' => $latestPost->category?->slug ?? 'uncategorized', 'code' => $latestPost->code, 'postSlug' => $latestPost->slug]) }}"
                                   title="{{ $latestPost->title }}"
                                   class="hover:underline hover:underline-offset-4">
                                    {{ $latestPost->title }}
                                </a>
                            </h2>
                            @if($latestPost->lead)
                                <p class="text-body-regular-s text-zinc-600 line-clamp-3">{{ $latestPost->lead }}</p>
                            @endif
                            <div class="my-4">
                                <a href="{{ route('blog.show', ['slug' => $latestPost->category?->slug ?? 'uncategorized', 'code' => $latestPost->code, 'postSlug' => $latestPost->slug]) }}"
                                   class="inline p-2 px-4 border text-body-regular-s border-gray-300 rounded-full hover:no-underline hover:bg-gray-100 w-auto"
                                   title="{{ $latestPost->title }}">
                                    {{__('blog.see_more')}} →
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- END first post  --}}

            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 mb-8">
            {{-- kategorie  --}}
            <aside class="flex flex-row w-full gap-6 my-12 overflow-x-auto py-4">
                <div class="">
                    <a href="{{ route('blog') }}" class="inline p-2 px-4 border  border-gray-300 rounded-full hover:no-underline hover:bg-gray-100 w-auto" title="">Wszystkie</a>
                </div>
                @foreach($categories as $category)
                    <div class="">
                        <a href="{{ route('blog.category', $category->slug) }}" class="inline p-2 px-4 border  border-gray-300 rounded-full hover:no-underline hover:bg-gray-100 w-auto" title="{{ $category->category }}"> {{ $category->category }}</a>
                    </div>

                @endforeach
            </aside>
            <main class="flex-1">
                    @if($posts->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                            @foreach($posts as $post)
                                <article class="bg-white overflow-hidden ">
                                    <a href="{{ route('blog.show', ['slug' => $post->category?->slug ?? 'uncategorized', 'code' => $post->code, 'postSlug' => $post->slug]) }}" 
                                        title="{{ $post->title }}">
                                        @if($post->featured_image)
                                            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" 
                                            class="w-full aspect-3/2 object-cover rounded-xl">
                                        @else
                                            <div class="w-full aspect-3/2 object-cover bg-linear-to-br from-purple-100 to-blue-100 rounded-xl"></div>
                                        @endif
                                    </a>
                                    <div class="flex flex-col gap-2 py-6">
                                        @if($post->category)
                                            <div class="text-body-regular-s">
                                                {{ $post->category->category }}
                                            </div>
                                        @endif

                                        <h3 class="text-body-medium-l text-[20px] line-clamp-2">
                                            <a 
                                                href="{{ route('blog.show', ['slug' => $post->category?->slug ?? 'uncategorized', 'code' => $post->code, 'postSlug' => $post->slug]) }}" 
                                                class="hover:underline-offset-4 hover:underline"
                                                >
                                                {{ $post->title }}
                                            </a>
                                        </h3>

                                        @if($post->lead)
                                            <p class="text-body-regular-s line-clamp-3">{{ $post->lead }}</p>
                                        @endif
                                        <div class="my-4">
                                            <a href="{{ route('blog.show', ['slug' => $post->category?->slug ?? 'uncategorized', 'code' => $post->code, 'postSlug' => $post->slug]) }}" 
                                                class="inline p-2 px-4 border text-body-regular-s border-gray-300 rounded-full hover:no-underline hover:bg-gray-100 w-auto" 
                                                title="{{ $post->title }}"
                                                >
                                                {{__('blog.see_more')}}
                                            </a>
                                        </div>

                                        
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="bg-white rounded-2xl border border-zinc-200 p-12 text-center">
                            <p class="text-zinc-500">{{__('blog.no_posts')}}</p>
                        </div>
                    @endif
            </main>

            
        </div>
    </div>
    
</x-layouts.site>