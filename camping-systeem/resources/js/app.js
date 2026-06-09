import '../css/app.css';

// Minimal app entry for Vite
console.log('resources/js/app.js loaded');

const minPriceRange = document.querySelector('[data-price-min-range]');
const maxPriceRange = document.querySelector('[data-price-max-range]');
const minPriceOutput = document.querySelector('[data-price-min-output]');
const maxPriceOutput = document.querySelector('[data-price-max-output]');
const priceRangeTrack = document.querySelector('[data-price-range-track]');
const autoFilterForm = document.querySelector('[data-auto-filter-form]');

let autoFilterSubmitTimeout;

const submitAutoFilterForm = (delay = 350) => {
    if (!(autoFilterForm instanceof HTMLFormElement)) {
        return;
    }

    window.clearTimeout(autoFilterSubmitTimeout);

    autoFilterSubmitTimeout = window.setTimeout(() => {
        autoFilterForm.requestSubmit();
    }, delay);
};

if (
    minPriceRange instanceof HTMLInputElement
    && maxPriceRange instanceof HTMLInputElement
    && minPriceOutput instanceof HTMLOutputElement
    && maxPriceOutput instanceof HTMLOutputElement
    && priceRangeTrack instanceof HTMLElement
) {
    const formatter = new Intl.NumberFormat(document.documentElement.lang || 'nl', {
        style: 'currency',
        currency: 'EUR',
        maximumFractionDigits: 0,
    });

    const minimum = Number(minPriceRange.min);
    const maximum = Number(minPriceRange.max);

    const updatePriceRange = (changedRange) => {
        let minPrice = Number(minPriceRange.value);
        let maxPrice = Number(maxPriceRange.value);

        if (minPrice > maxPrice && changedRange === minPriceRange) {
            maxPrice = minPrice;
            maxPriceRange.value = String(maxPrice);
        }

        if (maxPrice < minPrice && changedRange === maxPriceRange) {
            minPrice = maxPrice;
            minPriceRange.value = String(minPrice);
        }

        minPriceOutput.value = formatter.format(minPrice);
        minPriceOutput.textContent = formatter.format(minPrice);
        maxPriceOutput.value = formatter.format(maxPrice);
        maxPriceOutput.textContent = formatter.format(maxPrice);

        const minPercent = ((minPrice - minimum) / (maximum - minimum)) * 100;
        const maxPercent = ((maxPrice - minimum) / (maximum - minimum)) * 100;

        priceRangeTrack.style.left = `${minPercent}%`;
        priceRangeTrack.style.right = `${100 - maxPercent}%`;

        if (minPrice === maxPrice) {
            minPriceRange.style.zIndex = changedRange === minPriceRange ? '30' : '20';
            maxPriceRange.style.zIndex = changedRange === maxPriceRange ? '30' : '20';
        } else {
            minPriceRange.style.zIndex = '20';
            maxPriceRange.style.zIndex = '30';
        }
    };

    minPriceRange.addEventListener('input', () => updatePriceRange(minPriceRange));
    maxPriceRange.addEventListener('input', () => updatePriceRange(maxPriceRange));
    minPriceRange.addEventListener('change', () => submitAutoFilterForm(150));
    maxPriceRange.addEventListener('change', () => submitAutoFilterForm(150));
    updatePriceRange(minPriceRange);
}

if (autoFilterForm instanceof HTMLFormElement) {
    autoFilterForm
        .querySelectorAll('input, select')
        .forEach((field) => {
            if (!(field instanceof HTMLInputElement || field instanceof HTMLSelectElement)) {
                return;
            }

            if (field.type === 'range') {
                return;
            }

            const eventName = field.type === 'number' || field.type === 'text'
                ? 'input'
                : 'change';

            field.addEventListener(eventName, () => submitAutoFilterForm());
        });
}
