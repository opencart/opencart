import { WebComponent } from './../webcomponent.js';

class XZone extends WebComponent {
    default = HTMLInputElement;
    element = HTMLInputElement;

    event = {
        connected: async () => {
            this.default = this.innerHTML;

            // Create the select element
            this.innerHTML = '<select name="' + this.getAttribute('name') + '" id="' + this.getAttribute('input-id') + '" class="' + this.getAttribute('input-class') + '">' + this.default + '</select>';

            this.element = this.querySelector('select');

            // Add the on change event
            this.element.addEventListener('change', this.event.onchange);

            // Get the country id from the target element
            let element = document.querySelector(this.getAttribute('target'));

            element.querySelector('select').addEventListener('change', this.event.changed);

            //observer.observe(element, config);
            let response = this.storage.fetch('localisation/country.' + this.getAttribute('value'));

            response.then(this.event.onloaded);
        },
        onloaded: (country) => {
            let html = this.default;
            let zones = country['zone'];

            for (let i in zones) {
                html += '<option value="' + zones[i].zone_id + '"';

                if (zones[i].zone_id == this.getAttribute('value')) {
                    html += ' selected';
                }

                html += '>' + zones[i].name + '</option>';
            }

            this.element.innerHTML = html;
        },
        onchange: async (e) => {
            this.setAttribute('value', e.target.value);
        }
    };
}

customElements.define('x-zone', XZone);