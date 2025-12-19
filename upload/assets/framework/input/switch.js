import { WebComponent } from '../library/webcomponent.js';

class XSwitch extends WebComponent {
    static observed = ['checked'];
    element = HTMLInputElement;

    get checked() {
        return this.getAttribute('checked') == 1 ? 1 : 0;
    }

    set checked(value) {
        if (this.checked != value) {
            this.setAttribute('checked', value);
        }
    }

    async connected() {
        this.addEventListener('[checked]', this.onChecked);

        let html  = '';

        html += '<div class="form-switch form-switch-lg">';
        html += '  <input type="hidden" name="' + this.getAttribute('name') + '" value=""/>';
        html += '  <input type="checkbox" name="' + this.getAttribute('name') + '" value="' + this.getAttribute('value') + '" class="form-check-input"' + (this.checked ? ' checked' : '') + '/>';
        html += '</div>';

        this.innerHTML = html;

        this.element = this.querySelector('input[type=\'checkbox\']');

        this.element.addEventListener('change', this.onChange);

        if (this.hasAttribute('input-id')) {
            this.element.setAttribute('id', this.getAttribute('input-id'));
        }
    };

    onChange(e) {
        this.checked = e.target.checked ? 1 : 0;
    }

    onChecked(e) {
        this.element.checked = e.detail.value_new == 1 ? true : false;
    }
}

customElements.define('x-switch', XSwitch);