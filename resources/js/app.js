document.addEventListener('alpine:init', () => {
    // Komponent citySearch do wyszukiwania miast
    Alpine.data('citySearch', (initialValue = '') => ({
        value: initialValue,
        open: false,
        suggestions: [],

        init() {
            this.value = initialValue;
        },

        async onInput() {
            const query = this.value.trim();

            if (query.length < 2) {
                this.suggestions = [];
                this.open = false;
                return;
            }

            try {
                const response = await fetch(`/api/cities?q=${encodeURIComponent(query)}`);
                const data = await response.json();
                this.suggestions = data.data || [];
                this.open = this.suggestions.length > 0;
            } catch (error) {
                console.error('Error fetching cities:', error);
                this.suggestions = [];
                this.open = false;
            }
        },

        select(cityName) {
            this.value = cityName;
            this.open = false;
            this.suggestions = [];
        }
    }));

    // 2. Uruchom Flux
    if (window.Flux?.boot) {
        Flux.boot();
    }
});

// document.addEventListener('alpine:init', () => {
//     // Bezpieczne bootowanie Flux (jeśli używasz Flux.boot)
//     if (window.Flux?.boot) {
//         Flux.boot();
//     }

//     if (window.Livewire) {
//         window.Livewire.start({
//             navigate: true,
//         });
//     }
// });

function openAdModalIfRequested() {
    try {
        const params = new URLSearchParams(window.location.search);
        if (params.get('start_ad') === '1') {
            document.dispatchEvent(new CustomEvent('modal-show', { detail: { name: 'add-ad' }, bubbles: true }));
        }
    } catch (e) {}
}

document.addEventListener('DOMContentLoaded', () => {
    setTimeout(openAdModalIfRequested, 0);
});

document.addEventListener('livewire:navigated', openAdModalIfRequested);
