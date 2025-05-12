import { WebComponent } from './../webcomponent.js';

class XZone extends WebComponent {
    static observed = ['value'];
    default = HTMLInputElement;
    element = HTMLInputElement;
    target = HTMLInputElement;
    zones = [];

    event = {
        connected: async () => {
            this.default = this.innerHTML;

            // Create the select element
            this.innerHTML = '<select name="' + this.getAttribute('name') + '" id="' + this.getAttribute('input-id') + '" class="' + this.getAttribute('input-class') + '">' + this.default + '</select>';

            this.addEventListener('attribute:value', this.event.changeValue);

            this.element = this.querySelector('select');

            this.element.addEventListener('change', this.event.onchange);

            // Country Element
            this.target = document.getElementById(this.getAttribute('target'));

            this.target.addEventListener('attribute:value', this.event.changeCountry.bind(this));

            let value = this.target.getAttribute('value');

            if (value > 0) {
                console.log(value);

                let response = this.storage.fetch('localisation/country-' + value);

                response.then(this.event.onloaded).then(this.event.render);
            }
        },
        onloaded: (country) => {
            this.zones = country['zone'];
        },
        render: () => {
            let html = this.default;

            for (let i in this.zones) {
                html += '<option value="' + this.zones[i].zone_id + '"';

                if (this.zones[i].zone_id == this.getAttribute('value')) {
                    html += ' selected';
                }

                html += '>' + this.zones[i].name + '</option>';
            }

            this.element.innerHTML = html;
        },
        onchange: (e) => {
            this.setAttribute('value', e.target.value);
        },
        changeValue: (e) => {
            let value = e.detail.value_new;

            if (this.element.value != value) {
                this.element.value = value;
            }
        },
        changeCountry: (e) => {
            let value = e.target.getAttribute('value')

            if (value > 0) {
                let response = this.storage.fetch('localisation/country-' + value);

                response.then(this.event.onloaded).then(this.event.render);
            } else {
                this.zones = [];
                this.event.render();
            }
        }
    };
}

customElements.define('x-zone', XZone);