import { WebComponent } from '../library/webcomponent.js';

class XSwitch extends WebComponent {
    get checked() {
        return this.getAttribute('checked') == 1 ? 1 : 0;
    }

    set checked(value) {
        if (this.checked != value) {
            this.setAttribute('checked', value);
        }
    }

    async render() {
        let html  = '';

        html += '<div class="form-switch form-switch-lg">';
        html += '  <input type="hidden" name="' + this.getAttribute('name') + '" value=""/>';
        html += '  <input type="checkbox" name="' + this.getAttribute('name') + '" value="' + this.getAttribute('value') + '"';

        if (this.hasAttribute('input-id')) {
            html += ' id="' + this.getAttribute('input-id') + '"';
        }

        html += ' bind-on="change:onChange" class="form-check-input"';

        if (this.checked) {
            html += ' checked';
        }

        if (this.hasAttribute('disabled')) {
            html += ' disabled';
        }

        if (this.hasAttribute('readonly')) {
            html += ' readonly';
        }

        html += '/>';
        html += '</div>';

        return html;
    };

    onChange(e) {
        this.checked = e.target.checked ? 1 : 0;
    }
}

customElements.define('x-switch', XSwitch);