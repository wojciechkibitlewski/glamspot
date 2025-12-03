<x-layouts.site 
:title="__('seo.user-account.billings.title')"
:description="__('seo.user-account.billings.description')"

>
    <div class="w-full">
        
        <div class="max-w-7xl mx-auto px-6 lg:px-8 mb-8 py-8 min-h-[600px]">
            {{-- header --}}
            <div class="flex items-center justify-between  pb-4 mb-4 border-b border-zinc-200">
                <div class="space-y-1">
                    <flux:heading size="xl">{{__('user-account.billings_heading')}}</flux:heading>
                </div>
            </div>
            
            
            
            <div class="flex items-start gap-8">
                @include('sites.user-accounts.partials.nav')

                <section class="w-full rounded-2xl border border-zinc-200 bg-gray-50 p-6">
                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-zinc-200">
                        <h2 class="text-xl font-medium">Metoda płatności</h2>
                    </div>

                    <div class="flex flex-row w-full gap-6 mb-8">
                        <div class="flex flex-col w-full gap-4"> 
                            {{-- billing period  --}}
                            <div class="w-full">
                                <div class="flex flex-row w-full justify-between">
                                    <span class="block text-body-medium-s text-zinc-500 mb-2">Okres rozliczeniowy</span>
                                    <a href="#" class="text-sm underline text-right text-blue-600">Zmień</a>
                                </div>
                                
                                <div class="rounded-lg border border-zinc-200 p-4 bg-white">
                                    MIESIĘCZNY<br>
                                    <span class="text-xs text-zinc-500">Następne odnowienie: 4 sty 2026</span>
                                </div>
                            </div>
                            
                            {{-- payment method  --}}
                            <div class="w-full">
                                <div class="flex flex-row w-full justify-between">
                                    <span class="block text-body-medium-s text-zinc-500 mb-2">Metoda płatności</span>
                                    <a href="#" class="text-sm underline text-right text-blue-600">Zmień</a>
                                </div>
                                <div class="flex flex-row w-full rounded-lg border border-zinc-200 p-4 bg-white">
                                    <div class=""> 
                                        <img src="{{ asset('storage/mastercard-logo.svg') }}" alt="" class="w-[100px]" />
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-body-medium-s">**** **** **** 453</span>
                                        <span class="text-body-medium-s">Imie i nazwisko</span>
                                        <span class="text-body-regular-xs">Wygasa: 12/2026</span>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col w-full gap-4"> 
                            {{-- Payment details --}}
                            <div class="w-full">
                                <div class="flex flex-row w-full justify-between">
                                    <span class="block text-body-medium-s text-zinc-500 mb-2">Dane do płatności</span>
                                    <a href="#" class="text-sm underline text-right text-blue-600">Zmień</a>
                                </div>
                                <form method="POST" wire:submit="resetPassword" class="flex flex-col gap-6 my-6 w-full">
                                    @csrf
                                    <!-- Name -->
                                    <flux:input
                                        wire:model="name"
                                        :label="__('Imię i nazwisko / nazwa firmy')"
                                        type="text"
                                        required
                                        autocomplete="name"
                                        placeholder="Jan Kowalski"
                                    />
                                    
                                    <div class="flex flex-row w-full gap-4">
                                        <div class="w-full flex flex-col gap-4">
                                            <!-- city -->
                                            <flux:input
                                                wire:model="city"
                                                :label="__('Miasto')"
                                                type="text"
                                                required
                                                autocomplete="city"
                                                placeholder=""
                                            />
                                            
                                        </div>
                                        <div class="w-full flex flex-col gap-4">
                                            <!-- ZIP -->
                                            <flux:input
                                                wire:model="zip"
                                                :label="__('Kod pocztowy')"
                                                required
                                                type="text"
                                                autocomplete=""
                                                placeholder=""
                                            />

                                        </div>
                                    </div>
                                    <!-- Address -->
                                    <flux:input
                                        wire:model="address"
                                        :label="__('Ulica, nr posesji / lokalu')"
                                        type="text"
                                        required
                                        autocomplete="address"
                                        placeholder=""
                                    />
                                    <!-- NIP -->
                                    <flux:input
                                        wire:model="nip"
                                        :label="__('NIP')"
                                        type="text"
                                        autocomplete=""
                                        placeholder=""
                                    />
                                    <!-- Email -->
                                    <flux:input
                                        wire:model="email"
                                        :label="__('Adres e-mail')"
                                        type="email"
                                        required
                                        autocomplete="email"
                                        placeholder="email@example.com"
                                    />

                                    

                                    <div class="flex flex-row w-full gap-4">
                                        <div class="w-full">
                                            <flux:button type="submit" variant="primary" class="w-full cursor-pointer my-btn">
                                                {{ __('Zapisz dane') }}
                                            </flux:button>
                                        </div>
                                        <div class="w-full"></div>
                                    </div>
                                </form> 
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-zinc-200">
                        <h2 class="text-xl font-medium">Historia płatności</h2>
                    </div>
                    <div class="rounded-2xl border border-zinc-200 overflow-hidden">
                        <div class="px-4 py-3 bg-zinc-100 border-b border-zinc-200 font-medium">Twoje płatności</div>
                        <div class="p-4">
                            <div class="grid grid-cols-4 text-sm text-zinc-500 mb-2">
                                <div>Data</div><div>Nr faktury</div><div>Kwota</div><div>Faktura</div>
                            </div>
                            @for($i=0;$i<4;$i++)
                                <div class="grid grid-cols-4 py-2 border-t border-zinc-100 text-sm">
                                    <div>23.11.2025</div><div>X-543242</div><div>99,00 zł</div><div><a href="#" class="underline">pobierz</a></div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-layouts.site>

