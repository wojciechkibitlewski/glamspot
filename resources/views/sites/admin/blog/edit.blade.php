<x-layouts.administracja
:title="__('Edytuj artykuł - Panel Administratora')"
:description="__('')"
>
    <div class="min-h-screen">
        {{-- Header --}}
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-zinc-200">
            <div>
                <flux:heading size="xl">Edytuj artykuł</flux:heading>
                <flux:text class="mt-2 text-base">{{ $post->title }}</flux:text>
            </div>
            <flux:button href="{{ route('admin.blog') }}" variant="ghost" icon="arrow-left">
                Powrót do listy
            </flux:button>
        </div>

        {{-- Errors --}}
        @if ($errors->any())
            <x-mary-alert icon="o-exclamation-triangle" class="alert-error mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-mary-alert>
        @endif

        {{-- Form --}}
        <form action="{{ route('admin.blog.update', $post->id) }}" method="POST" class="space-y-6 max-w-3xl">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-lg border border-zinc-200 p-6">

                <div class="space-y-4">
                    <x-mary-input
                        label="Tytuł artykułu"
                        name="title"
                        value="{{ old('title', $post->title) }}"
                        required
                    />

                    <x-mary-select
                        label="Kategoria"
                        name="post_category_id"
                        :options="$categories"
                        option-value="id"
                        option-label="category"
                        :value="old('post_category_id', $post->post_category_id)"
                        required
                    />

                    <x-mary-textarea
                        label="Wstęp"
                        name="lead"
                        rows="6"
                        hint="Podsumowanie artykułu wyświetlane na liście"
                    >{{ old('lead', $post->lead) }}</x-mary-textarea>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Treść artykułu</label>
                        <div id="editor-container" class="min-h-[600px] border border-gray-300 rounded-md">
                            <textarea id="description" name="description" class="hidden">{{ old('description', $post->description) }}</textarea>
                        </div>
                    </div>

                </div>
            </div>

            <div class="bg-white rounded-lg border border-zinc-200 p-6">
                <flux:heading size="lg" class="mb-4">Zdjęcie wyróżniające</flux:heading>

                <livewire:blog.featured-image :existing="$post->featured_image" />
            </div>

            <div class="bg-white rounded-lg border border-zinc-200 p-6">
                <flux:heading size="lg" class="mb-4">Ustawienia SEO</flux:heading>

                <div class="space-y-4">
                    <x-mary-input
                        label="SEO Tytuł"
                        name="seo_title"
                        value="{{ old('seo_title', $post->seo_title) }}"
                        hint="Tytuł wyświetlany w wynikach wyszukiwania"
                    />

                    <x-mary-textarea
                        label="SEO Opis"
                        name="seo_description"
                        rows="2"
                        hint="Opis wyświetlany w wynikach wyszukiwania"
                    >{{ old('seo_description', $post->seo_description) }}</x-mary-textarea>

                    <x-mary-input
                        label="SEO Słowa kluczowe"
                        name="seo_keywords"
                        value="{{ old('seo_keywords', $post->seo_keywords) }}"
                        hint="Oddzielone przecinkami"
                    />
                </div>
            </div>

            <div class="bg-white rounded-lg border border-zinc-200 p-6">
                <flux:heading size="lg" class="mb-4">Publikacja</flux:heading>

                <div class="flex items-center gap-3">
                    <flux:switch name="is_published" value="1" :checked="old('is_published', $post->is_published)" />
                    <flux:label>Opublikuj artykuł</flux:label>
                </div>
                <flux:text class="mt-2 text-sm text-gray-500">
                    Jeśli zaznaczone, artykuł zostanie opublikowany i będzie widoczny dla użytkowników
                </flux:text>
            </div>

            <div class="flex justify-end gap-4">
                <flux:button href="{{ route('admin.blog') }}" variant="ghost">
                    Anuluj
                </flux:button>
                <flux:button type="submit" variant="primary">
                    Zapisz zmiany
                </flux:button>
            </div>
        </form>
    </div>
    @push('scripts')
        <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>
        <style>
            .ck-editor__editable {
                min-height: 600px !important;
            }
            .ck-content ul {
                list-style-type: disc !important;
                padding-left: 40px !important;
                margin: 1em 0 !important;
            }
            .ck-content ol {
                list-style-type: decimal !important;
                padding-left: 40px !important;
                margin: 1em 0 !important;
            }
            .ck-content ul li,
            .ck-content ol li {
                display: list-item !important;
                margin-bottom: 0.5em !important;
            }
        </style>
        <script>
            ClassicEditor
                .create(document.querySelector('#description'), {
                    minHeight: '600px'
                })
                .then(editor => {
                    editor.ui.view.editable.element.style.minHeight = '600px';
                })
                .catch(error => {
                    console.error(error);
                });
        </script>
    @endpush

</x-layouts.administracja>
