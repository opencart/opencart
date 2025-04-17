import { WebComponent } from './../webcomponent.js';

class XCountry extends WebComponent {

    data = {
        postcode: '',
        countries: []
    };

    event = {
        connected: async () => {
            // I think for simple elements we can get without using a template system
            // Add the select element to the shadow DOM
            this.shadow.innerHTML = '<select name="' + this.getAttribute('name') + '" id="' + this.getAttribute('input-id') + '" class="' + this.getAttribute('input-class') + '" required>' + this.innerHTML + '</select>';

            this.addStylesheet('bootstrap.css');
            this.addStylesheet('fonts/fontawesome/css/fontawesome.css');

            this.shadow.addEventListener('change', this.event.onchange);

            // input-postcode
            if (this.hasAttribute('postcode')) {
                this.postcode = document.querySelector(this.getAttribute('postcode'));
            }

            let response = await fetch('./catalog/view/data/localisation/country.' + this.getAttribute('language') + '.json');

            response.json().then(this.event.onloaded);
        },
        onloaded: (countries) => {
            let html = this.innerHTML;
            let value= this.getAttribute('value');

            this.data.countries = countries;

            for (let i in countries) {
                html += '<option value="' + countries[i].country_id + '"';

                if (countries[i].country_id == value) {
                    html += ' selected';
                }

                html += '>' + countries[i].name + '</option>';
            }

            this.shadow.querySelector('select').innerHTML = html;

            // input-postcode
            if (this.hasAttribute('postcode')) {
                let element = document.querySelector(this.getAttribute('postcode'));

                if (this.data.countries[value] && this.data.countries[value].postcode_required == 1) {
                    this.postcode.setAttribute('required', '');
                } else {
                    this.postcode.removeAttribute('required');
                }
            }
        },
        onchange: async (e) => {
            this.setAttribute('value', e.target.value);

            if (this.hasAttribute('postcode')) {
                let element = document.querySelector(this.getAttribute('postcode'));

                if (this.data.countries[e.target.value] && this.data.countries[e.target.value].postcode_required == 1) {
                    element.setAttribute('required', '');
                } else {
                    element.removeAttribute('required');
                }
            }
        }
    };
}

customElements.define('x-country', XCountry);