import { WebComponent } from '../component.js';

class XZone extends WebComponent {
    static observed = ['value'];
    default = HTMLInputElement;
    element = HTMLInputElement;
    target = HTMLInputElement;
    zones = [];

    get value() {
        return this.getAttribute('value');
    }

    set value(value) {
        if (this.getAttribute('value') != value) {
            this.setAttribute('value', value);
        }

        if (this.element.value != value) {
            this.element.value = value;
        }
    }

    async connected () {
        this.default = this.innerHTML;

        // Create the select element
        this.innerHTML = '<select name="' + this.getAttribute('name') + '" id="' + this.getAttribute('input-id') + '" class="form-select">' + this.default + '</select>';

        this.addEventListener('[value]', this.changeValue);

        this.element = this.querySelector('select');

        this.element.addEventListener('change', this.onchange);

        // Country Element
        this.target = document.getElementById(this.getAttribute('target'));

        this.target.addEventListener('[value]', this.changeCountry.bind(this));

        if (this.target.value != 0) {
            let response = this.storage.fetch('localisation/country-' + this.target.value);

            response.then(this.onloaded);
            response.then(this.option);
        }
    }

    onloaded(country) {
        this.zones = country['zone'];
    }

    option() {
        let html = this.default;

        for (let i in this.zones) {
            html += '<option value="' + this.zones[i].zone_id + '"';

            if (this.zones[i].zone_id == this.value) {
                html += ' selected';
            }

            html += '>' + this.zones[i].name + '</option>';
        }

        this.element.innerHTML = html;
    }

    onChange(e) {
        this.value = e.target.value;
    }

    changeValue(e) {
        this.value = e.detail.value_new;
    }

    changeCountry(e) {
        let value = e.target.value;

        if (value != 0) {
            let response = this.storage.fetch('localisation/country-' + value);

            response.then(this.onloaded);
            response.then(this.option);
        } else {
            this.zones = [];
            this.option();
        }
    }
}

customElements.define('x-zone', XZone);