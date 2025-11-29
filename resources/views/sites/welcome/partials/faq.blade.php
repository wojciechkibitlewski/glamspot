<div class="w-full flex items-center justify-center mt-[100px] md:mt-[300px]">
    <div class="flex flex-col-reverse md:flex-row gap-4 w-full max-w-7xl mx-auto px-6 lg:px-8 ">
        <div class="relative w-full min-h-[100px]">
            <img src="{{ asset('storage/container.png') }}" alt="" class="w-full md:absolute b-0 md:-mt-[200px] md:px-0" />
        </div>
        <div class="w-full">
            <h4>FAQ</h4>
            <div class="text-body-regular-m py-6">
                Masz pytania dotyczące platformy GlamSpot? Poniżej znajdziesz odpowiedzi na najczęściej zadawane pytania naszych użytkowników.
            </div>
        </div>
    </div>
</div>
<div class="w-full flex items-center justify-center bg-linear-to-l from-[#E446B4] to-[#6A80CE] mb-8 -mt-[200px] md:mt-0">
    <div class="flex flex-col md:flex-row gap-4 w-full max-w-7xl mx-auto px-6 lg:px-8">
        <div class="w-full">

        </div>
        <div class="flex flex-col justify-end w-full text-white py-8 mt-[200px] md:mt-0">
            <div class="collapse collapse-arrow border-b border-zinc-100 bg-transparent! rounded-none">
                <input type="radio" name="my-accordion-2" checked="checked" />
                <div class="collapse-title font-semibold">1. Czym jest GlamSpot?</div>
                <div class="collapse-content text-sm">
                    GlamSpot to innowacyjna platforma marketplace dla branży beauty, która łączy producentów, dystrybutorów i właścicieli salonów kosmetycznych. Umożliwia wynajem, zakup lub sprzedaż nowoczesnych urządzeń kosmetycznych HITECH w jednym miejscu, a także znalezienie pracownika czy interesujących szkoleń.
                </div>
            </div>
            <div class="collapse collapse-arrow border-b border-zinc-100 bg-transparent! rounded-none">
                <input type="radio" name="my-accordion-2" />
                <div class="collapse-title font-semibold">2. Dla kogo przeznaczony jest GlamSpot?</div>
                <div class="collapse-content text-sm">
                    Platforma GlamSpot skierowana jest do właścicieli gabinetów kosmetycznych, klinik medycyny estetycznej, salonów beauty, fryzjerskich, gabinetów fizjo a także osób, które planują otworzyć własny biznes w branży beauty i poszukują nowoczesnych technologii, szkoleń lub pracowników.
                </div>
            </div>
            <div class="collapse collapse-arrow border-b border-zinc-100 bg-transparent! rounded-none">
                <input type="radio" name="my-accordion-2" />
                <div class="collapse-title font-semibold">3. Jakie urządzenia kosmetyczne można znaleźć na GlamSpot?</div>
                <div class="collapse-content text-sm">
                    Na GlamSpot  znajdziesz szeroką ofertę urządzeń zarówno nowych jak i używanych HITECH, m.in. lasery frakcyjne, lasery CO₂, lasery do depilacji, radiofrekwencję mikroigłową, urządzenia do modelowania sylwetki, kriolipolizy czy endermologii, a także akcesoria niezbędne w każdym salonie fryzjerskim czy SPA. 
                </div>
            </div>
            <div class="collapse collapse-arrow border-b border-zinc-100 bg-transparent! rounded-none">
                <input type="radio" name="my-accordion-2" />
                <div class="collapse-title font-semibold">4. Czy mogę kupić nowe urządzenie kosmetyczne bezpośrednio przez GlamSpot?</div>
                <div class="collapse-content text-sm">
                    Nie, platforma GlamSpot nie prowadzi bezpośredniej sprzedaży urządzeń.<br/>
                    Naszym celem jest umożliwienie producentom, dystrybutorom i sprzedawcom wystawiania własnych ofert sprzedaży lub wynajmu sprzętu kosmetycznego.<br/>
                    Każda oferta zawiera zdjęcia, opisy, parametry techniczne oraz dane kontaktowe, dzięki czemu zainteresowany klient może skontaktować się bezpośrednio z wystawcą i dokonać transakcji poza platformą. <br/>
                </div>
            </div>
            
            <div class="my-4">
                <a href="{{ route('faq') }}" class="flex flex-row gap-2 items-center text-white underline-offset-2">
                    Czytaj więcej
                    <flux:icon.arrow-long-right /> 
                </a>
                
            </div>
            
        </div>
    </div>
</div>