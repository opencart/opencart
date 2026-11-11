import { loader, local } from '../../library.js';
import { InputSelect } from './select.js';

// Config
const config = await loader.config('default');

/**
 * input-zone
 *
 * @example <input-zone name="" value="" target="" input-id=""></input-zone>
 *
 * @tag     input-zone
 *
 * @attr   string    name   name of the form element
 *
 * optional required disabled
 */
customElements.define('input-zone', class extends InputSelect {
	static observedAttributes = [
		'value',
		'country_id',
		'required',
		'disabled',
		'readonly'
	];
	
	async render() {
		let zones = [];

		let country = await loader.storage('localisation/country-' + this.getAttribute('country_id'));
		
		if (country instanceof Map) {
			zones = country['zone'] || [];
		}

		return `
			<label @ref="label" for="input">${this.label}</label>
			<select name="${this.name}" id="input"${this.disabled ? ` disabled` : ``}${this.readonly ? ` readonly` : ``}${this.required ? ` required` : ''} @ref="input" @change="onChange" class="form-control">${this.html}${zones.map(zone => `<option value="${zone.zone_id}"${zone.zone_id === this.value ? ' selected' : ''}>${zone.description[local.get('language')] ? zone.description[local.get('language')].name : ''}</option>`).join('')}</select>
			<div @ref="error" class="invalid-feedback"></div>
		`;
	}
	
	// A native change event does not leave the shadow root, so send it on.
	onChange(e) {
		this.value = e.target.value;
	}

	async handleChange(e) {
		this.value = e.target.value;
		
		this.update();

		//this.form.zone.setAttribute('country_id', this.form.country.value);
		//this.postcode = this.hasAttribute('postcode') ? document.getElementById(this.getAttribute('postcode')) : '';
	}
});