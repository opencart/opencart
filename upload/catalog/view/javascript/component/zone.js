import {local, WebComponent} from '../index.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

/**
 * XZone
 *
 * @example <x-zone name="" value="" target="" input-id=""></x-country>
 *
 * @tag     x-zone
 *
 * @attr   string    name   name of the form element
 *
 * optional required disabled
 */
customElements.define('input-zone', class extends WebComponent {
    static observed = ['country_id'];

    default = HTMLInputElement;

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

        let country = await loader.storage('localisation/country-' + this.getAttribute('country_id'));

        console.log(country);

        if (country !== undefined) {
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

    async onConnect() {
        this.default = this.innerHTML;
    }

    onChange(e) {
        this.value = e.target.value;
    }
});