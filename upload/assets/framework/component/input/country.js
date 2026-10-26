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
	#options = [];
    target = '';

	constructor() { 
		super();
        
		this.#options = Array.from(this.querySelectorAll('option')).map(option => {
			return {
				text: option.outerHTML,
				value: option.value
			};
		});

        for (let country of countries) {
			this.#options.push({
				text: country.description[local.get('language')] ? country.description[local.get('language')].name : '',
				value: country.country_id
			});
		}
	}

    async handleConnect() {
        this.target = this.hasAttribute('target') ? document.getElementById(this.getAttribute('target')) : '';
        this.postcode = this.hasAttribute('postcode') ? document.getElementById(this.getAttribute('postcode')) : '';
    }

    async handleChange(e) {
        this.value = e.target.value;

		if (this.target) this.target.setAttribute('country_id', this.value);
		
        if (this.postcode) {
            if (this.value in countries && countries[this.value].postcode_required == 1) {
                this.postcode.setAttribute('required', '');
            } else {
                this.postcode.removeAttribute('required');
            }
        }
	}
}

customElements.define('input-country', InputCountry);