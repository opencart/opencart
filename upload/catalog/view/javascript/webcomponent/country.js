import { WebComponent } from './../webcomponent.js';

const template = `
<select name="{{ name }}" id="{{ id }}" class="{{ class }}">
  {% for country in countries %}
  <option value="{{ country.country_id }}"{% if country.country_id == value %} selected{% endif %}>{{ country.name }}</option>
  {% endfor %}
</select>`;

class XCountry extends WebComponent {
    data = {
        id: '',
        name: '',
        value: 0,
        countries: []
    };

    event = {
        connected: async () => {

            console.log(this);
            console.log(this.__proto__);
            console.log(this.dataset);
            console.log(this.attributes);



            // Add the data attributes to the data object
            this.data.id = this.getAttribute('data-id');
            this.data.name = this.getAttribute('data-name');
            this.data.value = this.getAttribute('data-oc-value');
            this.data.language = this.getAttribute('data-oc-language');

            this.data.target = this.getAttribute('data-oc-target');





            // Add countries to the data object
            this.data.countries = await (await fetch('./data/country.' + this.data.language + '.json')).json();

            this.addStylesheet('bootstrap.css');
            this.addStylesheet('fontawesome.css');

            this.shadow.innerHTML = await this.render('country.html', this.data);

            this.shadow.addEventListener('change', this.event.onchange);
        },
        onchange: async (e) => {
            this.data.value = e.target.value;

            this.setAttribute('data-value', this.data.value);

            // Apply change to target element
            let target = document.querySelector(this.getAttribute('data-target'));

            target.setAttribute('data-country-id', this.data.value);
        }
    };
}

customElements.define('x-country', XCountry);