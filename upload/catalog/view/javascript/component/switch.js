import { WebComponent } from '../index.js';

customElements.define('input-switch', class extends WebComponent {
    formAssociated = true;

    get checked() {
        return this.hasAttribute('checked');
    }

    set checked(value) {
        this.toggleAttribute('checked', value);
    }

    render() {
        let html = '<div class="form-switch form-switch-lg">';

        html += '  <input type="hidden" name="' + this.getAttribute('name') + '" value=""/>';
        html += '  <input type="checkbox" name="' + this.getAttribute('name') + '" value="' + this.getAttribute('value') + '"';

        if (this.hasAttribute('input-id')) {
            html += ' id="' + this.getAttribute('input-id') + '"';
        }

        html += ' data-on="change:onChange" class="form-check-input"';

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
    }

    onChange(e) {
        this.checked = e.target.checked;
    }
});