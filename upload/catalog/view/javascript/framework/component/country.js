import { WebComponent } from './../webcomponent.js';

class XCountry extends WebComponent {
    static observed = ['value', 'postcode'];
    default = HTMLInputElement;
    element = HTMLInputElement;
    countries = [];

    get value() {
        return this.getAttribute('value');
    }

    set value(value) {
        if (this.getAttribute('value') != value) {
            this.setAttribute('value', value);
        }

        if (this.element.value != value) {
            this.element.value = value;
        }
    }

    set postcode(value) {
        if (this.hasAttribute('target')) {
            this.setAttribute('postcode', value);

            let target = document.getElementById(this.getAttribute('target'));

            if (value == 1) {
                target.setAttribute('required', '');
            } else {
                target.removeAttribute('required');
            }
        }
    }

    get postcode() {
        return this.getAttribute('postcode');
    }

    event = {
        connected: async () => {
            this.default = this.innerHTML;

            this.innerHTML = '<select name="' + this.getAttribute('name') + '" id="' + this.getAttribute('input-id') + '" class="' + this.getAttribute('input-class') + '"' + (this.hasAttribute('required') ? ' required' : '') + '>' + this.default + '</select>';

            this.addEventListener('[value]', this.event.changeValue);

            this.element = this.querySelector('select');

            this.element.addEventListener('change', this.event.onchange);
            this.element.addEventListener('change', this.event.postcode);

            let response = this.storage.fetch('localisation/country');

            response.then(this.event.onloaded);
            response.then(this.event.option);
            response.then(this.event.postcode);
        },
        onloaded: (countries) => {
            this.countries = countries;
        },
        option: () => {
            let html = this.default;

            for (let i in this.countries) {
                html += '<option value="' + this.countries[i].country_id + '"';

                if (this.countries[i].country_id == this.value) {
                    html += ' selected';
                }

                html += '>' + this.countries[i].name + '</option>';
            }

            this.element.innerHTML = html;
        },
        postcode: () => {
            if (this.countries[this.value] !== undefined) {
                this.postcode = this.countries[this.value].postcode_required;
            } else {
                this.postcode = 0;
            }
        },
        onchange: (e) => {
            this.value = e.target.value;
        },
        changeValue: (e) => {
            this.value = e.detail.value_new;
        }
    };
}

customElements.define('x-country', XCountry);