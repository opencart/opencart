import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('catalog');

// Storage
const countries = await loader.storage('localisation/country');

class XCountry extends WebComponent {
    static observed = ['value', 'postcode'];
    default = HTMLInputElement;
    element = HTMLInputElement;
    countries = [];

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

    set postcode(value) {
        if (this.hasAttribute('target')) {
            this.setAttribute('postcode', value);

            let target = document.getElementById(this.getAttribute('target'));

            if (value == 1) {
                target.setAttribute('required', '');
            } else {
                target.removeAttribute('required');
            }
        }
    }

    get postcode() {
        return this.getAttribute('postcode');
    }

    async render() {
        this.default = this.innerHTML;

        let html = '<select name="' + this.getAttribute('name') + '" id="' + this.getAttribute('input-id') + '" data-on="change:onChange" class="form-select"';

        if (this.hasAttribute('required')) {
            html += ' required';
        }

        if (this.hasAttribute('disabled')) {
            html += ' disabled';
        }

        html += '>' + this.default + '</select>';

        this.innerHTML = html;

        return html;
    }

    option() {
        let html = this.default;

        console.log(this.countries);


        for (let i in this.countries) {
            html += '<option value="' + this.countries[i].country_id + '"';

            if (this.countries[i].country_id == this.value) {
                html += ' selected';
            }



            html += '>' + this.countries[i].name + '</option>';
        }

        this.element.innerHTML = html;
    }

    postcode() {
        if (this.countries[this.value] !== undefined) {
            this.postcode = this.countries[this.value].postcode_required;
        } else {
            this.postcode = 0;
        }
    }

    onChange(e) {
        this.value = e.target.value;
    }
}

customElements.define('x-country', XCountry);