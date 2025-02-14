import { WebComponent } from './../webcomponent.js';

class XSwitch extends WebComponent {
    data = {
        id: '',
        name: '',
        value: 0,
    };

    event = {
        connected: async () => {
            // Add the data attributes to the data object
            this.data.id = this.getAttribute('data-id');
            this.data.name = this.getAttribute('data-name');
            this.data.value = this.getAttribute('data-value');

            this.addStylesheet('bootstrap.css');
            this.addStylesheet('fontawesome.css');

            this.shadow.innerHTML = await this.render('switch.html', this.data);

            this.shadow.addEventListener('change', this.event.onchange);
        },
        onchange: (e) => {
            this.setAttribute('data-value', e.target.checked ? e.target.value : 0);
        }
    };
}

customElements.define('x-switch', XSwitch);

