import { WebComponent } from './../webcomponent.js';

class XSwitch extends WebComponent {
    static observed = ['checked'];
    element = HTMLInputElement;

    get checked() {
        return this.hasAttribute('checked');
    }

    set checked(value) {
        let checked = Boolean(value);

        if (checked) {
            this.setAttribute('checked', '');
        } else {
            this.removeAttribute('checked');
        }
    }

    event = {
        connected: async () => {
            this.addEventListener('[checked]', this.event.ontoggle);

            let html  = '';

            html += '<div class="' + this.getAttribute('input-class') + '">';
            html += '  <input type="hidden" name="' + this.getAttribute('name') + '" value=""/>';
            html += '  <input type="checkbox" name="' + this.getAttribute('name') + '" value="' + this.getAttribute('value') + '" class="form-check-input"' + (this.checked ? ' checked' : '') + '/>';
            html += '</div>';

            this.innerHTML = html;

            this.element = this.querySelector('input[type=\'checkbox\']');

            this.element.addEventListener('change', this.event.onchange);

            if (this.hasAttribute('input-id')) {
                this.element.setAttribute('id', this.getAttribute('input-id'));
            }
        },
        onchange: (e) => {
            this.checked = e.target.checked;
        },
        ontoggle: (e) => {
            console.log(e);

            if (e.detail.value_new !== null) {
                this.element.setAttribute('checked', '');
            } else {
                this.element.removeAttribute('checked');
            }
        }
    };
}

customElements.define('x-switch', XSwitch);