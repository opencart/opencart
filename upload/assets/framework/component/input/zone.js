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
        'country_id',
        'required',
        'disabled',
        'readonly'
	];
	
	#options = [];
	
    default = HTMLInputElement;

    get value() {
        return this.getAttribute('value');
    }

    set value(value) {
        this.setAttribute('value', value);
    }

	constructor() { 
		super();
		
		this.#options = Array.from(this.querySelectorAll('option')).map(option => {
			return {
				text: option.outerHTML,
				value: option.value
			};
		});

		for (let country of config.countries) {
			this.#options.push({
				text: country.description[local.get('language')] ? country.description[local.get('language')].name : '',
				value: country.country_id
			});
		}
	}

    async render() {
        let html = '<select name="' + this.getAttribute('name') + '" id="select" @change="handleChange" class="form-select"';

        if (this.hasAttribute('required')) {
            html += ' required';
        }

        if (this.hasAttribute('disabled')) {
            html += ' disabled';
        }

        html += '>' + this.default;

        //if () {

        //}

        let country = await loader.storage('localisation/country-' + this.getAttribute('country_id'));

        console.log(country);

        if (country instanceof Map) {
            for (let zone of country['zone']) {
                html += '<option value="' + zone.zone_id + '"';

                if (zone.zone_id == this.value) {
                    html += ' selected';
                }

                let name = '';

                if (local.get('language') in zone.description) {
                    name = zone.description[local.get('language')].name;
                }

                html += '>' + name + '</option>';
            }
        }

        html += '</select>';

        return html;
    }

    async handleConnect() {
        this.default = this.innerHTML;
    }

    async handleChange(e) {
		this.value = e.target.value;
		
		let country = await loader.storage('localisation/country-' + this.getAttribute('country_id'));
    }
});