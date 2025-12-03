<x-layouts.site 
:title="__('seo.user-ads.title')"
:description="__('seo.user-ads.description')"
>
    <div class="w-full">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 mb-8 py-8 min-h-[600px]">
             {{-- header --}}
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-zinc-200">
                <div class="space-y-1">
                    <flux:heading size="xl">{{__('user-ads.create.title')}}</flux:heading>
                </div>
            </div>

            <div class="flex items-start gap-8">
                @include('sites.user-accounts.partials.nav')
                
                <section class="w-full rounded-2xl border border-zinc-200 bg-gray-50 p-6" x-data='adForm()' x-on:firm-saved.window="window.location.reload()">
                    {{-- tytuł  --}}
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-zinc-200">
                        <h2 class="text-xl font-medium">{{__('user-ads.create.subtitle')}}</h2>
                        <flux:button href="{{ route('user-ads.index') }}" variant="filled" class="cursor-pointer">{{__('user-ads.ads_list')}}</flux:button>
                    </div>
                    {{-- błędy  --}}
                    @if ($errors->any())
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user-ads.store') }}" method="POST" class="grid gap-5">
                        @csrf
                        {{-- tytuł ogłoszenia  --}}
                        <div class="w-full">
                            <flux:field>
                                <flux:label class="font-semibold">{{__('user-ads.form.title')}} <span class="text-red-500">*</span></flux:label>
                                <flux:input name="title" value="{{ old('title') }}" />
                                <flux:error name="title" />
                            </flux:field>
                            
                        </div>
                        {{-- Kategoria  --}}
                        <div class="flex gap-4 w-full mb-2">
                            <div class="w-full">
                                <flux:field>
                                    <flux:label class="font-semibold ">{{__('user-ads.form.category')}} <span class="text-red-500">*</span></flux:label>
                                    <flux:select 
                                        name="category_id" 
                                        x-model.number="category_id" 
                                        @change="onCategoryChange"
                                        class="w-full rounded-md border border-zinc-300 p-2"
                                        >
                                        <option value="">{{__('user-ads.form.select_category')}}</option>
                                        @foreach($categories as $cat)
                                            <option 
                                                value="{{ $cat->id }}" 
                                                data-slug="{{ $cat->slug }}" 
                                                @selected(old('category_id') == $cat->id)
                                            >{{ $cat->name }}</option>
                                        @endforeach
                                    </flux:select>
                                </flux:field>
                            </div>
                            <div class="w-full">
                                <template x-if="currentCategorySlug === 'praca'">
                                    <div class="mb-2 w-full">
                                        <flux:field>
                                            <flux:label class="font-semibold ">{{__('user-ads.form.subcategory')}} <span class="text-red-500">*</span></flux:label>
                                                <flux:select name="subcategory_id" x-model.number="subcategory_id" 
                                                class="w-full rounded-md border border-zinc-300 p-2">
                                                <option value="">{{__('user-ads.form.select_subcategory')}}</option>
                                                    <template x-for="sub in filteredSubcategories" :key="sub.id">
                                                        <option :value="sub.id" :data-type-slug="sub.slug" x-text="sub.name"></option>
                                                    </template>
                                            </flux:select>
                                        </flux:field>
                                    </div>
                                </template>
                                
                                {{-- jeśli szkolenia --}}
                                <template x-if="currentCategorySlug === 'szkolenia'">
                                    <div class="mb-2 w-full">
                                        <flux:field>
                                            <flux:label class="font-semibold ">{{__('user-ads.form.course_type')}} <span class="text-red-500">*</span></flux:label>
                                                <flux:select name="subcategory_id" x-model.number="subcategory_id" 
                                                    class="w-full rounded-md border border-zinc-300 p-2">
                                                <option value="">{{__('user-ads.form.select_course_type')}}</option>
                                                    <template x-for="sub in filteredSubcategories" :key="sub.id">
                                                        <option :value="sub.id" :data-type-slug="sub.slug" x-text="sub.name"></option>
                                                    </template>
                                            </flux:select>
                                        </flux:field>
                                    </div>
                                </template>

                                {{-- jeśli urządzenia i sprzęt  --}}
                                <template x-if="currentCategorySlug === 'urzadzenia-i-sprzet'">
                                    <div class="mb-2 w-full">
                                            <flux:field>
                                            <flux:label class="font-semibold ">{{__('user-ads.form.subcategory')}} <span class="text-red-500">*</span></flux:label>
                                            <flux:select name="subcategory_id" x-model.number="subcategory_id" 
                                                class="w-full rounded-md border border-zinc-300 p-2">
                                                <option value="">{{__('user-ads.form.select_subcategory')}}</option>
                                                <template x-for="sub in filteredSubcategories" :key="sub.id">
                                                        <option :value="sub.id" :data-type-slug="sub.slug" x-text="sub.name"></option>
                                                    </template>
                                            </flux:select>
                                        </flux:field>                                               
                                    </div>
                                </template>

                                <template x-if="currentCategorySlug && currentCategorySlug !== 'praca' && currentCategorySlug !== 'szkolenia' && currentCategorySlug !== 'urzadzenia-i-sprzet'">
                                    Others
                                </template>
                            </div>
                        </div>
                        
                        <template x-if="selectedSlug === 'praca'">
                            @include('sites.user-ads.partials.form-work')
                        </template>
                        

                        <template x-if="selectedSlug === 'szkolenia'">
                             @include('sites.user-ads.partials.form-courses')
                        </template>

                        <template x-if="selectedSlug === 'urzadzenia-i-sprzet'">
                             @include('sites.user-ads.partials.form-devices')
                        </template>

                        <template x-if="selectedSlug && selectedSlug !== 'praca' && selectedSlug !== 'szkolenia' && selectedSlug !== 'urzadzenia-i-sprzet'">
                            @include('sites.user-ads.partials.form-others')
                        </template>

                        <div class="mt-6">
                            <flux:heading size="md" class="mb-2">{{__('user-ads.photos')}}</flux:heading>
                            <livewire:ads.photos />
                        </div>


                        <div class="flex justify-between mt-2 items-center">
                            <flux:heading class="">{{__('user-ads.contact_info')}}</flux:heading>
                            
                            <div class="flex items-center gap-4">
                                @if (auth()->user()?->firm)
                                <flux:label>{{__('user-ads.ad_as_firm')}}</flux:label>
                                <flux:switch name="as_company" x-model="as_company" value="1" />
                                @else
                                <flux:modal.trigger name="add-firm">
                                    <button type="button" class="text-body-regular-m border border-gray-300 flex justify-center gap-3 px-4 py-2 rounded-full font-medium-l hover:no-underline hover:bg-grey-50 cursor-pointer">
                                        {{__('user-ads.want_to_add_as_firm')}}
                                        <flux:icon.plus variant="solid" />
                                    </button>
                                </flux:modal.trigger>
                                @endif
                            </div>
                        </div>
                        
                        @php $hasFirm = (bool) auth()->user()?->firm; @endphp
                        
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
                            <button type="submit" class="w-full px-8 py-4 rounded-full bg-gradient-to-r from-[#BA75EC] to-[#1FC2D7] text-white font-medium text-body-medium-m hover:opacity-90 transition cursor-pointer">
                                {{ __('user-ads.save_and_continue') }}
                            </button>
                        </div>
                    </form>

                    <script>
                        function adForm() {
                            return {
                                title: @json(old('title')),
                                category_id: @json(old('category_id')),
                                // category_id: {{ old('category_id') ?? 'null' }},
                                subcategory_id: @json(old('subcategory_id')),
                                selectedSlug: null,
                                allSubcategories: @json($subcategories ?? []),
                                allCategories: @json($categories ?? []),
                                    as_company: @json((bool) old('as_company', false)),
                                    trainingDates: @json(old('training_dates', [])),
                                    // common fields for persistence
                                    location: @json(old('location')),
                                    is_online: @json((bool) old('is_online', false)),
                                    price: @json(old('price')),
                                    promo_price: @json(old('promo_price')),
                                    seats: @json(old('seats')),
                                    audience: @json(old('audience')),
                                    description: @json(old('description')),
                                    program: @json(old('program')),
                                    bonuses: @json(old('bonuses')),
                                    certificate: @json((bool) old('certificate', false)),
                                    signup_url: @json(old('signup_url')),
                                    training_specializations: @json(old('training_specializations', [])),
                                selectedSubSlug: null,
                                deviceForm: {
                                    type: 'new',
                                    availability: 'sale',
                                    condition: 'bardzo_dobry',
                                    allowLeasing: false,
                                    salePriceFrom: null,
                                    salePriceTo: null,
                                    rentalUnits: [],
                                    rentalPrices: {
                                        hour: null,
                                        day: null,
                                        week: null,
                                        month: null,
                                    },
                                    rentalTerms: '',
                                    depositRequired: false,
                                    contactNote: '',
                                },
                                init() {
                                    if (this.category_id && Array.isArray(this.allCategories)) {
                                        const cat = this.allCategories.find(c => String(c.id) === String(this.category_id));
                                        this.selectedSlug = cat ? cat.slug : null;
                                    }
                                    if (this.subcategory_id && Array.isArray(this.allSubcategories)) {
                                        const sub0 = this.allSubcategories.find(s => String(s.id) === String(this.subcategory_id));
                                        this.selectedSubSlug = sub0 ? sub0.slug : null;
                                    }
                                    // If devices category selected on load and no subcategory chosen, default to "urzadzenia-nowe"
                                    if (!this.subcategory_id && this.selectedSlug === 'urzadzenia-i-sprzet') {
                                        const def = (this.allSubcategories || []).find(s => s.slug === 'urzadzenia-nowe');
                                        if (def) {
                                            this.subcategory_id = def.id;
                                            this.selectedSubSlug = def.slug;
                                        }
                                    }
                                    this.$watch('subcategory_id', (val) => {
                                        const sub = this.allSubcategories.find((s) => String(s.id) === String(val));
                                        this.selectedSubSlug = sub ? sub.slug : null;
                                    });

                                    this.$watch('deviceForm.type', (value) => {
                                        if (value === 'rental') {
                                            this.deviceForm.availability = 'rent';
                                            this.deviceForm.salePriceFrom = null;
                                            this.deviceForm.salePriceTo = null;
                                        } else if (this.deviceForm.availability === 'rent') {
                                            this.deviceForm.availability = 'sale';
                                        }

                                        if (value !== 'rental') {
                                            this.resetRentalFields();
                                        }
                                    });
                                },
                                resetRentalFields() {
                                    this.deviceForm.rentalUnits = [];
                                    this.deviceForm.rentalPrices = {
                                        hour: null,
                                        day: null,
                                        week: null,
                                        month: null,
                                    };
                                    this.deviceForm.rentalTerms = '';
                                    this.deviceForm.depositRequired = false;
                                },
                                onCategoryChange(e) {
                                    const select = e?.target ?? null;
                                    const option = select ? select.selectedOptions[0] : null;
                                    this.selectedSlug = option ? option.getAttribute('data-slug') : null;
                                    // Reset subcategory selection when category changes
                                    this.subcategory_id = null;
                                    this.selectedSubSlug = null;
                                    // Default devices category to "urzadzenia-nowe"
                                    if (this.selectedSlug === 'urzadzenia-i-sprzet') {
                                        const def = (this.allSubcategories || []).find(s => s.slug === 'urzadzenia-nowe');
                                        if (def) {
                                            this.subcategory_id = def.id;
                                            this.selectedSubSlug = def.slug;
                                        }
                                    }
                                },
                                
                                get filteredSubcategories() {
                                    if (!this.category_id) return [];
                                    return this.allSubcategories.filter((s) => s.category_id === this.category_id);
                                },
                                get currentCategorySlug() {
                                    if (this.subcategory_id) {
                                        const sub = this.allSubcategories.find((s) => s.id === this.subcategory_id);
                                        if (sub) {
                                            const cat = (this.allCategories || []).find((c) => c.id === sub.category_id);
                                            if (cat && cat.slug) {
                                                return cat.slug;
                                            }
                                        }
                                    }
                                    return this.selectedSlug;
                                },
                            }
                        }
                    </script>
                </section>
            </div>
        </div>
    </div>
    {{-- Flyout modal: tworzenie firmy bez opuszczania strony --}}
    @if (!auth()->user()?->firm)
    <flux:modal name="add-firm" variant="flyout">
        <livewire:user.firm-form />
    </flux:modal>
    @endif
</x-layouts.site>
