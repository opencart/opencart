import { WebComponent } from './../webcomponent.js';

class XCountry extends WebComponent {
    element = HTMLInputElement;
    postcode = HTMLInputElement;
    countries = [];
    event = {
        connected: async () => {
            // I think for simple elements we can get without using a template system
            this.innerHTML = '<select name="' + this.getAttribute('name') + '" id="' + this.getAttribute('input-id') + '" class="' + this.getAttribute('input-class') + '" required>' + this.innerHTML + '</select>';

            this.element = this.querySelector('select');

            let value= this.getAttribute('value');

            let html = '';

            let countries = await this.load.data('localisation/country.' + this.getAttribute('language'));

            for (let i in countries) {
                html += '<option value="' + countries[i].country_id + '"';

                if (countries[i].country_id == value) {
                    html += ' selected';
                }

                html += '>' + countries[i].name + '</option>';
            }

            this.element.innerHTML = html;

            this.element.addEventListener('change', this.event.onchange);

            // If attribute postcode exists
            if (this.hasAttribute('postcode')) {
                this.setPostcode(document.querySelector(this.getAttribute('postcode')));
            }
        },
        setPostcode: () => {
            if (this.hasAttribute('postcode')) {
                let element = document.querySelector(this.getAttribute('postcode'));

                if (this.countries[e.target.value] && this.countries[e.target.value].postcode_required == 1) {
                    element.setAttribute('required', '');
                } else {
                    element.removeAttribute('required');
                }
            }
        },
        onchange: async (e) => {
            this.setAttribute('value', e.target.value);

            if (this.hasAttribute('postcode')) {
                let element = document.querySelector(this.getAttribute('postcode'));

                if (this.countries[e.target.value] && this.countries[e.target.value].postcode_required == 1) {
                    element.setAttribute('required', '');
                } else {
                    element.removeAttribute('required');
                }
            }
        }
    };
}

customElements.define('x-country', XCountry);