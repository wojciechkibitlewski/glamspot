<x-layouts.site :title="__('Podsumowanie i płatność')">
    <div class="w-full">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 mb-8 py-8 min-h-[600px]">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-zinc-200">
                <div class="space-y-1">
                    <flux:heading size="xl">Moje ogłoszenia. Podsumowanie i płatność</flux:heading>
                </div>
            </div>

            <div class="flex items-start gap-8">
                @include('sites.user-accounts.partials.nav')

                <section class="w-full rounded-2xl border border-zinc-200 bg-gray-50 p-6">
                    <div class="flex items-center justify-between pb-4 mb-4 border-b border-zinc-200">
                        <h2 class="text-xl font-medium">Podsumowanie i płatność</h2>
                        <flux:button href="{{ route('user-ads.edit', [$ad->code, $ad->slug]) }}" variant="filled" class="cursor-pointer">Wróć do edycji</flux:button>
                    </div>

                    <div class="grid gap-6 ">
                        
                        @if ($errors->has('payment'))
                            <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                                {{ $errors->first('payment') }}
                            </div>
                        @endif

                        <div class="flex items-start justify-between gap-4 rounded-lg bg-white border border-zinc-200 p-4">
                            <div class="flex ">
                                <div class="w-24 h-16 mr-3">
                                    @php $firstPhoto = $ad->photos->first(); @endphp
                                    @if($firstPhoto)
                                        <img src="{{ asset('storage/'.$firstPhoto->photo) }}" alt="{{ $ad->title }}" class="w-24 h-16 object-cover rounded-md border" />
                                    @endif
                                </div>
                                <div>
                                    <div class="font-medium text-zinc-800">{{ $ad->title }}</div>
                                    <div class="flex gap-2 my-2">
                                        <flux:badge color="zinc" class="text-body-regular-xs !font-normal">{{ $ad->category?->name }}</flux:badge>
                                        <flux:badge color="zinc" class="text-body-regular-xs !font-normal">{{ $ad->location ?? 'Brak lokalizacji' }}</flux:badge>
                                        @php
                                            $statusMap = [
                                                'pending_payment' => 'Oczekuje na platnosc',
                                                'active' => 'Aktywne',
                                                'in_review' => 'Weryfikacja',
                                                'expired' => 'Wygaslo',
                                            ];
                                            $statusLabel = $statusMap[$ad->status] ?? ucfirst($ad->status);
                                        @endphp
                                        @if($ad->status === 'pending_payment')
                                            <flux:badge color="fuchsia" class="text-body-regular-xs !font-normal">{{ $statusLabel }}</flux:badge>
                                        @else
                                            <flux:badge color="lime" class="text-body-regular-xs !font-normal">{{ $statusLabel }}</flux:badge>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">

                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <div class="text-body-medium-s text-zinc-500">Czas emisji ogłoszenia</div>
                            <div class="">Twoje ogłoszenie będzie ważne przez 30 dni. W każdej chwili możesz je przedłużyć.</div>
                        </div>

                        

                        <form method="POST" action="{{ route('orders.store') }}" class="mt-2">
                            @csrf
                            <input type="hidden" name="ad_code" value="{{ $ad->code }}" />
                            {{-- wybierz pakiet --}}
                            <div class="mb-6">
                                <h3 class="text-lg font-medium mb-4 text-zinc-800">Wybierz pakiet</h3>
                                <div class="grid gap-4">
                                    <label class="flex items-center justify-between p-4 rounded-lg border-2 border-zinc-200 bg-white hover:border-[#BA75EC] cursor-pointer transition">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="package" value="single" class="w-5 h-5 text-[#BA75EC] focus:ring-[#BA75EC]" checked />
                                            <div>
                                                <div class="font-medium text-zinc-800">Płatność jednorazowa</div>
                                                <div class="text-sm text-zinc-500">1 ogłoszenie</div>
                                            </div>
                                        </div>
                                        <div class="text-xl font-bold text-zinc-800">25 zł</div>
                                    </label>

                                    <label class="flex items-center justify-between p-4 rounded-lg border-2 border-zinc-200 bg-white hover:border-[#BA75EC] cursor-pointer transition">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="package" value="package_5" class="w-5 h-5 text-[#BA75EC] focus:ring-[#BA75EC]" />
                                            <div>
                                                <div class="font-medium text-zinc-800">Pakiet 5 ogłoszeń</div>
                                                <div class="text-sm text-zinc-500">Kup pakiet 5 ogłoszeń i zapłać tylko 20 zł za jedno ogłoszenie.</div>
                                            </div>
                                        </div>
                                        <div class="text-xl font-bold text-zinc-800">100 zł</div>
                                    </label>

                                    <label class="flex items-center justify-between p-4 rounded-lg border-2 border-zinc-200 bg-white hover:border-[#BA75EC] cursor-pointer transition">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="package" value="package_12" class="w-5 h-5 text-[#BA75EC] focus:ring-[#BA75EC]" />
                                            <div>
                                                <div class="font-medium text-zinc-800">Pakiet 10 ogłoszeń <flux:badge color="lime">Najczęściej wybierany</flux:badge></div>
                                                <div class="text-sm text-zinc-500">Kup pakiet 10 ogłoszeń i zapłać 18 zł za jedno ogłoszenie.</div>
                                            </div>
                                        </div>
                                        <div class="text-xl font-bold text-zinc-800">180 zł</div>
                                    </label>

                                    <label class="flex items-center justify-between p-4 rounded-lg border-2 border-zinc-200 bg-white hover:border-[#BA75EC] cursor-pointer transition">
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="package" value="package_26" class="w-5 h-5 text-[#BA75EC] focus:ring-[#BA75EC]" />
                                            <div>
                                                <div class="font-medium text-zinc-800">Pakiet 20 ogłoszeń</div>
                                                <div class="text-sm text-zinc-500">Kup pakiet 20 ogłoszeń i zapłać 15 zł za jedno ogłoszenie.</div>
                                            </div>
                                        </div>
                                        <div class="text-xl font-bold text-zinc-800">300 zł</div>
                                    </label>
                                </div>
                            </div>
                            
                            {{-- dane do faktury --}}
                            @php $firm = auth()->user()->firm; @endphp
                            <div class="my-6">
                                <h3 class="text-lg font-medium mb-4 text-zinc-800">Dane do faktury</h3>
                                <div class="grid gap-4">
                                    <div>
                                        <label for="firm_name" class="block text-sm font-medium text-zinc-700 mb-1">Nazwa firmy</label>
                                        <input type="text"
                                               id="firm_name"
                                               name="firm_name"
                                               value="{{ old('firm_name', $firm?->firm_name) }}"
                                               class="w-full px-4 py-2 rounded-lg border border-zinc-300 focus:border-[#BA75EC] focus:ring-1 focus:ring-[#BA75EC] outline-none transition"
                                               required />
                                    </div>

                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="firm_city" class="block text-sm font-medium text-zinc-700 mb-1">Miasto</label>
                                            <input type="text"
                                                   id="firm_city"
                                                   name="firm_city"
                                                   value="{{ old('firm_city', $firm?->firm_city) }}"
                                                   class="w-full px-4 py-2 rounded-lg border border-zinc-300 focus:border-[#BA75EC] focus:ring-1 focus:ring-[#BA75EC] outline-none transition"
                                                   required />
                                        </div>

                                        <div>
                                            <label for="firm_postalcode" class="block text-sm font-medium text-zinc-700 mb-1">Kod pocztowy</label>
                                            <input type="text"
                                                   id="firm_postalcode"
                                                   name="firm_postalcode"
                                                   value="{{ old('firm_postalcode', $firm?->firm_postalcode) }}"
                                                   placeholder="00-000"
                                                   class="w-full px-4 py-2 rounded-lg border border-zinc-300 focus:border-[#BA75EC] focus:ring-1 focus:ring-[#BA75EC] outline-none transition"
                                                   required />
                                        </div>
                                    </div>

                                    <div>
                                        <label for="firm_address" class="block text-sm font-medium text-zinc-700 mb-1">Adres</label>
                                        <input type="text"
                                               id="firm_address"
                                               name="firm_address"
                                               value="{{ old('firm_address', $firm?->firm_address) }}"
                                               placeholder="ul. Przykładowa 1/2"
                                               class="w-full px-4 py-2 rounded-lg border border-zinc-300 focus:border-[#BA75EC] focus:ring-1 focus:ring-[#BA75EC] outline-none transition"
                                               required />
                                    </div>

                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="firm_nip" class="block text-sm font-medium text-zinc-700 mb-1">NIP</label>
                                            <input type="text"
                                                id="firm_nip"
                                                name="firm_nip"
                                                value="{{ old('firm_nip', $firm?->firm_nip) }}"
                                                placeholder="0000000000"
                                                class="w-full px-4 py-2 rounded-lg border border-zinc-300 focus:border-[#BA75EC] focus:ring-1 focus:ring-[#BA75EC] outline-none transition"
                                                required />
                                        </div>

                                        <div>
                                            <label for="firm_email" class="block text-sm font-medium text-zinc-700 mb-1">E-mail firmowy</label>
                                            <input type="email"
                                                id="firm_email"
                                                name="firm_email"
                                                value="{{ old('firm_email', $firm?->firm_email) }}"
                                                placeholder="firma@example.com"
                                                class="w-full px-4 py-2 rounded-lg border border-zinc-300 focus:border-[#BA75EC] focus:ring-1 focus:ring-[#BA75EC] outline-none transition"
                                                required />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="px-8 py-4 rounded-full bg-linear-to-r from-[#BA75EC] to-[#1FC2D7] !text-white font-medium hover:opacity-90 transition cursor-pointer">
                                Zapłać teraz z Przelewy24.pl
                            </button>
                        </form>
                    </div>

                </section>
            </div>
        </div>
    </div>
</x-layouts.site>
