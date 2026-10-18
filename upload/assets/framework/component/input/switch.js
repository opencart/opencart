import { WebComponent } from '../../engine.js';

customElements.define('input-switch', class extends WebComponent {
    static formAssociated = true;
    static observedAttributes = [
        'name',
        'value',
        'required',
        'disabled',
        'readonly'
    ];

    states() {
        return {
            error: '',
            checked: false,
            required: false,
            disabled: false,
            readonly: false
        };
    }

    get checked() {
        return this.hasAttribute('checked');
    }

    set checked(value) {
        this.toggleAttribute('checked', value);
    }

    render() {
        let html = '<div class="form-switch form-switch-lg">';

        html += '<input type="hidden" name="' + this.getAttribute('name') + '" value="" @change="handleChange"/>';
        html += '<input type="checkbox" name="' + this.getAttribute('name') + '" value="' + this.getAttribute('value') + '" @change="handleChange" class="form-check-input"';

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
        html += '<div @ref="error" class="invalid-feedback"></div>';

        return html;
    }

    handleChange(e) {
        this.checked = e.target.checked;
    }

    // Updates the field in place, so nothing the user typed (or focus) is lost.
    stateChecked(value) {
        value = value ?? false;

        this.toggleAttribute('checked', value);

        if (this.input) this.input.toggleAttribute('checked', value);
    }

    stateError(error) {
        if (!this.input) return;

        this.input.classList.toggle('is-invalid', !!error);

        console.log('is-invalid', error);

        this.error.textContent = error ?? '';

        try {
            if (error) {
                this.internal.states.add('invalid');
            } else {
                this.internal.states.delete('invalid');
            }
        } catch (e) {
            // CustomStateSet not supported; .is-invalid still shows the error.
        }
    }
});