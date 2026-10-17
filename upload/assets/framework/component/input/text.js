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
        'value',
        'required',
        'disabled',
        'readonly'
    ];

    static states() {
        return {
            value: '',
            error: '',
            required: false,
            disabled: false,
            readonly: false
        };
    }

    styles() {
        return `
            :host { display: block; }
            :host(:state(invalid)) .form-control { border-color: var(--bs-form-invalid-border-color, #dc3545); }
            :host(:state(invalid)) .form-control:focus { box-shadow: 0 0 0 .25rem rgb(220 53 69 / .25); }
            .invalid-feedback { display: none; margin-top: .25rem; font-size: .875em; color: var(--bs-form-invalid-color, #dc3545); }
            .invalid-feedback:not(:empty) { display: block; }
        `;
    }

    render() {
        let error = this.state.get('error');

        let html = '<input type="text" name="' + escape(this.getAttribute('name')) + '" value="' + escape(this.state.get('value')) + '"';

        if (this.hasAttribute('placeholder')) {
            html += ' placeholder="' + escape(this.getAttribute('placeholder')) + '"';
        }

        html += ' class="form-control' + (error ? ' is-invalid' : '') + '" @ref="input" @input="onInput" @change="onChange"';

        if (this.hasAttribute('disabled')) {
            html += ' disabled';
        }

        if (this.hasAttribute('readonly')) {
            html += ' readonly';
        }

        if (this.hasAttribute('required')) {
            html += ' required';
        }

        html += '/>';

        // Always there, so an error can be shown without a re-render. Empty is hidden.
        html += '<div @ref="feedback" class="invalid-feedback">' + escape(error) + '</div>';

        return html;
    }

    constructor() {
        super();

        this.state.set({
            value: this.getAttribute('value') ?? '',
            error: '',
            required: this.hasAttribute('required'),
            disabled: this.hasAttribute('disabled'),
            readonly: this.hasAttribute('readonly')
        });
    }

    async update() {
        await super.update();

        this.#sync();
    }

    onInput(e) {
        this.state.set('value', e.target.value);
    }

    // A native change event does not leave the shadow root, so send it on.
    onChange(e) {
        this.state.set('value', e.target.value);
    }

    stateValue(value) {
        this.input.value = value;

        this.setAttribute('value', value);

        if (this.input.validity.valid) {
            this.internal.setValidity({});
        } else {
            this.internal.setValidity(this.input.validity, this.input.validationMessage, this.input);
        }
    }

    attributeValue(name, value) {
        this.state.set('value', value ?? '');

        if (this.input) this.input.value = value ?? '';
    }

    // Updates the field in place, so nothing the user typed (or focus) is lost.
    stateError(error) {
        if (!this.input) return;

        this.input.classList.toggle('is-invalid', !!error);

        this.feedback.textContent = error ?? '';

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

    attributeRequired(name, value) {
        if (this.input) this.input.required = value !== null;

        this.#sync();
    }

    attributeDisabled(name, value) {
        if (this.input) this.input.disabled = value !== null;

        this.#sync();
    }

    attributeReadonly(name, value) {
        if (this.input) this.input.readOnly = value !== null;

        this.#sync();
    }






    // Called by the form on reset.
    formResetCallback() {
        let value = this.getAttribute('value') ?? '';

        this.state.set({ value: value, error: '' });

        if (this.input) this.input.value = value;
    }

    // Called by the form when a parent <fieldset> is disabled or enabled.
    formDisabledCallback(disabled) {
        if (this.input) this.input.disabled = disabled || this.hasAttribute('disabled');
    }

    // Called on back/forward navigation and autofill.
    formStateRestoreCallback(value) {
        this.state.set('value', value ?? '');

        if (this.input) this.input.value = value ?? '';
    }
}

customElements.define('input-text', InputText);
