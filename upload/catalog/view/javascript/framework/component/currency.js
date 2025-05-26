import { WebComponent } from './../webcomponent.js';

class XCurrency extends WebComponent {


    currencies = [];

    event = {
        connected: async () => {
            this.code = this.getAttribute('code');
            this.amount = this.getAttribute('amount');
            this.value = this.getAttribute('value');

            let response = this.storage.fetch('localisation/currency');

            response.then(this.event.onloaded);
            response.then(this.event.format);
        },
        onloaded: (currencies) => {
            this.currencies = currencies;
        },
        format: () => {
            if (this.currencies[this.code]) {
                let symbol_left = this.currencies[this.code]['symbol_left'];
                let symbol_right = this.currencies[this.code]['symbol_right'];
                let decimal_place = this.currencies[this.code]['decimal_place'];
                let value = this.currencies[this.code]['value'];

            } else {


            }

            if (!this.hasAttribute('value')) {
                let value = this.getAttribute('value');
            }

            $amount = value ? (float)$number * $value : (float)$number;

            $amount = round($amount, $decimal_place);

            $string = '';

            if ($symbol_left) {
                $string .= $symbol_left;
            }

            $string .= number_format($amount, $decimal_place, $this->language->get('decimal_point'), $this->language->get('thousand_point'));

            if ($symbol_right) {
                $string .= $symbol_right;
            }

            this.innerHTML = '';
        },
        onchange: (e) => {
            this.value = e.target.value;
        },
    };
}

customElements.define('x-currency', XCurrency);

