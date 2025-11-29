import { WebComponent } from './../library/webcomponent.js';

class XCurrency extends WebComponent {
    static observed = ['code', 'amount', 'value'];
    currencies = [];

    get code() {
        return this.getAttribute('code');
    }

    set code(code) {
        this.setAttribute('code', code);
    }

    get amount() {
        return parseFloat(this.getAttribute('amount'));
    }

    set amount(amount) {
        this.setAttribute('amount', amount);
    }

    get symbol_left() {
        if (this.currencies[this.code]) {
            return this.currencies[this.code]['symbol_left'];
        } else {
            return '';
        }
    }

    get symbol_right() {
        if (this.currencies[this.code]) {
            return this.currencies[this.code]['symbol_right'];
        } else {
            return '';
        }
    }

    get decimal_place() {
        if (this.currencies[this.code]) {
            return this.currencies[this.code]['decimal_place'];
        } else {
            return 2;
        }
    }

    get value() {
        if (this.hasAttribute('value')) {
            return parseFloat(this.getAttribute('value')).toFixed(this.decimal_place);
        }

        if (this.currencies[this.code]) {
            return this.currencies[this.code]['value'];
        } else {
            return 1.00000;
        }
    }

    set value(value) {
        this.setAttribute('value', value);
    }

    event = {
        connected: async () => {
            this.addEventListener('[code]', this.event.format);
            this.addEventListener('[amount]', this.event.format);
            this.addEventListener('[value]', this.event.format);

            let response = this.storage.fetch('localisation/currency');

            response.then(this.event.onloaded);
            response.then(this.event.format);
        },
        onloaded: (currencies) => {
            this.currencies = currencies;
        },
        format: () => {
            this.innerHTML = this.currency.format(this.value, this.code);
        }
    };
}

customElements.define('x-currency', XCurrency);