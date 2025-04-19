import { WebComponent } from './../webcomponent.js';

class XCountry extends WebComponent {
    static observed = ['postcode-required'];
    default = HTMLInputElement;
    element = HTMLInputElement;
    countries = [];

    event = {
        connected: async () => {
            this.default = this.innerHTML;

            // I think for simple elements we can get without using a template system
            this.innerHTML = '<select name="' + this.getAttribute('name') + '" id="' + this.getAttribute('input-id') + '" class="' + this.getAttribute('input-class') + '"' + (this.hasAttribute('required') ? ' required' : '') + '>' + this.default + '</select>';

            this.element = this.querySelector('select');

            this.element.addEventListener('change', this.event.onchange);

            let response = this.storage.fetch('localisation/country');

            response.then(this.event.onloaded);
        },
        onloaded: (countries) => {
            let html = this.default;

            this.countries = countries;

            for (let i in this.countries) {
                html += '<option value="' + this.countries[i].country_id + '"';

                if (this.countries[i].country_id == this.getAttribute('value')) {
                    html += ' selected';
                }

                html += '>' + this.countries[i].name + '</option>';
            }

            this.element.innerHTML = html;

            // Checks to see if the selected country option requires a postcode
            if (this.hasAttribute('postcode')) {
                this.setAttribute('postcode-required', this.countries[this.getAttribute('value')].postcode_required);
            }
        },
        onchange: (e) => {
            this.setAttribute('value', e.target.value);

            if (this.hasAttribute('postcode')) {
                this.setAttribute('postcode-required', this.countries[e.target.value].postcode_required);
            }
        },
        changed: async (name, value_old, value_new) => {
            if (name == 'postcode-required') {
                let element = document.querySelector(this.getAttribute('postcode'));

                if (value_new == 1) {
                    element.setAttribute('required', '');
                } else {
                    element.removeAttribute('required');
                }
            }
        }
    };
}

customElements.define('x-country', XCountry);