import { WebComponent } from '../../framework.js';

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
		'readonly',
		'hidden'
	];

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
		return this.getAttribute('value');
	}
	
	set value(value) { 
		this.setAttribute('value', value);
	}

	get required() {
		return this.hasAttribute('required');
	}

	set required(value) { 
		this.toggleAttribute('required', value == true);
	}

	get disabled() {
		return this.hasAttribute('disabled');
	}

	set disabled(value) { 
		this.toggleAttribute('disabled', value == true);
	}

	get readonly() {
		return this.hasAttribute('readonly');
	}

	set readonly(value) { 
		this.toggleAttribute('readonly', value == true);
	}

	get hidden() {
		return this.hasAttribute('hidden');
	}

	set hidden(value) { 
		this.toggleAttribute('hidden', value == true);
	}

    render() {
        return `
			<label @ref="label" for="input-text">${this.label}</label>
			<input type="text" name="${this.name}" value="${this.value}"${this.label ? ` placeholder="${this.label}"` : ``} id="input-text"${this.disabled ? ` disabled` : ``}${this.readonly ? ` readonly` : ``}${this.required ? ` required` : ``} @ref="input" @input="onInput" @change="onChange" class="form-control"/>
			<div @ref="error" class="invalid-feedback"></div>
		`;
    }

    // Handles the input event and updates the component state with the new value.
    onInput(e) {
		this.value = e.target.value;
    }

    // A native change event does not leave the shadow root, so send it on.
    onChange(e) {
		this.value = e.target.value;
    }
	
	handleValue(name, value) {
        this.input.value = value;
		this.internal.setFormValue(value);
	}

	handleRequired(name, value) {
		this.input.toggleAttribute('required', value !== null);
	}

	handleDisabled(name, value) {
		this.input.toggleAttribute('disabled', value !== null);
	}

	handleReadonly(name, value) {
		this.input.toggleAttribute('readonly', value !== null);
	}

	reset() { 
		this.input.classList.remove('is-invalid');

		this.internal.states.delete('invalid');
	}

	setError(error) {
		//this.input.setCustomValidity(error);

        this.input.classList.toggle('is-invalid', !!error);

        this.error.textContent = error ?? '';

		if (error) {
			this.internal.states.add('invalid');
		} else {
			this.internal.states.delete('invalid');
		}
	}

	validate() { 
		if (!this.input.checkValidity()) {
			this.internal.setValidity(this.input.validity, this.input.validationMessage, this.input);
		} else {
			this.internal.setValidity({});
		}
	}

	reportValidity() {
		return this.internal.reportValidity();
	}

	checkValidity() {
		return this.internal.checkValidity();
	}
}

customElements.define('input-text', InputText);