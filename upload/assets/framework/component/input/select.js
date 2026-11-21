import { WebComponent } from '../../framework.js';

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

	constructor() { 
		super();

		const template = this.querySelector('template');

		this.html = template ? template.innerHTML : this.innerHTML;
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
			<label @ref="label" for="input-select">${this.label}</label>
			<select name="${this.name}" id="input-select"${this.disabled ? ` disabled` : ``}${this.readonly ? ` readonly` : ``}${this.required ? ` required` : ``} @ref="input" @change="onChange" class="form-control">${this.html}</select>
			<div @ref="error" class="invalid-feedback"></div>
        `;
	}
		
    // A native change event does not leave the shadow root, so send it on.
    onChange(e) {
        this.value = e.target.value;
	}
	
    handleValue(name, value) {
		this.internal.setFormValue(value);

		if (value !== this.input.value) this.update();
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
