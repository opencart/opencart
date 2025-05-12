import { WebComponent } from './../webcomponent.js';

class XCountry extends WebComponent {
    static observed = ['value', 'postcode'];
    default = HTMLInputElement;
    element = HTMLInputElement;
    countries = [];

    event = {
        connected: async () => {
            this.default = this.innerHTML;

            this.innerHTML = '<select name="' + this.getAttribute('name') + '" id="' + this.getAttribute('input-id') + '" class="' + this.getAttribute('input-class') + '"' + (this.hasAttribute('required') ? ' required' : '') + '>' + this.default + '</select>';

            this.addEventListener('attribute:value', this.event.changeValue);

            this.element = this.querySelector('select');

            this.element.addEventListener('change', this.event.onchange);

            let response = this.storage.fetch('localisation/country');

            response.then(this.event.onloaded).then(this.event.render);
        },
        onloaded: (countries) => {
            this.countries = countries;
        },
        render: () => {
            let html = this.default;

            for (let i in this.countries) {
                html += '<option value="' + this.countries[i].country_id + '"';

                if (this.countries[i].country_id == this.getAttribute('value')) {
                    html += ' selected';
                }

                html += '>' + this.countries[i].name + '</option>';
            }

            this.element.innerHTML = html;
        },
        onchange: (e) => {
           this.setAttribute('value', e.target.value);
        },
        changeValue: (e) => {
            let value = e.detail.value_new;

            if (this.element.value != value) {
                this.element.value = value;

                if (this.hasAttribute('target') && this.countries[value] !== undefined) {
                    let target = document.getElementById(this.getAttribute('target'));

                    if (this.countries[value] !== undefined) {
                        target.setAttribute('postcode', this.countries[value].postcode_required);
                    } else {
                        target.setAttribute('required', '');
                    }
                }
            }
        }
    };
}

customElements.define('x-country', XCountry);