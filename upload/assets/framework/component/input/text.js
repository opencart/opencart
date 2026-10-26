import { WebComponent } from '../../engine.js';

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
const escape = (value) => String(value ?? '').replaceAll('&', '&amp;').replaceAll('"', '&quot;').replaceAll('<', '&lt;').replaceAll('>', '&gt;');

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

    get label() {
        return this.hasAttribute('label') ? this.getAttribute('label') : '';
    }

    get name() {
        return this.getAttribute('name');
    }

    get value() {
        return this.state.get('value');
    }

    get required() {
        return this.state.get('required');
    }

    get disabled() {
        return this.state.get('disabled');
    }

    get readonly() {
        return this.state.get('readonly');
    }

    render() {
        return `
			<label @ref="label" for="input">${this.label}</label>
			<input type="text" name="${this.name}" value="${this.value}"${this.label ? ` placeholder="${this.label}"` : ''} id="input"${this.disabled ? ' disabled' : ''}${this.readonly ? ' readonly' : ''}${this.required ? ' required' : ''} @ref="input" @input="onInput" @change="onChange" class="form-control"/>
			<div @ref="error" class="invalid-feedback"></div>
		`;
    }

    handleConnect() {
        this.internal.setFormValue(this.getAttribute('value'));
    }

    // Handles the input event and updates the component state with the new value.
    onInput(e) {
        this.state.set('value', e.target.value);
    }

    // A native change event does not leave the shadow root, so send it on.
    onChange(e) {
        this.state.set('value', e.target.value);
    }
	
	handleState(name, value, state) {
		if (['required', 'disabled', 'readonly'].includes(name)) {
			this.toggleAttribute(name, value);

			if (this.input) this.input.toggleAttribute(name, value);

			return;
		}

		if (name === 'value') this.handleValue(value);	
		if (name === 'error') this.handleError(value);
	}
	
	handleAttribute(name, value) {
		if (['required', 'disabled', 'readonly'].includes(name)) {
			this.state.set(name, value !== null);

			return;
		}

		if (name === 'name') this.update();
		if (name === 'value') this.state.set('value', value);
	}

    handleValue(value) {
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
    handleError(error) {
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
}

customElements.define('input-text', InputText);
