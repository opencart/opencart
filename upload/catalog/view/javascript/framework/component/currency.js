import { WebComponent } from './../webcomponent.js';

class XCurrency extends WebComponent {
    currencies = [];

    get code() {
        return this.getAttribute('code');
    }

    set code(code) {
        this.setAttribute('code', code);
    }

    get amount() {
        return this.getAttribute('code');
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
            return this.getAttribute('value');
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

    get decimal_point() {
        if (this.currencies[this.code]) {
            return this.currencies[this.code]['decimal_point'];
        } else {
            return '.';
        }
    }

    get thousand_point() {
        if (this.currencies[this.code]) {
            return this.currencies[this.code]['thousand_point'];
        } else {
            return ',';
        }
    }

    event = {
        connected: async () => {
            this.addEventListener('[code]', this.event.format);

            let response = this.storage.fetch('localisation/currency');

            response.then(this.event.onloaded);
            response.then(this.event.format);
        },
        onloaded: (currencies) => {
            this.currencies = currencies;
        },
        onchange: (e) => {
            this.value = e.target.value;
        },
        format: () => {
            let code = this.hasAttribute('value');
            let amount = this.hasAttribute('amount');

            if (number == null || !isFinite(number)) {
                throw new TypeError("number is not valid");
            }

            let amount = parseFloat(amount).toFixed(decimals);

            amount = this.value ? number * value : number;

            amount = round(amount, $decimal_place);

            let string = '';

            if (this.symbol_left) {
                string += this.symbol_left;
            }

            string += number_format(amount, this.decimal_place, this.decimal_point, this.thousand_point);

            if (symbol_right) {
                string += symbol_right;
            }

            this.innerHTML = '';
        }
    };
}

customElements.define('x-currency', XCurrency);

