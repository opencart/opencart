import { WebComponent } from './../webcomponent.js';

class XZone extends WebComponent {
    static observed = ['data-country-id'];

    target; ''
    event = {
        connected: async () => {
            // Add the select element to the shadow DOM
            this.shadow.innerHTML = '<select name="' + this.getAttribute('name') + '" id="' + this.getAttribute('input-id') + '" class="' + this.getAttribute('input-class') + '">' + this.innerHTML + '</select>';

            this.addStylesheet('bootstrap.css');
            //this.addStylesheet('fonts/fontawesome/css/fontawesome.css');

            this.shadow.addEventListener('change', this.event.onchange);

            let element = document.querySelector(this.getAttribute('target'));

            console.log(element);
            console.log(element.getAttribute('value'));

            let response = await fetch('./data/country.' + element.getAttribute('value') + '.' + this.getAttribute('language') + '.json');

            response.json().then(this.event.onloaded);
        },
        onloaded: (country) => {
            //this.data.zones = country.zone;
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
        onchange: (e) => {
            this.setAttribute('value', e.target.value);
        },
        changed: async (name, value_old, value_new) => {
            if (name == 'data-country-id' && value_new) {
                this.data.country_id = value_new;

                let country = await (await fetch('./data/country.' + value_new + '.json')).json();

                this.data.zones = country.zone;

                this.shadow.innerHTML = await this.render('zone.html', this.data);



                // input-postcode
                if (this.hasAttribute('postcode')) {
                    //   document.querySelector(this.getAttribute('postcode')).addClass('required');
                }

                // Apply change to target element
                //let target = document.querySelector(this.getAttribute('target'));

                // target.setAttribute('data-country-id', e.target.value);

            }
        }
    };
}

customElements.define('x-zone', XZone);