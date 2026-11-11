import { loader, local } from '../../library.js';
import { InputSelect } from './select.js';
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

// Storage
const countries = await loader.storage('localisation/country');

class InputCountry extends InputSelect {
	render() {
		return `
			<label @ref="label" for="input-country">${this.label}</label>
			<select name="${this.name}" id="input-country"${this.disabled ? ` disabled` : ``}${this.readonly ? ` readonly` : ``}${this.required ? ` required` : ``} @ref="input" @change="onChange" class="form-control">${this.html}${countries.map(country => `<option value="${country.country_id}"${country.country_id === this.value ? ` selected` : ``}>${country.description[local.get('language')] ? country.description[local.get('language')].name : ``}</option>`).join('')}</select>
			<div @ref="error" class="invalid-feedback"></div>
        `;
	}
	
    // A native change event does not leave the shadow root, so send it on.
	onChange(e) {
		console.log('Country changed', e);

		this.setAttribute('value', e.target.value);
		
		this.emit('[change]');
	}

    async handleChange(e) {
        this.value = e.target.value;

        if (this.postcode) {
            if (this.value in countries && countries[this.value].postcode_required == 1) {
                this.postcode.toggleAttribute('required', true);
            } else {
                this.postcode.toggleAttribute('required', false);
            }
        }
	}
}

customElements.define('input-country', InputCountry);