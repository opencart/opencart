import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Storage
const countries = await loader.storage('localisation/country');

/**
 * XCountry
 *
 * @example <x-country name="" value="" target="" input-id=""></x-country>
 *
 * @tag     x-country
 *
 * @attr   string    name   name of the form element
 *
 * optional required disabled
 */
class XCountry extends WebComponent {
    static observed = ['value'];

    default = HTMLInputElement;
    countries = [];
    target = '';
    language = config.config_language;

    get value() {
        return this.getAttribute('value');
    }

    set value(value) {
        this.setAttribute('value', value);
    }

    async connected() {
        this.default = this.innerHTML;
        this.countries = countries;
        this.target = this.hasAttribute('target') ? document.getElementById(this.getAttribute('target')) : '';
    }

    async render() {
        let html = '<select name="' + this.getAttribute('name') + '" id="' + this.getAttribute('input-id') + '" data-on="change:onChange" class="form-select"';

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

            if (this.language in country.description) {
                name = country.description[this.language].name;
            }

            html += '>' + name + '</option>';
        }

        html += '</select>';

        if (this.target) {
            let postcode = (this.value in this.countries[this.value]) ? this.countries[this.value].postcode_required : 0;

            if (postcode == 1) {
                this.target.setAttribute('required', '');
            } else {
                this.target.removeAttribute('required');
            }
        }

        return html;
    }

    onChange(e) {
        this.value = e.target.value;
    }
}

customElements.define('x-country', XCountry);