import { WebComponent } from './../webcomponent.js';

class XCurrency extends WebComponent {
    static observed = ['format', 'value'];

    get format() {
        return this.getAttribute('format');
    }

    set format(format) {
        this.setAttribute('format', format);
    }

    get value() {
        return this.getAttribute('value');
    }

    set value(value) {
        this.setAttribute('value', value);
    }

    event = {
        connected: async () => {
            this.addEventListener('[format]', this.event.format);
            this.addEventListener('[value]', this.event.format);
        },
        format: () => {
            let string = '';

            if (this.symbol_left) {
                string += this.symbol_left;
            }

            let option = {
                style: 'currency',
                currency: this.code,
                currencyDisplay: 'symbol',
                currencySign: 'standard',
                minimumIntegerDigits: 1,
                minimumFractionDigits: this.decimal_place,
            };

            let formater = new Intl.NumberFormat(document.querySelector('html').lang, option);

            let part = formater.formatToParts(this.amount * this.value);

            let allowed = [
                'minusSign',
                'integer',
                'group',
                'decimal',
                'fraction',
                'literal'
            ];

            for (let i = 0; i < part.length; i++) {
                if (allowed.includes(part[i].type)) {
                    string += part[i].value;
                }
            }

            if (this.symbol_right) {
                string += this.symbol_right;
            }

            this.innerHTML = string;
        }
    };
}

customElements.define('x-currency', XCurrency);

