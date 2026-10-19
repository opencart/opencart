import { WebComponent } from '../../engine.js';

const escape = (value) => String(value ?? '').replaceAll('&', '&amp;').replaceAll('"', '&quot;').replaceAll('<', '&lt;').replaceAll('>', '&gt;');

/*
 * <input-text name="firstname" id="input-firstname" value="" placeholder="First Name" required></input-text>
 *
 * A text input for forms.
 *
 * - The tag is form-associated, so its value is part of FormData and it takes
 *   part in the form's validation (required).
 * - Set `error` in its state to show a message under the field. The field is
 *   marked invalid (`:state(invalid)` and `.is-invalid`) until the user types.
 * - A `change` event is sent on from the inner input, so `@change` works on the tag.
 *
 * Attributes: name, value, placeholder, required, disabled, readonly.
 */
export class InputText extends WebComponent {
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
            value: this.getAttribute('value') ?? '',
            error: '',
            required: this.hasAttribute('required'),
            disabled: this.hasAttribute('disabled'),
            readonly: this.hasAttribute('readonly')
        };
    }

    styles() {
        return `
            :host {
                display: block;
            }
            :host(:state(invalid)) .form-control {
                border-color: var(--bs-form-invalid-border-color, #dc3545);
            }
            :host(:state(invalid)) .form-control:focus {
                box-shadow: 0 0 0 .25rem rgb(220 53 69 / .25);
            }
            .invalid-feedback {
                display: none;
                margin-top: .25rem;
                font-size: .875em;
                color: var(--bs-form-invalid-color, #dc3545);
            }
            .invalid-feedback:not(:empty) {
                display: block;
            }
        `;
    }

    render() {
        let html = '<input type="text" name="' + this.getAttribute('name') + '" value="' + this.getAttribute('value') + '"';

        if (this.hasAttribute('placeholder')) {
            html += ' placeholder="' + this.getAttribute('placeholder') + '"';
        }

        html += ' class="form-control" @ref="input" @input="onInput" @change="onChange"';

        if (this.state.get('disabled')) {
            html += ' disabled';
        }

        if (this.state.get('readonly')) {
            html += ' readonly';
        }

        if (this.state.get('required')) {
            html += ' required';
        }

        html += '/>';
        // Always there, so an error can be shown without a re-render. Empty is hidden.
        html += '<div @ref="error" class="invalid-feedback"></div>';

        return html;
    }

    handleConnect() {
        this.internal.setFormValue(this.getAttribute('value'));
    }

    stateValue(value) {
        value = value ?? '';

        this.internal.setFormValue(value);

        this.setAttribute('value', value);

        if (this.input) this.input.setAttribute('value', value);

        if (this.input.validity.valid) {
            this.internal.setValidity({});
        } else {
            this.internal.setValidity(this.input.validity, this.input.validationMessage, this.input);
        }
    }

    // Updates the field in place, so nothing the user typed (or focus) is lost.
    stateError(error) {
        if (!this.input) return;

        this.input.classList.toggle('is-invalid', !!error);

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

    stateRequired(value) {
        value = value ?? false;

        this.toggleAttribute('required', value);

        if (this.input) this.input.toggleAttribute('required', value);
    }

    stateDisabled(value) {
        value = value ?? false;

        this.toggleAttribute('disabled', value);

        if (this.input) this.input.toggleAttribute('disabled', value);
    }

    stateReadonly(value) {
        value = value ?? false;

        this.toggleAttribute('readonly', value);

        if (this.input) this.input.toggleAttribute('readonly', value);
    }

    // Handles the input event and updates the component state with the new value.
    onInput(e) {
        this.state.set('value', e.target.value);
    }

    // A native change event does not leave the shadow root, so send it on.
    onChange(e) {
        this.state.set('value', e.target.value);
    }

    attributeName(name, value) {
        this.update();
    }

    attributeValue(name, value) {
        this.state.set('value', value ?? '');
    }

    attributeRequired(name, value) {
        this.state.set('required', value !== null);
    }

    attributeDisabled(name, value) {
        this.state.set('disabled', value !== null);
    }

    attributeReadonly(name, value) {
        this.state.set('readonly', value !== null);
    }
}

customElements.define('input-text', InputText);
