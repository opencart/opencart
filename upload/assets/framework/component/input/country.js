import { WebComponent } from '../../engine.js';
import { loader, local } from '../../library.js';

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
    static formAssociated = true;
    static observedAttributes = [
        'name',
        'value',
        'required',
        'disabled',
        'readonly'
    ];

    default = '';
    countries = [];
    target = '';

    get value() {
        return this.getAttribute('value');
    }

    set value(value) {
        this.setAttribute('value', value);
    }

    async render() {
        let html = '';
        
        for (let country of countries) {
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

        if (this.postcode) {
            if (this.value in this.countries[this.value] && this.countries[this.value].postcode_required == 1) {
                this.postcode.setAttribute('required', '');
            } else {
                this.postcode.removeAttribute('required');
            }
        }

        return ` 
        <label @ref="label" for="input">${this.label}</label>
        <select name="${this.name}" @ref="input" @change="handleChange" class="form-select" id="input"${this.disabled ? ' disabled' : ''}${this.readonly ? ' readonly' : ''}${this.required ? ' required' : ''} class="form-control" @ref="input" @change="onChange">${this.default}</select>
        <div @ref="error" class="invalid-feedback"></div>`;
    }

    async handleConnect() {
        this.default = this.innerHTML;
        this.target = this.hasAttribute('target') ? document.getElementById(this.getAttribute('target')) : '';
        this.postcode = this.hasAttribute('postcode') ? document.getElementById(this.getAttribute('postcode')) : '';
    }

    handleChange(e) {
        this.value = e.target.value;

        if (this.target) this.target.setAttribute('country_id', this.value);
    }
});