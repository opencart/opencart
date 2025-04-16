import { WebComponent } from './../webcomponent.js';

const template = `
<select name="{{ name }}" id="{{ id }}" class="{{ class }}">
  {% for zone in zones %}
  <option value="{{ zone.zone_id }}"{% if zone.zone_id == value %} selected{% endif %}>{{ zone.name }}</option>
  {% endfor %}
</select>`;

class XZone extends WebComponent {
    static observed = ['data-country-id'];

    data = {
        id: '',
        name: '',
        value: 0,
        country_id: 0,
        zones: []
    };

    event = {
        connected: async () => {
            // Add the data attributes to the data object
            this.data.id = this.getAttribute('data-id');
            this.data.name = this.getAttribute('data-name');
            this.data.value = this.getAttribute('data-value');
            this.data.country_id = this.getAttribute('data-country-id');

            let country = await (await fetch('./data/country.' + this.data.country_id + '.json')).json();

            //this.data.zones = country.zone;

            this.addStylesheet('bootstrap.css');
            this.addStylesheet('fontawesome.css');

            //this.shadow.innerHTML = await this.render('zone.html', this.data);

            this.shadow.addEventListener('change', this.event.onchange);
        },
        onchange: (e) => {
            this.data.value = e.target.value;

            this.setAttribute('data-value', this.data.value);
        },
        changed: async (name, value_old, value_new) => {
            if (name == 'data-country-id' && value_new) {
                this.data.country_id = value_new;

                let country = await (await fetch('./data/country.' + value_new + '.json')).json();

                this.data.zones = country.zone;

                this.shadow.innerHTML = await this.render('zone.html', this.data);
            }
        }
    };
}

customElements.define('x-zone', XZone);