import { WebComponent } from '../component.js';
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
class XZone extends WebComponent {
    static observed = ['country_id'];

    default = HTMLInputElement;

    get value() {
        return this.getAttribute('value');
    }

    set value(value) {
        this.setAttribute('value', value);
    }

    async connected() {
        this.default = this.innerHTML;
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

        let country = await loader.storage('localisation/country-' + this.getAttribute('country_id'));

        if (country !== undefined) {
            for (let zone of country['zone']) {
                html += '<option value="' + zone.zone_id + '"';

                if (zone.zone_id == this.value) {
                    html += ' selected';
                }

                let name = '';

                if (config.config_language in zone.description) {
                    name = zone.description[config.config_language].name;
                }

                html += '>' + name + '</option>';
            }
        }

        html += '</select>';

        return html;
    }

    onChange(e) {
        this.value = e.target.value;
    }
}

customElements.define('x-zone', XZone);