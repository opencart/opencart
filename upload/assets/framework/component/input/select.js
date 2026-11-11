import { WebComponent } from '../../library.js';

/*
 * <input-select name="country" id="input-country" value="" required>
 *   <option value="1">Country 1</option>
 *   <option value="2">Country 2</option>
 * </input-select>
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
export class InputSelect extends WebComponent {
    static formAssociated = true;
    static observedAttributes = [
        'value',
        'required',
        'disabled',
        'readonly'
    ];

	#options = [];

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

	constructor() { 
		super();

		this.html = this.innerHTML;
	}

	addOption(text, value) {
		this.#options.push({ text, value });
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

	render() {
		return `
			<label @ref="label" for="input-select">${this.label}</label>
			<select name="${this.name}" id="input-select"${this.disabled ? ` disabled` : ``}${this.readonly ? ` readonly` : ``}${this.required ? ` required` : ``} @ref="input" @change="onChange" class="form-control">${this.html}${this.#options.map(item => `<option value="${item.value}"${item.value === this.value ? ` selected` : ``}>${item.text}</option>`).join('')}</select>
			<div @ref="error" class="invalid-feedback"></div>
        `;
	}
		
    // A native change event does not leave the shadow root, so send it on.
    onChange(e) {
        this.setAttribute('value', e.target.value);
	}
	
    handleValue(value) {
        this.internal.setFormValue(value);

		this.querySelectorAll('option').forEach(option => option.toggleAttribute('selected', option.value === value));
    
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

    // Updates the field in place, so nothing the user typed (or focus) is lost.
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

customElements.define('input-select', InputSelect);
