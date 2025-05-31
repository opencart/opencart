import { WebComponent } from './../webcomponent.js';

class XSwitch extends WebComponent {
    static observed = ['value'];
    element = HTMLInputElement;

    get value() {
        return this.getAttribute('value');
    }

    set value(value) {
        if (this.value != value) {
            if (value == 1) {
                this.setAttribute('value', 1);
                this.element.setAttribute('checked', '');
            } else {
                this.setAttribute('value', 0);
                this.element.removeAttribute('checked');
            }
        }
    }

    event = {
        connected: async () => {
            this.addEventListener('[value]', this.event.changeValue);

            let html = '';

            html += '<div class="form-switch form-switch-lg">';
            html += '  <input type="hidden" name="' + this.getAttribute('name') + '" value="0"/>';
            html += '  <input type="checkbox" name="' + this.getAttribute('name') + '" value="1" id="' + this.getAttribute('input-id') + '" class="form-check-input"' + (this.value == 1 ? ' checked' : '') + '/>';
            html += '</div>';

            this.innerHTML = html;

            this.element = this.querySelector('input[type=\'checkbox\']');

            this.element.addEventListener('change', this.event.onchange);
        },
        onchange: (e) => {
            this.value = e.target.checked ? 1 : 0;
        },
        changeValue: (e) => {
            this.value = e.detail.value_new;
        }
    };
}

customElements.define('x-switch', XSwitch);