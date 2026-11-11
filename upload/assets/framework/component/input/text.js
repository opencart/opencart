import { WebComponent } from '../../library.js';

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
	
	get required() {
		return this.hasAttribute('required');
	}

	get disabled() {
		return this.hasAttribute('disabled');
	}

	get readonly() {
		return this.hasAttribute('readonly');
	}

    get form() {
        return this.internals.form;
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
		this.setAttribute('value', e.target.value);
    }

    // A native change event does not leave the shadow root, so send it on.
    onChange(e) {
        this.setAttribute('value', e.target.value);
    }
	
	handleValue(name, value) {
        this.input.setAttribute('value', value);

        if (this.input.validity.valid) {
            this.internal.setValidity({});
        } else {
            this.internal.setValidity(this.input.validity, this.input.validationMessage, this.input);
		}
	}

	handleRequired(name, value) {
		this.update();
	}

	handleDisabled(name, value) {
		this.update();
	}

	handleReadonly(name, value) {
		this.update();
	}

    setError(error) {
        this.input.classList.toggle('is-invalid', !!error);

        this.error.textContent = error ?? '';

		if (error) {
			this.internal.states.add('invalid');
		} else {
			this.internal.states.delete('invalid');
		}
	}
}

customElements.define('input-text', InputText);