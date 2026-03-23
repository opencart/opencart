import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

class XZone extends WebComponent {
    default = HTMLInputElement;
    country_id = 0;
    target = '';
    zones = [];

    get value() {
        return this.getAttribute('value');
    }

    set value(value) {
        this.setAttribute('value', value);
    }

    async connected() {
        this.default = this.innerHTML;
        this.target = document.getElementById(this.getAttribute('target'));
        this.country_id = this.target.value;

        console.log(this.target);
        console.log(this.target.getAttribute('value'));

        let country_info = await loader.storage('localisation/country-' + this.target.getAttribute('value'));

        console.log(country_info);

        if (country_info) {
            this.zones = country_info['zone'];
        }

        //this.target.addEventListener('[value]', this.changeCountry.bind(this));
    }

    async render() {
        let html = '<select name="' + this.getAttribute('name') + '" id="' + this.getAttribute('input-id') + '" data-on="change:onChange" class="form-select">' + this.default;

        console.log(this.zones);

        for (let zone of this.zones) {
            html += '<option value="' + zone.zone_id + '"';

            if (zone.zone_id == this.value) {
                html += ' selected';
            }

            let name = '';

            if (this.language in zone.description) {
                name = zone.description[this.language].name;
            }

            html += '>' + name + '</option>';
        }

        //if (this.target.value != 0) {

        //}

        html += '</select>';

        console.log(html);

        return html;
    }

    onChange(e) {
        this.value = e.target.value;
    }

    async changeCountry(e) {
        let value = e.target.value;

        if (value != 0) {
            let country_info = await loader.storage('localisation/country-' + value);


            //if (country_info) {
            //    this.zones = country_info['zone'];
            //}
        }
    }
}

customElements.define('x-zone', XZone);