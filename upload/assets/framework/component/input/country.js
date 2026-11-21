import { loader, local } from '../../framework.js';
import { InputSelect } from './select.js';
/**
 * input-country
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
	handelConnect() { 
		let country = countries.find(country => country.country_id === this.value);

		if (country) { 
			this.setAttribute('postcode', country.postcode_required);
		}
	}

	render() {
		return `
			<label @ref="label" for="input-country">${this.label}</label>
			<select name="${this.name}" id="input-country"${this.disabled ? ` disabled` : ``}${this.readonly ? ` readonly` : ``}${this.required ? ` required` : ``} @ref="input" @change="onChange" class="form-control">${this.html}${countries.map(country => `<option value="${country.country_id}"${country.country_id === this.value ? ` selected` : ``}>${country.description[local.get('language')] ? country.description[local.get('language')].name : ``}</option>`).join('')}</select>
			<div @ref="error" class="invalid-feedback"></div>
        `;
	}
	
    // A native change event does not leave the shadow root, so send it on.
	onChange(e) {
		this.value = e.target.value;
	}

	handleValue(name, value_new, value_old) {
		console.log(name);

		this.emit('custom-change', {
			name: name,
			value_new: value_new,
			value_old: value_old
		});
	}
}

customElements.define('input-country', InputCountry);