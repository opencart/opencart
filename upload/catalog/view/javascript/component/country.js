import {local, WebComponent} from '../index.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Storage
const countries = await loader.storage('localisation/country');

/**
 * inputCountry
 *
 * @example <input-country name="" value="" target="" input-id=""></input-country>
 *
 * @tag     input-country
 *
 * @attr   string   name   name of the form element
 *
 * optional required disabled
 */
customElements.define('input-country', class extends WebComponent {
    static observed = ['value'];

    default = HTMLInputElement;
    countries = [];
    target = '';

    get value() {
        return this.getAttribute('value');
    }

    set value(value) {
        this.setAttribute('value', value);
    }

    async render() {
        let html = '<select name="' + this.getAttribute('name') + '" id="' + this.getAttribute('input-id') + '" @change="onChange" class="form-select"';

        if (this.hasAttribute('required')) {
            html += ' required';
        }

        if (this.hasAttribute('disabled')) {
            html += ' disabled';
        }

        html += '>' + this.default;

        for (let country of this.countries) {
            html += '<option value="' + country.country_id + '"';

            if (country.country_id == this.value) {
                html += ' selected';
            }

            let name = '';

            if (local.get('language') in country.description) {
                name = country.description[local.get('language')].name;
            }

            html += '>' + name + '</option>';
        }

        html += '</select>';

        if (this.postcode) {
            if (this.value in this.countries[this.value] && this.countries[this.value].postcode_required == 1) {
                this.postcode.setAttribute('required', '');
            } else {
                this.postcode.removeAttribute('required');
            }
        }

        return html;
    }

    async handleConnect() {
        this.default = this.innerHTML;
        this.countries = countries;
        this.target = this.hasAttribute('target') ? document.getElementById(this.getAttribute('target')) : '';
        this.postcode = this.hasAttribute('postcode') ? document.getElementById(this.getAttribute('postcode')) : '';
    }

    handleChange(e) {
        this.value = e.target.value;

        if (this.target) this.target.setAttribute('country_id', this.value);
    }
});