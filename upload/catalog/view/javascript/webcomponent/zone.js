import { WebComponent } from './../webcomponent.js';

class XZone extends WebComponent {
    event = {
        connected: async () => {
            // Add the select element to the shadow DOM
            this.shadow.innerHTML = '<select name="' + this.getAttribute('name') + '" id="' + this.getAttribute('input-id') + '" class="' + this.getAttribute('input-class') + '">' + this.innerHTML + '</select>';

            this.addStylesheet('bootstrap.css');
            this.addStylesheet('fonts/fontawesome/css/fontawesome.css');

            this.shadow.addEventListener('change', this.event.onchange);

            // Get the country id from the target element
            let element = document.querySelector(this.getAttribute('target'));

            element.shadow.querySelector('select').addEventListener('change', this.event.changed);

            let response = await fetch('./catalog/view/data/localisation/country.' + element.getAttribute('value') + '.' + this.getAttribute('language') + '.json');

            response.json().then(this.event.onloaded);
        },
        changed: async (e) => {
            let response = await fetch('./catalog/view/data/localisation/country.' + e.target.value + '.' + this.getAttribute('language') + '.json');

            response.json().then(this.event.onloaded);
        },
        onloaded: (country) => {
            let html = this.innerHTML;
            let zones = country['zone'];

            for (let i in zones) {
                html += '<option value="' + zones[i].zone_id + '"';

                if (zones[i].zone_id == this.getAttribute('value')) {
                    html += ' selected';
                }

                html += '>' + zones[i].name + '</option>';
            }

            this.shadow.querySelector('select').innerHTML = html;
        },
        onchange: async (e) => {
            this.setAttribute('value', e.target.value);
        }
    };
}

customElements.define('x-zone', XZone);