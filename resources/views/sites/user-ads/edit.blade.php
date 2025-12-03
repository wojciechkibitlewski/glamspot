<x-layouts.site 
:title="__('Edycja ogłoszenia')"
:description="__('Edytuj swoje ogłoszenie na naszej platformie.')"
>

    <div class="w-full">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 mb-8 py-8 min-h-[600px]">
            {{-- header  --}}
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-zinc-200">
                <div class="space-y-1">
                    <flux:heading size="xl">Edytuj ogłoszenie</flux:heading>
                </div>
                <div class="flex gap-2">
                    <flux:modal.trigger name="delete-ad">
                        <flux:button variant="danger" class="cursor-pointer !text-white [&_*]:!text-white">Usuń ogłoszenie</flux:button>
                    </flux:modal.trigger>
                    <flux:button href="{{ route('user-ads.show', [$ad->code, $ad->slug]) }}" variant="filled" class="cursor-pointer">Podgląd</flux:button>
                </div>
            </div>

            <div class="flex items-start gap-8">
                @include('sites.user-accounts.partials.nav')

                <section class="w-full rounded-2xl border border-zinc-200 bg-gray-50 p-6" x-data='editAdForm({{ $ad->category_id }}, @json($subcategories), {{ $ad->as_company ? 'true' : 'false' }}, @json($trainingDates ?? []) , {{ (int) ($selectedSubcategoryId ?? 0) }})'>
                    {{-- errors  --}}
                    @if ($errors->any())
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- form  --}}
                    <form action="{{ route('user-ads.update', [$ad->code, $ad->slug]) }}" method="POST" class="grid gap-5">
                        @csrf
                        @method('PUT')

                        @if($ad->category->slug === 'praca')
                            @include('sites.user-ads.partials.form-work-edit')
                        @elseif($ad->category->slug === 'szkolenia')
                            @include('sites.user-ads.partials.form-courses-edit')
                        @elseif($ad->category->slug === 'urzadzenia-i-sprzet')
                            @include('sites.user-ads.partials.form-devices-edit')
                        @else
                           @include('sites.user-ads.partials.form-others-edit')
                        @endif

                        

                        <div class="mt-6">
                            <flux:heading size="md" class="mb-2">{{__('user-ads.photos')}}</flux:heading>
                            <livewire:ads.photos :existing="$ad->photos->pluck('photo')->all()" :adId="$ad->id" />
                        </div>

                        <div class="flex justify-between mt-2 items-center">
                            <flux:heading class="">Dane kontaktowe</flux:heading>
                            
                            <div class="flex items-center gap-4">
                                @if (auth()->user()?->firm)
                                <flux:label>Dodaj ogłoszenie jako firma</flux:label>
                                <flux:switch name="as_company" x-model="as_company" value="1" />
                                @else
                                <flux:modal.trigger name="add-firm">
                                    <button type="button" class="text-body-regular-m border border-gray-300 flex justify-center gap-3 px-4 py-2 rounded-full font-medium-l hover:no-underline hover:bg-grey-50 cursor-pointer">
                                        Chcesz dodać ogłoszenie jako firma?
                                        <flux:icon.plus variant="solid" />
                                    </button>
                                </flux:modal.trigger>
                                @endif
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg border border-gray-300">
                            @if (auth()->user()?->firm)
                                <div class="grid gap-4" x-show="as_company" x-cloak>
                                    @php $firm = auth()->user()->firm; @endphp
                                    @include('sites.user-ads.partials.form-contact-firm')
                                </div>
                            @endif

                            @php $hasFirm = (bool) auth()->user()?->firm; @endphp
                            @if ($hasFirm)
                                <div class="grid gap-4" x-show="!as_company" x-cloak>
                                    @include('sites.user-ads.partials.form-contact-user')
                                </div>
                            @else
                                <div class="grid gap-4">
                                    @include('sites.user-ads.partials.form-contact-user')
                                </div>
                            @endif
                        </div>

                        
                        <div class="flex items-center justify-end my-6">
                            <button type="submit" class="w-full px-8 py-4 rounded-full bg-linear-to-r from-[#BA75EC] to-[#1FC2D7] text-white font-medium text-body-medium-m hover:opacity-90 transition cursor-pointer">
                                Zapisz zmiany
                            </button>
                        </div>
                    </form>

                    <script>
                        function editAdForm(initialCategoryId, subs, initialAsCompany = false, initialTrainingDates = [], initialSubcategoryId = 0) {
                            return {
                                category_id: Number(initialCategoryId),
                                selectedSlug: null,
                                allSubcategories: subs || [],
                                as_company: Boolean(initialAsCompany),
                                trainingDates: initialTrainingDates || [],
                                subcategory_id: Number(initialSubcategoryId) || null,
                                selectedSubSlug: null,
                                onCategoryChange(e) {
                                    const select = e?.target ?? null;
                                    const option = select ? select.selectedOptions[0] : null;
                                    this.selectedSlug = option ? option.getAttribute('data-slug') : null;
                                    // Reset subcategory on change
                                    if (this.selectedSlug !== 'urzadzenia-i-sprzet') {
                                        this.subcategory_id = null;
                                        this.selectedSubSlug = null;
                                    } else if (!this.subcategory_id) {
                                        const def = (this.allSubcategories || []).find(s => s.slug === 'urzadzenia-nowe' && s.category_id === this.category_id);
                                        if (def) { this.subcategory_id = def.id; this.selectedSubSlug = def.slug; }
                                    }
                                },
                                get filteredSubcategories() {
                                    if (!this.category_id) return [];
                                    return this.allSubcategories.filter(s => s.category_id === this.category_id);
                                },
                                init() {
                                    const el = document.querySelector('select[name="category_id"]');
                                    if (el) this.onCategoryChange({ target: el });
                                    if (this.subcategory_id) {
                                        const sub = (this.allSubcategories || []).find(s => String(s.id) === String(this.subcategory_id));
                                        this.selectedSubSlug = sub ? sub.slug : null;
                                    }
                                    if (!this.subcategory_id && this.selectedSlug === 'urzadzenia-i-sprzet') {
                                        const def = (this.allSubcategories || []).find(s => s.slug === 'urzadzenia-nowe' && s.category_id === this.category_id);
                                        if (def) { this.subcategory_id = def.id; this.selectedSubSlug = def.slug; }
                                    }
                                    this.$watch('subcategory_id', (val) => {
                                        const sub = (this.allSubcategories || []).find((s) => String(s.id) === String(val));
                                        this.selectedSubSlug = sub ? sub.slug : null;
                                    });
                                }
                            }
                        }
                    </script>
                </section>
            </div>
        </div>
    </div>
</x-layouts.site>

{{-- modal - usuń ogłoszenie --}}
<flux:modal name="delete-ad" class="min-w-[22rem]">
    <div class="space-y-6 w-[360px]">
        <div class="space-y-2">
            <flux:heading size="lg">Usuń ogłoszenie</flux:heading>
            <p>Czy na pewno chcesz usunąc to ogłoszenie? Usuniesz ogłoszenie: <strong>{{ $ad->title }}</strong></p>
        </div>

        <div class="flex items-center justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost" class="cursor-pointer">Anuluj</flux:button>
            </flux:modal.close>
            <form action="{{ route('user-ads.destroy', [$ad->code, $ad->slug]) }}" method="POST">
                @csrf
                @method('DELETE')
                <flux:button type="submit" variant="danger" class="cursor-pointer !text-white [&_*]:!text-white">Usuń</flux:button>
            </form>
        </div>
    </div>
</flux:modal>

