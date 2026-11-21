import { loader, local } from '../../framework.js';
import { InputSelect } from './select.js';

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
		'readonly',
		'hidden'
	];
	
	get country_id() {
		return this.getAttribute('country_id');
	}

	set country_id(value) {
		this.setAttribute('country_id', value);
	}

	async render() {		
		let zones = [];

		let country = await loader.storage('localisation/country-' + Number(this.country_id));
		
		if (country instanceof Map) {
			zones = country.get('zones') || [];
		}

		return `
			<label @ref="label" for="input-zone">${this.label}</label>
			<select name="${this.name}" id="input-zone"${this.disabled ? ` disabled` : ``}${this.readonly ? ` readonly` : ``}${this.required ? ` required` : ``} @ref="input" @change="onChange" class="form-control">${zones.length !== 0 ? this.html : ``}${zones.map(zone => `<option value="${zone.zone_id}"${zone.zone_id === this.value ? ` selected` : ``}>${zone.description[local.get('language')] ? zone.description[local.get('language')].name : ''}</option>`).join('')}</select>
			<div @ref="error" class="invalid-feedback"></div>
		`;
	}
	
	// A native change event does not leave the shadow root, so send it on.
	onChange(e) {
		this.value = e.target.value;
	}

	handleValue(name, value) {
		this.update();
	}

	handleCountryId(name, value) {
		this.update();
	}
});