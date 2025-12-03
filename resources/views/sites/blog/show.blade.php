<x-layouts.site 
:title="$post->seo_title ?? $post->title" 
:description="$post->seo_description ?? __('seo.blog.description')"
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
                <span class="hidden md:block">|</span>
                <span class="text-body-regular-s block text-zinc-800">{{ Str::limit($post->title, 50) }}</span>
            </div>
        </div>

        {{-- Gradient Header with Title --}}
        <div class="flex flex-col items-center w-full bg-linear-to-l from-[#E446B4] to-[#6A80CE] py-12 px-6 min-h-[400px]">
            <div class="max-w-4xl mx-auto text-center">
                
            </div>
        </div>

        {{-- Featured Image --}}
        <div class="max-w-4xl mx-auto px-6 lg:px-8 mb-12 -mt-[350px] text-center">
            <div class="text-white/90 text-sm mb-4">
                <a href="{{ route('blog.category',$post->category?->slug) }}" title="{{ $post->category?->category ?? '-' }}" class="text-white">{{ $post->category?->category ?? '-' }}</a>
                <span class="text-white mx-2">•</span>
                <span class="text-white">{{ $post->published_at?->format('d M Y') ?? $post->created_at->format('d M Y') }}</span>
            </div>
            <h1 class="text-white mb-4">{{ $post->title }}</h1>
            @if($post->featured_image)
                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full aspect-video object-cover rounded-2xl">
            @elseif($post->photos->first())
                <img src="{{ Storage::url($post->photos->first()->photo) }}" alt="{{ $post->title }}" class="w-full aspect-video object-cover rounded-2xl">
            @else
                <div class="w-full aspect-video bg-linear-to-br from-purple-100 to-blue-100 rounded-2xl"></div>
            @endif
        </div>

        {{-- Article Content --}}
        <div class="max-w-4xl mx-auto px-6 lg:px-8 pb-16">
            <article class="bg-white">
                {{-- Lead/Introduction --}}
                @if($post->lead)
                    <div class="mb-12">
                        <div class="text-xl text-zinc-700 leading-relaxed">
                            {{ $post->lead }}
                        </div>
                    </div>
                @endif

                {{-- Main Content --}}
                <div class="mb-12 wp-class">
                    {!! $post->description !!}
                </div>

                {{-- Tags Section --}}
                @if($post->tags->count() > 0)
                    <div class="border-t border-zinc-200 pt-8 mb-12">
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="text-sm font-medium text-zinc-600">{{__('blog.tags')}}:</span>
                            @foreach($post->tags as $tag)
                                <a href="{{ route('blog') }}?tag={{ $tag->tag }}"
                                   class="px-4 py-2 border border-zinc-300 rounded-full text-sm text-zinc-700 hover:bg-zinc-100 hover:no-underline transition">
                                    {{ $tag->tag }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </article>
        </div>
        {{-- Newsletter Box --}}
        @include('sites.blog.partials.newsletter_box')


        {{-- Related Posts --}}
        <div class="max-w-7xl w-full mx-auto mb-[60px]">
            @if($relatedPosts->count() > 0)
                <div class="mt-16">
                    <h5 class="text-[32px] mb-8">{{__('blog.related_posts')}}</h5>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedPosts as $relatedPost)
                            <article class="bg-white overflow-hidden ">
                                @if($relatedPost->featured_image)
                                    <img src="{{ Storage::url($relatedPost->featured_image) }}" alt="{{ $relatedPost->title }}" 
                                    class="w-full aspect-3/2 object-cover rounded-xl">
                                @else
                                    <div class="w-full aspect-3/2 object-cover bg-linear-to-br from-purple-100 to-blue-100 rounded-xl"></div>
                                @endif

                                <div class="flex flex-col gap-2 py-6">
                                    @if($relatedPost->category)
                                        <div class="text-body-regular-s">
                                            {{ $relatedPost->category->category }}
                                        </div>
                                    @endif

                                    <h3 class="text-body-medium-l text-[20px] line-clamp-2">
                                        <a 
                                            href="{{ route('blog.show', ['slug' => $relatedPost->category?->slug ?? 'uncategorized', 'code' => $relatedPost->code, 'postSlug' => $relatedPost->slug]) }}" 
                                            class="hover:underline-offset-4 hover:underline"
                                            >
                                            {{ $relatedPost->title }}
                                        </a>
                                    </h3>

                                    @if($relatedPost->lead)
                                        <p class="text-body-regular-s line-clamp-3">{{ $relatedPost->lead }}</p>
                                    @endif
                                    <div class="my-4">
                                        <a href="{{ route('blog.show', ['slug' => $relatedPost->category?->slug ?? 'uncategorized', 'code' => $relatedPost->code, 'postSlug' => $relatedPost->slug]) }}" 
                                            class="inline p-2 px-4 border text-body-regular-s border-gray-300 rounded-full hover:no-underline hover:bg-gray-100 w-auto" 
                                            title="{{ $relatedPost->title }}"
                                            >
                                            {{__('blog.see_more')}}
                                        </a>
                                    </div>

                                    
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>


    </div>

</x-layouts.site>